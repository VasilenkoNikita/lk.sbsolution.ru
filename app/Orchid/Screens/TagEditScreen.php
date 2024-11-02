<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Tag;
use Orchid\Screen\Fields\Select;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Link;

class TagEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить новый тэг';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Тэги сервиса';

    /**
     * Query data.
     *
     * @param Tag $tag
     *
     * @return array
     */
    public function query(\App\Models\Tag $tag): array
    {
        $this->exists = $tag->exists;

        if($this->exists){
            $this->name = 'Редактировать тэг';
        }

        return [
            'tag' => $tag,
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
            Button::make('Создать')
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
                Input::make('tag.name')
                    ->title('Тэг')
                    ->help('Укажите название тэга'),

                Select::make('tag.type')
                    ->options([
                        'news' => 'Новости',
                        'resource' => 'Полезные ресурсы'
                    ])
                    ->title('Группа тэга')
                    ->help('Укажите к какому модулю сервиса должен относится тэг')
                    ->empty('Полезные ресурсы', 'usefullAccountingResource'),

                Input::make('tag.order_column')
                    ->title('Порядок отображения')
                    ->help('Выберите как близко в списке для выбора будет отображаться тэг'),

            ]),
        ];
    }

    /**
     * @param Tag $tag
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request, Tag $tag)
    {
        $tag::findOrCreate($request->input('tag.name'), $request->input('tag.type'));
        Alert::info('Вы успешно создали тэг!');
        return redirect()->route('platform.tags.list');
    }

    /**
     * @param Tag $tag
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Tag $tag)
    {
        $tag->delete()
            ? Alert::info('Вы успешно удалили запись о ресурсе!')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.tags.list');
    }

}
