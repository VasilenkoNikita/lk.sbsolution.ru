<?php

declare(strict_types=1);

namespace App\Orchid\Layouts;

use App\Models\Report;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class ReportsListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'reports';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('report_name', 'Наименование отчета')
				->sort()
				->filter(TD::FILTER_TEXT)
                ->render(function (Report $report) {
                    return Link::make($report->report_name)
                        ->route('platform.reports.edit', $report);
                }),

            TD::make('reportsTypes.name', 'Тип отчета')
                ->render(function (Report $report) {
                    if(!$report->reportsTypes->isEmpty()) {
                        return $report->reportsTypes[0]->name;
                    }
                    return "Не указано";
                }),

            TD::make('reportsSubtypes.name', 'Вид отчета')
                ->render(function (Report $report) {
                    if(!$report->reportsSubtypes->isEmpty()) {
                        return $report->reportsSubtypes[0]->name;
                    }
                    return "Не указано";
                }),

            TD::make('visibility', 'Видимость')
                ->align('center')
                ->render(function (Report $report) {
                    if($report->visibility === 1){
                        return '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="green" width="16" height="16" viewBox="0 0 32 32">
                                <path d="M16 0c-8.836 0-16 7.163-16 16s7.163 16 16 16c8.837 0 16-7.163 16-16s-7.163-16-16-16zM16 30.032c-7.72 0-14-6.312-14-14.032s6.28-14 14-14 14 6.28 14 14-6.28 14.032-14 14.032zM22.386 10.146l-9.388 9.446-4.228-4.227c-0.39-0.39-1.024-0.39-1.415 0s-0.391 1.023 0 1.414l4.95 4.95c0.39 0.39 1.024 0.39 1.415 0 0.045-0.045 0.084-0.094 0.119-0.145l9.962-10.024c0.39-0.39 0.39-1.024 0-1.415s-1.024-0.39-1.415 0z"></path>
                                </svg>';
                    }
                    return '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="red" width="16" height="16" viewBox="0 0 32 32">
                                <path d="M16 0c-8.836 0-16 7.163-16 16s7.163 16 16 16c8.837 0 16-7.163 16-16s-7.163-16-16-16zM16 30.032c-7.72 0-14-6.312-14-14.032s6.28-14 14-14 14 6.28 14 14-6.28 14.032-14 14.032zM21.657 10.344c-0.39-0.39-1.023-0.39-1.414 0l-4.242 4.242-4.242-4.242c-0.39-0.39-1.024-0.39-1.415 0s-0.39 1.024 0 1.414l4.242 4.242-4.242 4.242c-0.39 0.39-0.39 1.024 0 1.414s1.024 0.39 1.415 0l4.242-4.242 4.242 4.242c0.39 0.39 1.023 0.39 1.414 0s0.39-1.024 0-1.414l-4.242-4.242 4.242-4.242c0.391-0.391 0.391-1.024 0-1.414z"></path>
                            </svg>';
                }),

            TD::make('type_of_ownership', 'Форма собственности')
                ->render(function (Report $report) {
                    if(!is_null($report->type_of_ownership)) {
                        return $report->type_of_ownership;
                    }
                    return "Не указано";
                }),

            TD::make('active', 'Активность')
                ->align('center')
                ->render(function (Report $report) {
                    if($report->active === 1){
                        return '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="green" width="16" height="16" viewBox="0 0 32 32">
                                <path d="M16 0c-8.836 0-16 7.163-16 16s7.163 16 16 16c8.837 0 16-7.163 16-16s-7.163-16-16-16zM16 30.032c-7.72 0-14-6.312-14-14.032s6.28-14 14-14 14 6.28 14 14-6.28 14.032-14 14.032zM22.386 10.146l-9.388 9.446-4.228-4.227c-0.39-0.39-1.024-0.39-1.415 0s-0.391 1.023 0 1.414l4.95 4.95c0.39 0.39 1.024 0.39 1.415 0 0.045-0.045 0.084-0.094 0.119-0.145l9.962-10.024c0.39-0.39 0.39-1.024 0-1.415s-1.024-0.39-1.415 0z"></path>
                                </svg>';
                    }
                    return '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="red" width="16" height="16" viewBox="0 0 32 32">
                                <path d="M16 0c-8.836 0-16 7.163-16 16s7.163 16 16 16c8.837 0 16-7.163 16-16s-7.163-16-16-16zM16 30.032c-7.72 0-14-6.312-14-14.032s6.28-14 14-14 14 6.28 14 14-6.28 14.032-14 14.032zM21.657 10.344c-0.39-0.39-1.023-0.39-1.414 0l-4.242 4.242-4.242-4.242c-0.39-0.39-1.024-0.39-1.415 0s-0.39 1.024 0 1.414l4.242 4.242-4.242 4.242c-0.39 0.39-0.39 1.024 0 1.414s1.024 0.39 1.415 0l4.242-4.242 4.242 4.242c0.39 0.39 1.023 0.39 1.414 0s0.39-1.024 0-1.414l-4.242-4.242 4.242-4.242c0.391-0.391 0.391-1.024 0-1.414z"></path>
                            </svg>';
                }),

            TD::make('report_date', 'Время сдачи отчета')
                ->render(function (Report $report) {
                    return date( "d.m.Y", strtotime($report->report_date) );
                })
                ->sort(),




        ];
    }
}
