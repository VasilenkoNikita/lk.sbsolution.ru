<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Clients;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class ClientEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('client.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Name')),
        ];
    }
}
