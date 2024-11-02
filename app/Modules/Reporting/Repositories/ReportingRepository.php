<?php

declare(strict_types=1);

namespace App\Modules\Reporting\Repositories;

use App\Models\Client;
use App\Models\Payment;
use App\Models\Report;
use App\Models\User;
use App\Modules\Reporting\Filters\DateIntervalFilter;
use App\Modules\Reporting\Filters\GroupFilter;
use App\Modules\Reporting\Filters\ReportingEventsDisplayFilter;
use App\Modules\Reporting\Filters\ReportingFilter;
use App\Modules\Reporting\Filters\TypeOfTaxesFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReportingRepository{
    /**
     * @return Report | Payment
     */
    public function getEvents(Request $request): Collection
    {
        if ($request->request->get('reporting') === 'Отчеты'){
            return Report::select('id', 'report_name as name', 'report_date as date' , DB::raw('\'Отчет\' as type'))
                ->filtersApply([DateIntervalFilter::class, ReportingFilter::class])
                ->where('visibility', 1)
                ->orderBy('date')
                ->get();
        }elseif ($request->request->get('reporting') === 'Оплаты'){
            return Payment::select('id', 'payment_name as name', 'payment_date as date' , DB::raw('\'Оплата\' as type'))
                ->filtersApply([DateIntervalFilter::class, ReportingFilter::class])
                ->where('visibility', 1)
                ->orderBy('date')
                ->get();
        }elseif ($request->request->get('reporting') === 'Все'){
            return  Report::select('id', 'report_name as name', 'report_date as date' , DB::raw('\'Отчет\' as type'))
                ->filtersApply([DateIntervalFilter::class, ReportingFilter::class])
                ->where('visibility', 1)
                ->orderBy('date')
                ->get();
        }else{
            return  Report::select('id', 'report_name as name', 'report_date as date' , DB::raw('\'Отчет\' as type'))
                ->filtersApply([DateIntervalFilter::class, ReportingFilter::class])
                ->where('visibility', 1)
                ->orderBy('date')
                ->get();
        }
    }

    public function getClientsWithData(array $filters){
        return Client::with('typeOfTaxes', 'events', 'groups', 'payments', 'reports')
            ->filtersApply($filters)
            ->filters()
            ->orderBy('type_of_ownership', 'ASC')
            ->orderBy('organization', 'ASC')
            ->where('client_active', 1)
            ->paginate(100);
    }


}
