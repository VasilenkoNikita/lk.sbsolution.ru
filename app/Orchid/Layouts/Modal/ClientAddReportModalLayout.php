<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Modal;

use App\Models\Report;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class ClientAddReportModalLayout extends Rows
{
    /**
     * Views.
     *
     * @return array
     */
    public function fields(): array
    {

        return [
            Matrix::make('newClientReports')
                ->title('Список новых отчетов')
                ->columns([
                    'Наименование отчета' => 'report_name',
                ])
                ->fields([
                    'report_name' =>  Select::make('')
                        ->fromQuery(Report::where('active', 1), 'report_name'),
                ]),
        ];
    }
}
