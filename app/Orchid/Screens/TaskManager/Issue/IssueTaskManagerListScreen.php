<?php

namespace App\Orchid\Screens\TaskManager\Issue;

use App\Orchid\Layouts\TaskManager\Issue\IssueTaskManagerListLayout;
use App\Models\Project;
use App\Models\Issue;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class IssueTaskManagerListScreen extends Screen
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
     * Display projectid.
     *
     * @var string
     */
    public $projectId = '';

    /**
     * Query data.
     *
     * @param Project $project
     * @return array
     */
    public function query(Project $project): array
    {
        $this->projectId = $project->id;

        return [
            'issue' => Issue::with('category','status','assignedUser','priority')->filters()->defaultSort('id')->paginate()
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
            Link::make('Создать новую задачу')
                ->icon('pencil')
                ->route('platform.taskmanager.issue.create', $this->projectId),

            Link::make('Обсуждение')
                ->icon('settings')
                ->route('platform.taskmanager.board.list', $this->projectId),

            Link::make('Участники')
                ->icon('settings')
                ->route('platform.taskmanager.member.list', $this->projectId),

            Link::make('Задачи')
                ->icon('settings')
                ->route('platform.taskmanager.issue.list', $this->projectId),

            Link::make('Версии проекта')
                ->icon('settings')
                ->route('platform.taskmanager.member.list', $this->projectId),
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
            IssueTaskManagerListLayout::class
        ];
    }
}
