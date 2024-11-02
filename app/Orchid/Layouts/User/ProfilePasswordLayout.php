<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Fields\Password;
use Orchid\Screen\Layouts\Rows;

class ProfilePasswordLayout extends Rows
{
    /**
     * Views.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Password::make('old_password')
                ->placeholder('Введите текущий пароль')
                ->title('Текущий пароль')
                ->help('Это ваш пароль, установленный на данный момент.'),

            Password::make('password')
                ->placeholder('Enter the password to be set')
                ->title('Новый пароль'),

            Password::make('password_confirmation')
                ->placeholder('Введите пароль, который нужно установить')
                ->title('Подтвердите новый пароль')
                ->help('Хороший пароль должен содержать не менее 15 символов или не менее 8 символов, включая цифру и строчную букву.'),
        ];
    }
}
