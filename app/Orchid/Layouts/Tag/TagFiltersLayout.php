<?php

namespace App\Orchid\Layouts\Tag;

use App\Orchid\Filters\TagFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class TagFiltersLayout extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): array
    {
        return [
            TagFilter::class,
        ];
    }
}
