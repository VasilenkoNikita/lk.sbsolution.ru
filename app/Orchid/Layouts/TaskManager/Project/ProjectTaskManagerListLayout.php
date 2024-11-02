<?php

namespace App\Orchid\Layouts\TaskManager\Project;

use App\Models\Project;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;

use Orchid\Screen\Layouts\Table;

class ProjectTaskManagerListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'project';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('name', 'Название')
				->sort()
                ->render(function (Project $project) {
                    return Link::make($project->name)
                        ->route('platform.taskmanager.project.screen', $project);
                }),

			TD::set('identifier', 'Ключ')
				->sort(),

            TD::set('status', 'Статус')
				->sort(),

			TD::set('homepage', 'Домашняя страница')
				->sort(),

            TD::set('', '')
				->sort()
				->render(function (Project $project) {
                    return Link::make('Редактировать')
						->icon('note')
						->route('platform.taskmanager.project.edit', $project);
                })
        ];
    }
}
