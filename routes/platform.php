<?php

declare(strict_types=1);

use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\HistoryListScreen;
use App\Orchid\Screens\ManualEditScreen;
use App\Orchid\Screens\ManualListScreen;
use App\Orchid\Screens\ManualViewScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\SessionListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\EmailSenderScreen;
use App\Orchid\Screens\PostEditScreen;
use App\Orchid\Screens\PostListScreen;
use App\Orchid\Screens\ClientEditScreen;
use App\Orchid\Screens\ClientListScreen;
use App\Orchid\Screens\ReportingScreen;
use App\Orchid\Screens\GroupEditScreen;
use App\Orchid\Screens\GroupListScreen;
use App\Orchid\Screens\ReportsEditScreen;
use App\Orchid\Screens\ReportsListScreen;
use App\Orchid\Screens\RatesEditScreen;
use App\Orchid\Screens\RatesListScreen;
use App\Orchid\Screens\PaymentsEditScreen;
use App\Orchid\Screens\PaymentsListScreen;
use App\Orchid\Screens\EconomicActivitiesEditScreen;
use App\Orchid\Screens\EconomicActivitiesListScreen;
use App\Orchid\Screens\SubActivitiesListScreen;
use App\Orchid\Screens\UsefulAccountingResourceEditScreen;
use App\Orchid\Screens\UsefulAccountingResourceListScreen;
use App\Orchid\Screens\TagEditScreen;
use App\Orchid\Screens\TagListScreen;
use App\Orchid\Screens\TaskManager\Manage\SettingsScreen;
use App\Orchid\Screens\TaskManager\Manage\RoleTaskManagerListScreen;
use App\Orchid\Screens\TaskManager\Manage\RoleTaskManagerEditScreen;
use App\Orchid\Screens\TaskManager\Manage\IssuesStatusTaskManagerListScreen;
use App\Orchid\Screens\TaskManager\Manage\IssuesStatusTaskManagerEditScreen;
use App\Orchid\Screens\TaskManager\Manage\IssuesCategoryTaskManagerListScreen;
use App\Orchid\Screens\TaskManager\Manage\IssuesCategoryTaskManagerEditScreen;
use App\Orchid\Screens\TaskManager\Enumeration\EnumerationTaskManagerListScreen;
use App\Orchid\Screens\TaskManager\Enumeration\EnumerationTaskManagerEditScreen;
use App\Orchid\Screens\TaskManager\Project\ProjectTaskManagerListScreen;
use App\Orchid\Screens\TaskManager\Project\ProjectTaskManagerEditScreen;
use App\Orchid\Screens\TaskManager\Project\ProjectTaskManagerScreen;
use App\Orchid\Screens\TaskManager\Member\MemberTaskManagerListScreen;
use App\Orchid\Screens\TaskManager\Member\MemberTaskManagerEditScreen;
use App\Orchid\Screens\TaskManager\Board\BoardTaskManagerListScreen;
use App\Orchid\Screens\TaskManager\Board\BoardTaskManagerEditScreen;
use App\Orchid\Screens\TaskManager\Issue\IssueTaskManagerEditScreen;
use App\Orchid\Screens\TaskManager\Issue\IssueTaskManagerListScreen;

use App\Orchid\Screens\UserStatistics;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

/* [Main] */
Route::screen('/main', PlatformScreen::class)->name('platform.main');

/* [Users] */
// [Platform > System > Users > User]
Route::screen('system/users/{user}/edit', UserEditScreen::class)->name('platform.systems.users.edit');

// [Platform > System > Users]
Route::screen('system/users', UserListScreen::class)->name('platform.systems.users');

/* [Roles] */
Route::screen('system/roles/{role}/edit', RoleEditScreen::class)->name('platform.systems.roles.edit');
Route::screen('system/roles/create', RoleEditScreen::class)->name('platform.systems.roles.create');
Route::screen('system/roles', RoleListScreen::class)->name('platform.systems.roles');

/* [Task-manager] */
Route::middleware(['access:platform.task-manager'])->group(function () {

    /* [Task-manager -> Settings -> Roles > Role] */
    Route::screen('task-manager/settings/role/{rolesprojects?}', RoleTaskManagerEditScreen::class)->name('platform.taskmanager.roletaskmanager.edit');

    /* [Task-manager -> Settings -> Roles] */
    Route::screen('task-manager/settings/roles', RoleTaskManagerListScreen::class)->name('platform.taskmanager.roletaskmanager.list');

    /* [Task-manager -> Settings -> IssuesStatuses] */
    Route::screen('task-manager/settings/issues/status/{issuestatus?}', IssuesStatusTaskManagerEditScreen::class)->name('platform.taskmanager.issuesstatustaskmanager.edit');
    Route::screen('task-manager/settings/issues/statuses', IssuesStatusTaskManagerListScreen::class)->name('platform.taskmanager.issuesstatustaskmanager.list');

    /* [Task-manager -> Settings -> IssuesCategories] */
    Route::screen('task-manager/settings/issues/category/{issuescategory?}', IssuesCategoryTaskManagerEditScreen::class)->name('platform.taskmanager.issuescategorytaskmanager.edit');
    Route::screen('task-manager/settings/issues/categories', IssuesCategoryTaskManagerListScreen::class)->name('platform.taskmanager.issuescategorytaskmanager.list');

    /* [Task-manager -> Settings -> Enumerations] */
    Route::screen('task-manager/settings/enumeration/{enumeration?}', EnumerationTaskManagerEditScreen::class)->name('platform.taskmanager.enumeration.edit');
    Route::screen('task-manager/settings/enumerations', EnumerationTaskManagerListScreen::class)->name('platform.taskmanager.enumeration.list');

    /* [Task-manager -> Settings] */
    Route::screen('task-manager/settings', SettingsScreen::class)->name('platform.taskmanager.settings');

    /* [Task-manager -> Projects -> Issue] */
    Route::screen('task-manager/project/{project?}/issues/{member?}/edit', IssueTaskManagerEditScreen::class)->name('platform.taskmanager.issue.edit');
    Route::screen('task-manager/project/{project?}/issues/create', IssueTaskManagerEditScreen::class)->name('platform.taskmanager.issue.create');
    Route::screen('task-manager/project/{project?}/issues', IssueTaskManagerListScreen::class)->name('platform.taskmanager.issue.list');

    /* [Task-manager -> Projects -> Members] */
    Route::screen('task-manager/project/{project?}/members/{member?}/edit', MemberTaskManagerEditScreen::class)->name('platform.taskmanager.member.edit');
    Route::screen('task-manager/project/{project?}/member/create', MemberTaskManagerEditScreen::class)->name('platform.taskmanager.member.create');
    Route::screen('task-manager/projects/{project?}/members', MemberTaskManagerListScreen::class)->name('platform.taskmanager.member.list');

    /* [Task-manager -> Projects -> Boards] */
    Route::screen('task-manager/project/{project?}/board/{board?}/edit', BoardTaskManagerEditScreen::class)->name('platform.taskmanager.board.edit');
    Route::screen('task-manager/project/{project?}/board/create', BoardTaskManagerEditScreen::class)->name('platform.taskmanager.board.create');
    Route::screen('task-manager/projects/{project?}/boards', BoardTaskManagerListScreen::class)->name('platform.taskmanager.board.list');

    /* [Task-manager -> Projects] */
    Route::screen('task-manager/project/{project?}/edit', ProjectTaskManagerEditScreen::class)->name('platform.taskmanager.project.edit');
    Route::screen('task-manager/project/create', ProjectTaskManagerEditScreen::class)->name('platform.taskmanager.project.create');
    Route::screen('task-manager/projects/{project}/board', ProjectTaskManagerScreen::class)->name('platform.taskmanager.project.screen');
    Route::screen('task-manager/projects', ProjectTaskManagerListScreen::class)->name('platform.taskmanager.project.list');
});

/* [Email] */
Route::middleware(['access:platform.emails'])->group(function () {
    Route::screen('email', EmailSenderScreen::class)->name('platform.email');
});

/* [Monitoring] */
Route::middleware(['access:platform.monitoring'])->group(function () {
    Route::screen('history', HistoryListScreen::class)->name('platform.history.list');
    Route::screen('session', SessionListScreen::class)->name('platform.session.list');
    Route::screen('user-statistics', UserStatistics::class)->name('platform.userstatistics.list');
});

/* [News] */
Route::middleware(['access:platform.news'])->group(function () {
    Route::screen('posts/post/{post}/edit', PostEditScreen::class)->name('platform.posts.edit');
    Route::screen('posts/post/create', PostEditScreen::class)->name('platform.posts.create');
    Route::screen('posts', PostListScreen::class)->name('platform.posts.list');
});

/* [Groups] */
Route::middleware(['access:platform.groups'])->group(function () {
    Route::screen('groups/group/{group?}/edit', GroupEditScreen::class)->name('platform.groups.edit');
    Route::screen('groups/group/create', GroupEditScreen::class)->name('platform.groups.create');
    Route::screen('groups', GroupListScreen::class)->name('platform.groups.list');
});

/* Documents */
Route::middleware(['access:platform.documents'])->group(function () {
	/* [Documents -> Rates] */
    Route::screen('documents/rates/rate/{rate}/edit', RatesEditScreen::class)->name('platform.rates.edit');
    Route::screen('documents/rates/rate/create', RatesEditScreen::class)->name('platform.rates.create');
    Route::screen('documents/rates', RatesListScreen::class)->name('platform.rates.list');
	
    /* [Documents -> Reports] */
    Route::screen('documents/reports/report/{report}/edit', ReportsEditScreen::class)->name('platform.reports.edit');
    Route::screen('documents/reports/report/create', ReportsEditScreen::class)->name('platform.reports.create');
    Route::screen('documents/reports', ReportsListScreen::class)->name('platform.reports.list');

    /* [Documents -> Payments] */
    Route::screen('documents/payments/payment/{payment?}/edit', PaymentsEditScreen::class)->name('platform.payments.edit');
    Route::screen('documents/payments/payment/create', PaymentsEditScreen::class)->name('platform.payments.create');
    Route::screen('documents/payments', PaymentsListScreen::class)->name('platform.payments.list');

    /* [Documents -> Tags] */
    Route::screen('documents/tags/{tag}/edit', TagEditScreen::class)->name('platform.tags.edit');
    Route::screen('documents/tags/create', TagEditScreen::class)->name('platform.tags.create');
    Route::screen('documents/tags', TagListScreen::class)->name('platform.tags.list');

    /* [Documents -> EconomicActivities] */
    Route::screen('documents/economic-activities/economic-activity/{economicActivities?}/edit', EconomicActivitiesEditScreen::class)->name('platform.economicActivities.edit');
    Route::screen('documents/economic-activities/economic-activity/{economicActivities?}/view', SubActivitiesListScreen::class)->name('platform.subActivities.list');
    Route::screen('documents/economic-activities/economic-activity/create', EconomicActivitiesEditScreen::class)->name('platform.economicActivities.create');
    Route::screen('documents/economic-activities', EconomicActivitiesListScreen::class)->name('platform.economicActivities.list');

    /* [Documents -> UsefulAccountingResources] */
    Route::screen('documents/useful-accounting-resources/resource/{usefulAccountingResource}/edit', UsefulAccountingResourceEditScreen::class)->name('platform.usefulAccountingResources.edit');
    Route::screen('documents/useful-accounting-resources/resource/create', UsefulAccountingResourceEditScreen::class)->name('platform.usefulAccountingResources.create');
    Route::screen('documents/useful-accounting-resources', UsefulAccountingResourceListScreen::class)->name('platform.usefulAccountingResources.list');
});


Route::middleware(['access:platform.clients'])->group(function () {
    /* [Clients] */
    Route::screen('clients/{client}/edit', ClientEditScreen::class)->name('platform.clients.edit');
    Route::screen('clients/create', ClientEditScreen::class)->name('platform.clients.create');
    Route::screen('clients/reporting/editscreen', ReportingScreen::class)->name('platform.reporting.list');
    Route::screen('clients', ClientListScreen::class)->name('platform.clients.list');
});

Route::middleware(['access:platform.manuals-edit'])->group(function () {
    /* [Manuals-edit] */
    Route::screen('manuals/{manual}/edit', ManualEditScreen::class)->name('platform.manuals.edit');
    Route::screen('manuals/create', ManualEditScreen::class)->name('platform.manuals.create');
    Route::screen('manuals', ManualListScreen::class)->name('platform.manuals.list');
});

Route::middleware(['access:platform.manuals-view'])->group(function () {
    /* [Manuals-view] */
    Route::screen('manuals/{manual}/view', ManualViewScreen::class)->name('platform.manuals.view');
});

/* [Example] */
Route::screen('example', ExampleScreen::class)->name('platform.example');
Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

