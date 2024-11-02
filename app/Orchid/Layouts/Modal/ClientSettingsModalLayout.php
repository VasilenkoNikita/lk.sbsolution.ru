<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Modal;

use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\TD;


class ClientSettingsModalLayout extends Rows
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
            Input::make('event_fields.event_name')
                ->readonly()
                ->max(255),

            Input::make('event_fields.event_type')
                ->readonly()
                ->max(255),

            Input::make('event_fields.report_date')
                ->readonly()
                ->max(255),

            TextArea::make('event_fields.event_action')
                ->title('Запись к событию')
                ->rows(6)
                ->maxlength(2500),

            Input::make('event_fields.client_id')
                ->type('hidden')


        ];
    }
}
