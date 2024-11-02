<?php

namespace App\Orchid\Screens\TaskManager\Manage;

use App\Orchid\Layouts\TaskManager\Manage\IssuesEnumerationsTaskManagerListLayout;
use App\Models\Enumeration;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class IssuesEnumerationsTaskManagerListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список категорий';

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
            'Enumeration' => Enumeration::query()->filters()->defaultSort('id')->paginate()
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

            Link::make('Создать новую категорию')
                ->icon('pencil')
                ->route('platform.taskmanager.issuescategorytaskmanager.edit')
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
            IssuesEnumerationsTaskManagerListLayout::class
        ];
    }
}
