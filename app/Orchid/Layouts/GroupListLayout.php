<?php

declare(strict_types=1);

namespace App\Orchid\Layouts;

use App\Models\Group;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class GroupListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'groups';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('name', 'Название группы')
				->sort()
				->filter(TD::FILTER_TEXT)
                ->render(function (Group $group) {
                    return Link::make($group->name)
                        ->route('platform.groups.edit', $group);
                }),

        ];
    }
}
