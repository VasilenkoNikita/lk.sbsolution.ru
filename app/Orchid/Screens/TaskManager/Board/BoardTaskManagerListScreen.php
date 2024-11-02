<?php

namespace App\Orchid\Screens\TaskManager\Board;

use App\Orchid\Layouts\TaskManager\Board\BoardTaskManagerListLayout;
use App\Models\Project;
use App\Models\Board;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class BoardTaskManagerListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список досок';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = '';

    /**
     * Query data.
     * @param Project $project
     * @param Board $board
     * @return array
     */
    public function query(Project $project, Board $board): array
    {

        $this->projectId = $project->id;

        return [
            'board' => Board::query()->filters()->defaultSort('id')->paginate()
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
            Link::make('Создать доску')
                ->icon('settings')
                ->route('platform.taskmanager.board.create', $this->projectId),

            Link::make('Доски')
                ->icon('settings')
                ->route('platform.taskmanager.board.create', $this->projectId),

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
            BoardTaskManagerListLayout::class
        ];
    }
}
