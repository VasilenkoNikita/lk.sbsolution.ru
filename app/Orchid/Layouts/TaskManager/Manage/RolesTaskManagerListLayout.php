<?php

namespace App\Orchid\Layouts\TaskManager\Manage;

use App\Models\RolesProjects;
use App\Models\User;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class RolesTaskManagerListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'roles';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('name', 'Название роли')
				->sort()
				->render(function (RolesProjects $rolesprojects) {
                    return Link::make($rolesprojects->name)
                        ->route('platform.taskmanager.roletaskmanager.edit', $rolesprojects);
                }),
			TD::set('assignable', 'Назначаемый')
				->sort(),
            TD::set('permissions', 'Разрешения')
				->sort(),

        ];
    }
}