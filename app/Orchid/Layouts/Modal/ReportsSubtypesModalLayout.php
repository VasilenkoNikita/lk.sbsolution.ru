<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Modal;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Layouts\Rows;

class ReportsSubtypesModalLayout extends Rows
{
    /**
     * Views.
     *
     * @throws \Throwable|\Orchid\Screen\Exceptions\TypeException
     *
     * @return array
     */
    public function fields(): array
    {

        return [
            Matrix::make('reportsSubtypes')
                ->title('Типы отчетов')
                ->columns([
                    'Наименование типа' => 'name',
                ])
                ->fields([
                    'name' => Input::make(),
                ]),
        ];
    }
}
