<?php

declare(strict_types=1);

use App\Models\Manual;
use App\Models\Tag;
use App\Models\Client;
use App\Models\Group;
use App\Models\Report;
use App\Models\Rate;
use App\Models\Payment;
use App\Models\UsefulAccountingResource;
use App\Models\Post;
use App\Models\EconomicActivities;
use Orchid\Platform\Models\Role;
use Orchid\Platform\Models\User;
use Tabuna\Breadcrumbs\Breadcrumbs;
use Tabuna\Breadcrumbs\Trail;

// System
Breadcrumbs::for('platform.systems.index', fn(Trail $trail) => $trail
    ->push('Система', route('platform.systems.index'))
);

// Platform
Breadcrumbs::for('platform.index', function ($trail) {
    $trail->push('Главная');
});

// Platform -> Email
Breadcrumbs::for('platform.email', fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push('Email рассылка')
);

// Platform -> History
Breadcrumbs::for('platform.history', fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push('История действия пользователей')
);

// Platform > System > Users
Breadcrumbs::for('platform.systems.users', fn(Trail $trail) => $trail
    ->parent('platform.systems.index')
    ->push('Пользователи', route('platform.systems.users'))
);

// Platform > System > Users > User
Breadcrumbs::for('platform.systems.users.edit', fn(Trail $trail, User $user) => $trail
    ->parent('platform.systems.users')
    ->push(__($user->name), route('platform.systems.users.edit', $user->id))
);

// Platform > System > Roles
Breadcrumbs::for('platform.systems.roles', fn(Trail $trail) => $trail
    ->parent('platform.systems.index')
    ->push('Роли', route('platform.systems.roles'))
);

// Platform > System > Roles > Create
Breadcrumbs::for('platform.systems.roles.create', fn(Trail $trail) => $trail
    ->parent('platform.systems.roles')
    ->push(('Создание роли'), route('platform.systems.roles.create'))
);

// Platform > System > Roles > Role
Breadcrumbs::for('platform.systems.roles.edit', fn(Trail $trail, Role $role) => $trail
    ->parent('platform.systems.roles')
    ->push(__($role->name), route('platform.systems.roles.edit', $role->id))
);

// Platform -> TaskManager -> Settings
Breadcrumbs::for('platform.taskmanager.settings', function ($trail) {
    $trail->parent('platform.index');
    $trail->push('Настройки Task-Manager');
});

// Platform -> TaskManager -> Settings -> Roles
Breadcrumbs::for('platform.taskmanager.roletaskmanager.list', function ($trail) {
    $trail->parent('platform.taskmanager.settings');
    $trail->push('Роли Task-Manager');
});

// Platform -> TaskManager -> Settings -> IssueStatuses
Breadcrumbs::for('platform.taskmanager.issuesstatustaskmanager.list', function ($trail) {
    $trail->parent('platform.taskmanager.settings');
    $trail->push('Статусы задач Task-Manager');
});

// Platform -> TaskManager -> Projects
Breadcrumbs::for('platform.taskmanager.project.list', function ($trail) {
    $trail->parent('platform.index');
    $trail->push('Проекты Task-Manager');
});

// Platform -> Tags
Breadcrumbs::for('platform.tags.list', fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push('Список тэгов', route('platform.tags.list'))
);

//Platform -> Tag -> Create
Breadcrumbs::for('platform.tags.create', fn(Trail $trail) => $trail
    ->parent('platform.tags.list')
    ->push(('Создание тэга'), route('platform.tags.create'))
);

//Platform -> Tags -> Tag
Breadcrumbs::for('platform.tags.edit', fn(Trail $trail, Tag $tag) => $trail
    ->parent('platform.tags.list')
    ->push('Редактирование тэга: '.$tag->name, route('platform.tags.edit', $tag->id))
);

//Platform -> Clients -> Reporting -> Client
Breadcrumbs::for('platform.reporting.list', fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push('Отчетность', route('platform.reporting.list'))
);

// Platform -> Clients
Breadcrumbs::for('platform.clients.list', fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push('Список клиентов', route('platform.clients.list'))
);

//Platform -> Clients -> Create
Breadcrumbs::for('platform.clients.create', fn(Trail $trail) => $trail
    ->parent('platform.clients.list')
    ->push(('Создание клиента'), route('platform.clients.create'))
);

//Platform -> Clients -> Client
Breadcrumbs::for('platform.clients.edit', fn(Trail $trail, Client $client) => $trail
    ->parent('platform.clients.list')
    ->push('Редактирование клиента: '.$client->organization, route('platform.clients.edit', $client->id))
);

//Platform -> Manuals
Breadcrumbs::for('platform.manuals.list', fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push('Список руководств', route('platform.manuals.list'))
);

//Platform -> Manuals -> Create
Breadcrumbs::for('platform.manuals.create', fn(Trail $trail) => $trail
    ->parent('platform.manuals.list')
    ->push(('Создание руководства'), route('platform.manuals.create'))
);

//Platform -> Manuals -> Manual
Breadcrumbs::for('platform.manuals.edit', fn(Trail $trail, Manual $manual) => $trail
    ->parent('platform.manuals.list')
    ->push('Редактирование руководства: '.$manual->section, route('platform.manuals.edit', $manual->id))
);

// Platform -> Groups
Breadcrumbs::for('platform.groups.list', fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push('Список групп', route('platform.groups.list'))
);

//Platform -> Groups -> Create
Breadcrumbs::for('platform.groups.create', fn(Trail $trail) => $trail
    ->parent('platform.groups.list')
    ->push(('Создание группы'), route('platform.groups.create'))
);

//Platform -> Groups -> Group
Breadcrumbs::for('platform.groups.edit', fn(Trail $trail, Group $group) => $trail
    ->parent('platform.groups.list')
    ->push('Редактирование группы: '.$group->name, route('platform.groups.edit', $group->id))
);

// Platform -> Rates
Breadcrumbs::for('platform.rates.list', fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push('Список тарифов', route('platform.rates.list'))
);

//Platform -> Rates -> Create
Breadcrumbs::for('platform.rates.create', fn(Trail $trail) => $trail
    ->parent('platform.rates.list')
    ->push(('Создание тарифа'), route('platform.rates.create'))
);

//Platform -> Rates -> Rate
Breadcrumbs::for('platform.rates.edit', fn(Trail $trail, Rate $rate) => $trail
    ->parent('platform.rates.list')
    ->push('Редактирование тарифа: '.$rate->name, route('platform.rates.edit', $rate->id))
);


// Platform -> Reports
Breadcrumbs::for('platform.reports.list', fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push('Список отчетов', route('platform.reports.list'))
);

//Platform -> Reports -> Create
Breadcrumbs::for('platform.reports.create', fn(Trail $trail) => $trail
    ->parent('platform.reports.list')
    ->push(('Создание отчета'), route('platform.reports.create'))
);

//Platform -> Reports -> Report
Breadcrumbs::for('platform.reports.edit', fn(Trail $trail, Report $report) => $trail
    ->parent('platform.reports.list')
    ->push('Редактирование отчета: '.$report->report_name, route('platform.reports.edit', $report->id))
);


// Platform -> Payments
Breadcrumbs::for('platform.payments.list', fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push('Список оплат', route('platform.payments.list'))
);

//Platform -> Payments -> Create
Breadcrumbs::for('platform.payments.create', fn(Trail $trail) => $trail
    ->parent('platform.payments.list')
    ->push(('Создание оплаты'), route('platform.payments.create'))
);

//Platform -> Payments -> Payment
Breadcrumbs::for('platform.payments.edit', fn(Trail $trail, Payment $payment) => $trail
    ->parent('platform.payments.list')
    ->push('Редактирование оплаты: '.$payment->payment_name, route('platform.payments.edit', $payment->id))
);

// Platform -> UsefulAccountingResources
Breadcrumbs::for('platform.usefulAccountingResources.list', fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push('Список ресурсов', route('platform.usefulAccountingResources.list'))
);

//Platform -> UsefulAccountingResources -> Create
Breadcrumbs::for('platform.usefulAccountingResources.create', fn(Trail $trail) => $trail
    ->parent('platform.usefulAccountingResources.list')
    ->push(('Создание ресурса'), route('platform.usefulAccountingResources.create'))
);

//Platform -> UsefulAccountingResources -> UsefulAccountingResource
Breadcrumbs::for('platform.usefulAccountingResources.edit', fn(Trail $trail, UsefulAccountingResource $usefulAccountingResource) => $trail
    ->parent('platform.usefulAccountingResources.list')
    ->push('Редактирование ресурса: '.$usefulAccountingResource->resource_name, route('platform.usefulAccountingResources.edit', $usefulAccountingResource->id))
);

// Platform -> Posts
Breadcrumbs::for('platform.posts.list', fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push('Список новостей', route('platform.posts.list'))
);

//Platform -> Posts -> Create
Breadcrumbs::for('platform.posts.create', fn(Trail $trail) => $trail
    ->parent('platform.posts.list')
    ->push(('Создание новости'), route('platform.posts.create'))
);

//Platform -> Posts -> Post
Breadcrumbs::for('platform.posts.edit', fn(Trail $trail, Post $post) => $trail
    ->parent('platform.posts.list')
    ->push('Редактирование новости: '.$post->title, route('platform.posts.edit', $post->id))
);

// Platform -> EconomicActivities
Breadcrumbs::for('platform.economicActivities.list', fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push('Список ОКВЭД', route('platform.economicActivities.list'))
);

//Platform -> EconomicActivities -> Create
Breadcrumbs::for('platform.economicActivities.create', fn(Trail $trail) => $trail
    ->parent('platform.economicActivities.list')
    ->push(('Создание ОКВЭД'), route('platform.economicActivities.create'))
);

//Platform -> EconomicActivities -> EconomicActivity
Breadcrumbs::for('platform.economicActivities.edit', fn(Trail $trail, EconomicActivities $economicActivities) => $trail
    ->parent('platform.economicActivities.list')
    ->push('Редактирование раздела: '.$economicActivities->type_economic_activity, route('platform.economicActivities.edit', $economicActivities->id))
);
