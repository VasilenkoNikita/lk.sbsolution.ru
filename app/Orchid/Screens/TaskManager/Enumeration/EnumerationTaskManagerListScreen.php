<?php

namespace App\Orchid\Screens\TaskManager\Enumeration;


use App\Models\Enumeration;
use App\View\Components\EnumerationScreenTaskManager;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class EnumerationTaskManagerListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список общих свойств проектов';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = '';

    /**
     * Query data.
     *
     * @param Enumeration $enumeration
     *
     * @return array
     */
    public function query(Enumeration $enumeration): array
    {

      //  dd(Enumeration::all()->groupby('type'));
        return [
            'enumerationcats' => Enumeration::all()->groupby('type'),
            'enumeration' => Enumeration::query()->filters()->defaultSort('id')->paginate(),
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
            Link::make('Настройки task-manager')
                ->icon('settings')
                ->route('platform.taskmanager.settings'),

            Link::make('Создать новое свойство')
                ->icon('pencil')
                ->route('platform.taskmanager.enumeration.edit')
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
            Layout::component(EnumerationScreenTaskManager::class),
        ];
    }
}
