<?php

namespace App\Orchid\Layouts\TaskManager\Manage;

use App\Models\IssueCategory;
use App\Models\User;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class IssuesCategoriesTaskManagerListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'issuecategory';

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
				->render(function (IssueCategory $issuecategory) {
                    return Link::make($issuecategory->name)
                        ->route('platform.taskmanager.issuescategorytaskmanager.edit', $issuecategory);
                }),

			TD::set('project', 'Проект')
                ->sort(),

        ];
    }
}
