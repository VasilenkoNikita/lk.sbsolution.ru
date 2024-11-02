<?php

namespace App\Orchid\Layouts\TaskManager\Issue;

use App\Models\Project;
use App\Models\Issue;
use App\Models\User;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;

class IssueTaskManagerListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'issue';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('category.name', 'Категория')
				->sort(),

            TD::set('status.name', 'Статус')
				->sort(),

            TD::set('priority.name', 'Приоритет')
                ->sort(),

			TD::set('subject', 'Тема')
				->sort(),

            TD::set('assignedUser.name', 'Исполнитель')
                ->render(function ($issue) {
                    return $issue->assignedUser->first_name . ' ' . $issue->assignedUser->last_name;
                })
                ->sort(),

            TD::set('created_on', 'Время создания')
                ->render(function ($issue) {
                    return date_format($issue->created_on, 'Y-m-d H:i:s');
                })
                ->sort(),

            TD::set('', '')
				->sort()
				->render(function (Issue $issue) {
                    return Link::make('Редактировать')
						->icon('note')
						->route('platform.taskmanager.issue.edit', [$issue->project_id, $issue->id]);
                })


        ];
    }
}
