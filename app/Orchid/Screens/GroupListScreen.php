<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Orchid\Layouts\GroupListLayout;
use App\Models\Group;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class GroupListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список групп';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Все группы';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'groups' => Group::with('clients')->filters()->defaultSort('id')->paginate()
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
            Link::make('Создать новую группу')
                ->icon('pencil')
                ->route('platform.groups.create')
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
            GroupListLayout::class
        ];
    }
}
