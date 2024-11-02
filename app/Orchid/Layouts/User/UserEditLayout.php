<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class UserEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('user.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Name')),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title(__('Email'))
                ->placeholder(__('Email')),

            Input::make('user.first_name')
                ->type('text')
                ->max(255)
                ->title('Имя')
                ->placeholder('Имя сотрудника'),

            Input::make('user.last_name')
                ->type('text')
                ->max(255)
                ->title('Фамилия')
                ->placeholder('Фамилия сотрудника'),

            Cropper::make('user.profile_photo_url')
                ->targetRelativeUrl(),
        ];
    }
}
