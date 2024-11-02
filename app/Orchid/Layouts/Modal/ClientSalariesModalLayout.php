<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Modal;

use App\Models\Client;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class ClientSalariesModalLayout extends Rows
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
        $clientSalaries = $this->query->get('clientSalaries.salaries');
        $countSalaries = 9;
        if($clientSalaries){
           $countSalaries = count($clientSalaries);
        }

        return [
            Matrix::make('clientSalaries.salaries')
                ->title('Зарплаты и авансы')
                ->columns([
                    'День зарплаты' => 'payment_day',
                    'Статус зарплаты' => 'status',
                    'День аванса' => 'prepayment_day',
                    'Статус аванса' => 'prepayment_status',
                    'Месяц' => 'month',
                ])
                ->fields([
                    'payment_day' => Input::make(),
                    'status' => Select::make('status')
                        ->options([
                            'Не обработано'  => 'Не обработано',
                            'Обработано'     => 'Обработано',
                        ]),
                    'prepayment_day' => Input::make(),
                    'prepayment_status' => Select::make('prepayment_status')
                        ->options([
                            'Не обработано'  => 'Не обработано',
                            'Обработано'     => 'Обработано',
                        ]),
                    'month' => Input::make()
                        ->type('month')->disabled(),
                ])->maxRows($countSalaries),
            Input::make('clientSalaries.client_id')
                ->type('hidden'),
        ];
    }
}
