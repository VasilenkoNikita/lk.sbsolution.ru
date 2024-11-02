<?php

namespace App\Orchid\Layouts\TaskManager\Manage;

use App\Models\Enumeration;
use App\Models\User;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class IssuesEnumerationsTaskManagerListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'enumeration';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('id', 'ID')
                ->sort(),

            TD::set('name', 'Название категории')
				->sort()
				->render(function (Enumeration $enumeration) {
                    return Link::make($enumeration->name)
                        ->route('platform.taskmanager.issuescategorytaskmanager.edit', $enumeration);
                }),

			TD::set('project', 'Проект')
                ->sort(),

        ];
    }
}
