<?php

namespace App\Orchid\Screens\TaskManager\Document;

use App\Orchid\Layouts\TaskManager\Document\DocumentTaskManagerListLayout;
use App\Document;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class DocumentTaskManagerListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список проектов';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = '';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {

        return [
            'project' => Project::query()->filters()->defaultSort('id')->paginate()
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

            Link::make('Создать новый проект')
                ->icon('pencil')
                ->route('platform.taskmanager.project.create')
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
            DocumentTaskManagerListLayout::class
        ];
    }
}
