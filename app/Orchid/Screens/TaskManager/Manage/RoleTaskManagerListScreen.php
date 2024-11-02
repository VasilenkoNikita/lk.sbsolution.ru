<?php

namespace App\Orchid\Screens\TaskManager\Manage;

use App\Orchid\Layouts\TaskManager\Manage\RolesTaskManagerListLayout;
use App\Models\RolesProjects;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class RoleTaskManagerListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список ролей';

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
            'roles' => RolesProjects::query()->filters()->defaultSort('id')->paginate()
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
				
            Link::make('Создать новую роль')
                ->icon('pencil')
                ->route('platform.taskmanager.roletaskmanager.edit')
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
            RolesTaskManagerListLayout::class
        ];
    }
}