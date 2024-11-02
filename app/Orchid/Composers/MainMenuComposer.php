<?php

declare(strict_types=1);

namespace App\Orchid\Composers;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\Menu;

class MainMenuComposer
{
    /**
     * @var Dashboard
     */
    private $dashboard;

    /**
     * MenuComposer constructor.
     *
     * @param Dashboard $dashboard
     */
    public function __construct(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    /**
     * Registering the main menu items.
     */
    public function compose()
    {
        // Profile
        $this->dashboard->menu
            ->add(Menu::PROFILE,
                ItemMenu::label('Уведомления')
                    ->icon('compass')
            )
            ->add(Menu::PROFILE,
                ItemMenu::label('Напоминания')
                    ->icon('heart')
					->badge(function () {
                        return 6;
                    })
            );

        // Email
		$this->dashboard->menu
			->add(Menu::MAIN,
				ItemMenu::label('Email рассылка')
                    ->permission('platform.emails')
					->icon('envelope-letter')
					->route('platform.email')
					->title('Инструменты')
			)
			->add(Menu::MAIN,
				ItemMenu::label('Все новости')
                    ->permission('platform.news')
                    ->icon('book-open')
					->route('platform.posts.list')
            )
			->add(Menu::MAIN,
				ItemMenu::label('Список клиентов')
                    ->permission('platform.clients')
                    ->icon('user')
					->route('platform.clients.list')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Отчетность')
                    ->permission('platform.clients')
                    ->icon('paste')
                    ->route('platform.reporting.list')
            )
			->add(Menu::MAIN,
				ItemMenu::label('Список групп')
                    ->permission('platform.groups')
                    ->icon('people')
					->route('platform.groups.list')
            );

        // Monitoring
        $this->dashboard->menu
            ->add(Menu::MAIN,
                ItemMenu::label('История пользователей')
                    ->permission('platform.monitoring')
                    ->icon('history')
                    ->route('platform.history.list')
                    ->title('Мониторинг')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Активные пользователи')
                    ->permission('platform.monitoring')
                    ->icon('people')
                    ->route('platform.session.list')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Статистика пользователей')
                    ->permission('platform.monitoring')
                    ->icon('chart')
                    ->route('platform.userstatistics.list')
            );

		// TaskManager
		$this->dashboard->menu
			->add(Menu::MAIN,
				ItemMenu::label('Вход')
                    ->permission('platform.task-manager')
					->icon('monitor')
					->url('https://crm.sbsolution.ru/')
                    ->target('_blank')
					->title('Task-Manager')
            );


        // Documents
        $this->dashboard->menu
            ->add(Menu::MAIN,
                ItemMenu::label('Отчеты')
                    ->icon('event')
                    ->route('platform.reports.list')
                    ->title('Документы')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Оплаты')
                    ->icon('note')
                    ->route('platform.payments.list')
            )
			->add(Menu::MAIN,
                ItemMenu::label('Тарифы')
                    ->icon('calculator')
                    ->route('platform.rates.list')
            )
            ->add(
			Menu::MAIN,
                ItemMenu::label('Тэги')
                    ->icon('tag')
                    ->route('platform.tags.list')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Полезные ресурсы')
                    ->icon('browser')
                    ->route('platform.usefulAccountingResources.list')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('ОКВЭД')
                    ->icon('list')
                    ->route('platform.economicActivities.list')
            );

        // Documents
        $this->dashboard->menu
            ->add(Menu::MAIN,
                ItemMenu::label('Руководства')
                    ->permission('platform.manuals-edit')
                    ->icon('help')
                    ->route('platform.manuals.list')
                    ->title('Руководства')
            );

    }
}
