<?php

namespace App\Orchid\Screens\TaskManager\Manage;

use App\Orchid\Layouts\TaskManager\Manage\IssuesStatusesTaskManagerListLayout;
use App\Models\IssueStatus;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class IssuesStatusTaskManagerListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список статусов';

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
            'issuestatus' => IssueStatus::query()->filters()->defaultSort('id')->paginate()
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
				
            Link::make('Создать новый статус')
                ->icon('pencil')
                ->route('platform.taskmanager.issuesstatustaskmanager.edit')
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
            IssuesStatusesTaskManagerListLayout::class
        ];
    }
}