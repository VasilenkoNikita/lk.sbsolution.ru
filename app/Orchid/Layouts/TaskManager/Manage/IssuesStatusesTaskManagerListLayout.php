<?php

namespace App\Orchid\Layouts\TaskManager\Manage;

use App\Models\IssueStatus;
use App\Models\User;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class IssuesStatusesTaskManagerListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'issuestatus';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('name', 'Название')
				->sort()
				->render(function (IssueStatus $issuestatus) {
                    return Link::make($issuestatus->name)
                        ->route('platform.taskmanager.issuesstatustaskmanager.edit', $issuestatus);
                }),
			TD::set('is_closed', '	Открыта/Закрыта')
				->sort(),
            TD::set('is_default', 'Является статусом по умолчанию')
				->sort(),
			TD::set('default_done_ratio', 'Коэффициент готовности по умолчанию')
				->sort(),

        ];
    }
}