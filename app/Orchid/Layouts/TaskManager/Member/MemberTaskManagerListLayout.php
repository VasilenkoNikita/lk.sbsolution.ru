<?php

namespace App\Orchid\Layouts\TaskManager\Member;

use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;

class MemberTaskManagerListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'members';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('last_name', 'Участник')
                ->render(function ($members) {
                    return $members->first_name . ' ' . $members->last_name;
                }),

			TD::set('role', 'Роль')
                ->sort(),

            TD::set('', '')
                ->sort()
                ->render(function ($members) {
                    return Link::make('Редактировать')
                        ->icon('note')
                        ->route('platform.taskmanager.member.edit', [$members->project_id, $members->member_id]);
                })
        ];
    }
}
