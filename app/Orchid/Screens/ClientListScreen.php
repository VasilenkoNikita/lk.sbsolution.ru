<?php

namespace App\Orchid\Screens;

use App\Models\User;
use App\Models\UserColor;
use App\Orchid\Filters\ClientActivityFilter;
use App\Orchid\Filters\ColorFilter;
use App\Orchid\Filters\GroupFilter;
use App\Orchid\Filters\NumberOfEmployeesFilter;
use App\Orchid\Filters\TypeOfOwnershipFilter;
use App\Orchid\Filters\TypeOfTaxesFilter;
use App\Orchid\Layouts\ClientListLayout;
use App\Models\Client;
use App\Models\ClientUserSettings;
use App\Orchid\Layouts\Clients\ClientEditLayout;
use Illuminate\Support\Facades\DB;
use App\Orchid\Layouts\Clients\ClientsFiltersLayout;
use App\Orchid\Layouts\Clients\ClientsSimpleFiltersLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ClientListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список клиентов';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Все клиенты';

    /**
     * @var string
     */
    public $choiceGroups = '';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Request $request): array
    {

        if (is_null($request->request->get('group_'))) {
            $userGroups = $request->user()->load('groups');

            foreach ($userGroups->groups as $group) {
                $groups[] = $group->id;
            }
            $request->request->add(['group' => $groups]);

        }else{
            $request->request->add(['group' => array_map('intval', explode(",", $request->request->get('group_')))]);
        }

        if (is_null($request->request->get('clientActivity'))) {
            $request->request->add(['clientActivity' => 1]);
        }
        
        //если у пользователя не настроен раздел клиентов
        if(ClientUserSettings::where('user_id', $request->user()->id)->orderby('position')->get()->isEmpty()){
            //копируем порядок построения таблицы у админа
            $defaultSettings = ClientUserSettings::where('user_id', 1)->orderby('position')->get();
            //перебираем каждую колонку таблицы
            foreach ($defaultSettings as $setting){
                //заполняем для пользователя
                $newSetting = $setting->replicate()->fill([
                    'user_id' => $request->user()->id
                ]);
                //сохраняем
                $newSetting->save();
            }
        }else{
            //если у пользователя настройки не пустые
            //опять берем настройки у админа
            $defaultSettings = ClientUserSettings::where('user_id', 1)->orderby('position')->get();
            //перебираем каждую колонку
            foreach ($defaultSettings as $setting){
                //если какой то колонки не хватает (она добавилась в интерфейсе)
                if (ClientUserSettings::where('user_id', $request->user()->id)->where('row_name', $setting->row_name)->get()->isEmpty()){
                    //добавляем ее пользователю
                    ClientUserSettings::create(
                        [
                            'user_id' => $request->user()->id,
                            'name' => $setting->name,
                            'row_name' => $setting->row_name,
                            'position' => $setting->position
                        ]
                    );
                }
            }
        }

        return [
            'clients' => Client::with('groups','userColors')
                ->filtersApplySelection(ClientsFiltersLayout::class)
                ->filters()
                ->orderBy('type_of_ownership', 'ASC')
                ->orderBy('organization', 'ASC')
                ->paginate(100),

            'settings' => ClientUserSettings::where('user_id', $request->user()->id)->orderby('position')->get(),
            'colors' => UserColor::where('user_id', $request->user()->id)->orderby('position')->get(),

        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {


        return [
            Link::make('Инструкция по работе с разделом')
                ->icon('cloud-download')
                ->href('/dashboard/manuals/2/view')
                ->target('_blank')
                ->premission(),

            ModalToggle::make('Цвета')
                ->modal('ModalColors')
                ->icon('brush')
                ->modalTitle('Настройка цветов')
                ->method('methodForModalColors'),

            ModalToggle::make('Настроить таблицу')
                ->modal('ModalTable')
                ->icon('number-list')
                ->modalTitle('Настройка таблицы')
                ->method('methodForModalTable'),

            Link::make('Создать нового клиента')
                ->icon('pencil')
                ->route('platform.clients.create'),

        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            ClientsFiltersLayout::class,

            Layout::wrapper('wrappers/clients', [
                'table' => ClientListLayout::class,
            ]),

            Layout::modal('ModalTable', [
                Layout::view('clientSettings/index'),
            ])->withoutCloseButton(),

            Layout::modal('ModalColors', [
                Layout::view('colorsSettings/index'),
            ])->withoutCloseButton(),

            Layout::modal('ModalColorsCreate', [
                Layout::view('colorsSettings/create'),
            ])->withoutCloseButton(),

            Layout::modal('asyncModalColorsChoose', [
                Layout::view('colorsSettings/choose'),
            ])->async('asyncGetData')->withoutApplyButton()->withoutCloseButton(),

        ];
    }


    public function asyncGetData($client, $color): array
    {
        return [
            'client' => $client,
            'color' => $color,
        ];
    }


    public function updateAll(Request $request)
    {
        $settings = ClientUserSettings::where('user_id', $request->user()->id)->orderby('position')->get();

        foreach ($settings as $setting) {
            $setting->timestamps = false;
            $id = $setting->id;

            foreach ($request->settings as $settingFrontEnd) {
                if ($settingFrontEnd['id'] == $id) {
                    $setting->update(['position' => $settingFrontEnd['position']]);
                }
            }
        }

        return response('Update Successful.', 200);
    }



    public function updateColors(Request $request)
    {
        function in_array_r($needle, $haystack, $strict = false) {
            foreach ($haystack as $item) {
                if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                    return true;
                }
            }

            return false;
        }

        $colors = UserColor::where('user_id', $request->user()->id)->orderby('position')->get();

        foreach ($colors as $key => $color) {
            $color->timestamps = false;
            $id = $color->id;


            if(!in_array_r($id, $request->colors)){
                $color->delete();
                //Toast::info('Цвет удален');
            }

                foreach ($request->colors as $colorFrontEnd) {

                if ($colorFrontEnd['id'] == $id) {
                    $color->update(['position' => $colorFrontEnd['position']]);
                    $color->update(['name' => $colorFrontEnd['name']]);
                    $color->update(['color' => $colorFrontEnd['color']]);
                }
            }
        }

        return response('Color Update Successful.', 200);
    }

    public function methodForModalTable(Request $request)
    {
        return back();
    }

    public function methodForModalColors(Request $request)
    {
        return back();
    }

    public function methodForChooseColorModal(Request $request)
    {
        $client = Client::where('id', $request->client)->with('userColors')->first();

        if ($request->get('detach_color') === '1' && $client->userColors()->exists()){
            $client->userColors()->detach($client->userColors[0]->id);
            Toast::info('Цвет удален');
            return back()->with(['scroll' => true, 'scrollId' => $request->client]);
        }

        if(!$client->userColors()->exists()){
            $client->userColors()->attach($request->get('color_id'));
        }else{
            $client->userColors()->detach($client->userColors[0]->id);
            $client->userColors()->attach($request->get('color_id'));
        }

        Toast::info('Цвет установлен');
        return back()->with(['scroll' => true, 'scrollId' => $request->client]);
    }

    public function methodForModalCreateColors(Request $request)
    {
        $requestData = $request->get('color');

        $requestData['user_id'] = Auth::user()->id;
        $requestData['position'] = 1;

        UserColor::create($requestData);

        Toast::info('Цвет добавлен');

        return back()->with('colorCreated', true);
    }

    public function updateComment(Request $request)
    {
        $client = Client::where('id',$request->clientid)->first();
        $client->update(['comment' => $request->comment]);

        return Toast::info('Комментарий добавлен');
    }


}
