<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Modal;

use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class ReportingModalLayout extends Rows
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

        $eventinfo = $this->query->get('event_fields');
        if($eventinfo){
            if($eventinfo['event_type'] === "Отчет"){
                $selectStatus = Select::make('event_fields.status')
                    ->options([
                        'Отправлено'  => 'Отправлено',
                        'Отказ'  => 'Отказ',
                        'Сдано'  => 'Сдано',
                    ]);
            }else{
                $selectStatus = Select::make('event_fields.status')
                    ->options([
                        'Отправлено'  => 'Отправлено',
                        'Оплачено'  => 'Оплачено',
                    ]);
            }
        }else{
            $selectStatus = Input::make('event_fields.test')
                ->type('hidden');
        }



        return [

            Input::make('event_fields.client_organization')
                ->readonly()
                ->max(255),

             Input::make('event_fields.event_name')
                ->readonly()
                ->max(255),

            Input::make('event_fields.event_type')
                ->readonly()
                ->max(255),

            Input::make('event_fields.report_date')
                ->readonly()
                ->max(255),

            $selectStatus,

            TextArea::make('event_fields.event_action')
                ->title('Запись к событию')
                ->rows(6)
                ->maxlength(2500),

            Input::make('event_fields.client_id')
                ->type('hidden'),

            Input::make('event_fields.event_id')
                ->type('hidden'),

        ];
    }
}
