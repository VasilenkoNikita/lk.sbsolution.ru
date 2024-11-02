<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Layouts\Rows;

class UserNotificationLayout extends Rows
{
    /**
     * Views.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Switcher::make('user.lk_client_notification')
                ->sendTrueOrFalse()
                ->title('Уведомления в ЛК')
                ->help('Переключите активность уведомлений в ЛК'),

            Switcher::make('user.lk_client_mail_notification')
                ->sendTrueOrFalse()
                ->title('Уведомления на почту')
                ->help('Переключите активность уведомлений на почту'),

            Group::make([
                CheckBox::make('notifications_modules.patent')
                    ->title('Патенты')
                    ->sendTrueOrFalse(),
                CheckBox::make('notifications_modules.salaries')
                    ->title('Зарплаты и авансы')
                    ->sendTrueOrFalse(),
                CheckBox::make('notifications_modules.reports')
                    ->title('Отчеты и оплаты')
                    ->sendTrueOrFalse(),
                CheckBox::make('notifications_modules.certificates')
                    ->title('Сертификаты')
                    ->sendTrueOrFalse(),
            ]),
        ];
    }
}
