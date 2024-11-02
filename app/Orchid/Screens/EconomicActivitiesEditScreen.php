<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Imports\EconomicActivitiesImport;
use App\Imports\SectionActivityImport;
use App\Imports\SubActivityImport;
use App\Models\EconomicActivities;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Upload;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Link;

class EconomicActivitiesEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить новый ОКВЭД';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Классификатор видов экономической деятельности';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * Query data.
     *
     * @param EconomicActivities $EconomicActivities
     *
     * @return array
     */
    public function query(EconomicActivities $EconomicActivities): array
    {
        $this->exists = $EconomicActivities->exists;

        if($this->exists){
            $this->name = 'Редактировать ОКВЭД';
        }

        return [
            'EconomicActivities' => $EconomicActivities
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

            ModalToggle::make('Импорт ОКВЭД')
                ->icon('lock-open')
                ->method('asyncImport')
                ->modal('uploadEconomicActivities'),

            Button::make('Создать ОКВЭД')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make('Обновить')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make('Удалить')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exists),
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
            Layout::rows([
                Input::make('EconomicActivities.type_economic_activity')
                    ->title('Наименование ОКВЭД')
                    ->help('Наименование ОКВЭД класса'),

                Input::make('EconomicActivities.code_economic_activity')
                    ->title('Код ОКВЭД класса')
                    ->help('Укажите код ОКВЭД класса'),
            ]),

            Layout::modal('uploadEconomicActivities', [
                Layout::rows([
                    Upload::make('upload')
                        ->title('Загрузите excel файл с ОКВЭД классами'),
                ]),
            ]),
        ];
    }

    /**
     * @param EconomicActivities  $EconomicActivities
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(EconomicActivities $EconomicActivities, Request $request): \Illuminate\Http\RedirectResponse
    {
        $EconomicActivities->fill($request->get('EconomicActivities'))->save();

        Alert::info('Вы успешно создали ОКВЭД класс!');

        return redirect()->route('platform.economicActivities.list');
    }

    /**
     * @param EconomicActivities $EconomicActivities
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(EconomicActivities $EconomicActivities): \Illuminate\Http\RedirectResponse
    {
        $EconomicActivities->delete()
            ? Alert::info('Вы успешно удалили ОКВЭД класс!')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.economicActivities.list');
    }

    public function asyncImport(EconomicActivities $EconomicActivities, Request $request)
    {
        $attach = Attachment::where('id', $request->get('upload'))->get();
        $attachfile = "";
        foreach ($attach as $attachs) {
            $attachfile = public_path() .'/storage/' . $attachs->path . $attachs->name . '.' . $attachs->extension;
        }
        Excel::import(new SubActivityImport, $attachfile);

        return back();
    }
}
