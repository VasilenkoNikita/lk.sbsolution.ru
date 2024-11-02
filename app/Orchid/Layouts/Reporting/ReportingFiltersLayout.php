<?php

namespace App\Orchid\Layouts\Reporting;

use App\Orchid\Filters\DateIntervalFilter;
use App\Orchid\Filters\GroupFilter;
use App\Orchid\Filters\ReportingEventsDisplayFilter;
use App\Orchid\Filters\ReportingFilter;
use App\Orchid\Filters\TypeOfTaxesFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class ReportingFiltersLayout extends Selection
{
    /**
     * @return Filter[]
     */


    public function filters(): array
    {
        return [
            GroupFilter::class,
            DateIntervalFilter::class,
            ReportingFilter::class,
            TypeOfTaxesFilter::class,
            ReportingEventsDisplayFilter::class,
        ];
    }
}
