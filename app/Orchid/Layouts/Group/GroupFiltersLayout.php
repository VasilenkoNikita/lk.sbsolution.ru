<?php

namespace App\Orchid\Layouts\Group;

use App\Orchid\Filters\GroupFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class GroupFiltersLayout extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): array
    {
        return [
            GroupFilter::class,
        ];
    }
}
