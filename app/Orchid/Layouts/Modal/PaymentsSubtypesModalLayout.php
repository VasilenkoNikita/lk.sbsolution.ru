<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Modal;

use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class PaymentsSubtypesModalLayout extends Rows
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
            Matrix::make('paymentsSubtypes')
                ->title('Типы оплат')
                ->columns([
                    'Наименование типа' => 'name',
                ])
                ->fields([
                    'name' => Input::make(),
                ]),
        ];
    }
}
