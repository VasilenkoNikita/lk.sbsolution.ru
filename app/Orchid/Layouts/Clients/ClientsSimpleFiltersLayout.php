<?php

namespace App\Orchid\Layouts\Clients;

use App\Orchid\Filters\NumberOfEmployeesFilter;
use App\Orchid\Filters\TypeOfTaxesFilter;
use App\Orchid\Filters\TypeOfOwnershipFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class ClientsSimpleFiltersLayout extends Selection
{
    /**
     * @return Filter[]
     */

    public function filters(): array
    {
        return [
            TypeOfTaxesFilter::class,
            TypeOfOwnershipFilter::class,
            NumberOfEmployeesFilter::class,
        ];
    }
}
