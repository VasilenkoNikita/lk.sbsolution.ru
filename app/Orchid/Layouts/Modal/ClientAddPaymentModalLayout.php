<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Modal;

use App\Models\Payment;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class ClientAddPaymentModalLayout extends Rows
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
            Matrix::make('newClientPayments')
                ->title('Список новых оплат')
                ->columns([
                    'Наименование оплаты' => 'payment_name',
                ])
                ->fields([
                    'payment_name' =>  Select::make('')
                        ->fromQuery(Payment::where('active', 1), 'payment_name'),
                ]),
        ];
    }
}
