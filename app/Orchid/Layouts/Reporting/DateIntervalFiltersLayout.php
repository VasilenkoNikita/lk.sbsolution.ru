<?php

namespace App\Orchid\Layouts\Reporting;

use App\Orchid\Filters\DateIntervalFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class DateIntervalFiltersLayout extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): array
    {
        return [
            DateIntervalFilter::class,
        ];
    }
}
