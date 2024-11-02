<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\TaskManager\Message;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class MessageTaskManagerEditLayout extends Rows
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
            Input::make('messages.subject')
                ->type('text')
                ->required()
                ->title(__('Тема')),

            Input::make('messages.content')
                ->type('email')
                ->required()
                ->title(__('Сообщение')),

        ];
    }
}
