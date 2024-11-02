<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Orchid\Composers\MainMenuComposer;
use App\Orchid\Composers\SystemMenuComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;

class PlatformProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard)
    {


        View::composer('platform::dashboard', MainMenuComposer::class);
        View::composer('platform::systems', SystemMenuComposer::class);

            try {
                $dashboard->registerResource('scripts', mix('/js/dashboard.js'));
            } catch (\Throwable $th) {

            }
            try {
                $dashboard->registerResource('stylesheets', mix('/css/appsbs.css'));
            } catch (\Throwable $th) {

            }
        $dashboard->registerPermissions($this->registerPermissionsSystems());
        $dashboard->registerPermissions($this->registerPermissionsMain());
        $dashboard->registerSearch([
            //...Models
        ]);
    }

    /**
     * @return ItemPermission
     */
    protected function registerPermissionsSystems(): ItemPermission
    {
        return ItemPermission::group(__('Systems'))
            ->addPermission('platform.systems.roles', __('Roles'))
            ->addPermission('platform.systems.users', __('Users'));

    }

    protected function registerPermissionsMain(): ItemPermission
    {
        return ItemPermission::group(__('Модули'))
            ->addPermission('platform.clients', 'Клиенты')
            ->addPermission('platform.groups', 'Группы')
            ->addPermission('platform.task-manager', 'Task-manager')
            ->addPermission('platform.news', 'Новости')
            ->addPermission('platform.emails', 'Email рассылка')
            ->addPermission('platform.monitoring', 'Мониторинг пользователей')
            ->addPermission('platform.documents', 'Документы')
            ->addPermission('platform.manuals-edit', 'Создание руководств')
            ->addPermission('platform.manuals-view', 'Просмотр руководств');
    }
}
