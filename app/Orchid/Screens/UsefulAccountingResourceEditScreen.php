<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Tag;
use App\Models\UsefulAccountingResource;
use App\View\Components\Tags;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Link;


class UsefulAccountingResourceEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить новый ресурс';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Полезные ресурсы';

    /**
     * Tag list.
     *
     * @var array
     */
    public $taglist = [];

    /**
     * Query data.
     *
     * @param UsefulAccountingResource $UsefulAccountingResource
     *
     * @return array
     */
    public function query(UsefulAccountingResource $UsefulAccountingResource): array
    {
        $this->exists = $UsefulAccountingResource->exists;

        if($this->exists){
            $this->name = 'Редактировать ресурс';
        }


        $tagsPipe = [];
        if($this->exists) {
            $tags = UsefulAccountingResource::with('tags')->where('id', $UsefulAccountingResource->id)->get();
            foreach ($tags[0]['tags'] as $tag) {
                $tagsPipe[] = $tag->name;
            }
        }else{
            $tags = Tag::where('type', 'resource')->get();
        }


        return [
            'UsefulAccountingResource' => $UsefulAccountingResource,
            'tags' => $tags,
            'tagsList' => implode(", ", $tagsPipe),
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
            Button::make('Создать запись')
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
                Input::make('UsefulAccountingResource.resource_name')
                    ->title('Наименование ресурса')
                    ->help('Укажите наименование сайта/сервиса'),

                Input::make('UsefulAccountingResource.resource_link')
                    ->title('Ссылка на ресурс')
                    ->help('Укажите ссылку на сайт/ресурс'),

                TextArea::make('tagsList')
                    ->title('Теги')
                    ->help('Теги указываются через запятую'),

            ]),
            Layout::component(Tags::class),
        ];
    }

    /**
     * @param UsefulAccountingResource $UsefulAccountingResource
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(UsefulAccountingResource $UsefulAccountingResource, Request $request): \Illuminate\Http\RedirectResponse
    {

        $UsefulAccountingResource->fill($request->get('UsefulAccountingResource'))->save();

        if (strpos($request->input('tagsList'), ',')) {
            $tags = explode(", ", $request->input('tagsList'));
            $UsefulAccountingResource->syncTagsWithType($tags, 'resource');
        }else{
            $UsefulAccountingResource->syncTagsWithType([$request->input('tagsList')], 'resource');
        }

        Alert::info('Вы успешно создали запись о ресурсе!');

        return back();
    }

    /**
     * @param UsefulAccountingResource $UsefulAccountingResource
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(UsefulAccountingResource $UsefulAccountingResource)
    {
        $UsefulAccountingResource->delete()
            ? Alert::info('Вы успешно удалили запись о ресурсе!')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.usefulAccountingResources.list');
    }
}
