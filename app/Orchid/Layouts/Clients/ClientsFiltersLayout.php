<?php

namespace App\Orchid\Layouts\Clients;

use App\Orchid\Filters\ClientActivityFilter;
use App\Orchid\Filters\ColorFilter;
use App\Orchid\Filters\GroupFilter;
use App\Orchid\Filters\NumberOfEmployeesFilter;
use App\Orchid\Filters\TypeOfOwnershipFilter;
use App\Orchid\Filters\TypeOfTaxesFilter;
use Illuminate\Support\Facades\Auth;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class ClientsFiltersLayout extends Selection
{
    /**
     * @return Filter[]
     */

    public function filters(): array
    {
        $filters[] = '';
        if(Auth::user()->name === 'natalia.s' || Auth::user()->name === 'anastasia.e' || Auth::user()->name === 'admin') {
            $this->choiceGroups = ClientsFiltersLayout::class;
            $filters = [GroupFilter::class,
                ClientActivityFilter::class,
                TypeOfTaxesFilter::class,
                TypeOfOwnershipFilter::class,
                NumberOfEmployeesFilter::class,
                ColorFilter::class];
        }else{
            $filters = [
                GroupFilter::class,
                ClientActivityFilter::class,
                TypeOfTaxesFilter::class,
                TypeOfOwnershipFilter::class,
                NumberOfEmployeesFilter::class];
        }


        return $filters;
    }
}
