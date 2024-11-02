<?php

namespace App\Modules\Reporting\Filters;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateRange;
use Orchid\Screen\Fields\Select;

class DateIntervalFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'reporting',
        'rangeDate',
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Интервал дат';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {

        if ($this->request->get('reporting') === 'Отчеты'){
            $dynamicBuilder = $builder->whereBetween('report_date', [
                    $this->request->get('rangeDate')['start'] ?? date('Y-m-01 H:i:s'),
                    $this->request->get('rangeDate')['end'] ?? date("Y-m-01 H:i:s", strtotime("+3 month"))
                ]);
        }elseif ($this->request->get('reporting') === 'Оплаты'){
            $dynamicBuilder = $builder->whereBetween('payment_date', [
                    $this->request->get('rangeDate')['start'] ?? date('Y-m-01 H:i:s'),
                    $this->request->get('rangeDate')['end'] ?? date("Y-m-01 H:i:s", strtotime("+3 month"))
                ]);
        }elseif ($this->request->get('reporting') === 'Все'){

            $dynamicBuilder = $builder->whereBetween('report_date', [
                    $this->request->get('rangeDate')['start'] ?? date('Y-m-01 H:i:s'),
                    $this->request->get('rangeDate')['end'] ?? date("Y-m-01 H:i:s", strtotime("+3 month"))
                ])
                ->union(Payment::select('id', 'payment_name as name', 'payment_date as date' , DB::raw('\'Оплата\' as type'))
                ->whereBetween('payment_date', [
                    $this->request->get('rangeDate')['start'] ?? date('Y-m-01 H:i:s'),
                    $this->request->get('rangeDate')['end'] ?? date("Y-m-01 H:i:s", strtotime("+3 month"))
                ]));
        }else{
            $dynamicBuilder = $builder->whereBetween('report_date', [
                $this->request->get('rangeDate')['start'] ?? date('Y-m-01 H:i:s'),
                $this->request->get('rangeDate')['end'] ?? date("Y-m-01 H:i:s", strtotime("+3 month"))
            ]);
        }

        return $dynamicBuilder;
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            DateRange::make('rangeDate')
                ->title('Укажите диапазон даты')
                ->value(["start" => $this->request->get('rangeDate')['start'], "end" => $this->request->get('rangeDate')['end']])
                ->class('col-md-6'),
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->name().': '.$this->request->get('rangeDate')['start'].' - '.$this->request->get('rangeDate')['end'];
    }
}
