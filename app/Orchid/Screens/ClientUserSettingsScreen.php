<?php

namespace App\Orchid\Screens;

use App\Http\Controllers\Controller;
use App\Models\ClientUserSettings;
use App\Models\User;
use App\Orchid\Filters\DateIntervalFilter;
use App\Orchid\Filters\GroupFilter;
use App\Orchid\Filters\ReportingFilter;
use App\Orchid\Filters\TypeOfTaxesFilter;
use App\Orchid\Layouts\Modal\ReportingModalLayout;
use App\Orchid\Layouts\Reporting\ReportingFiltersLayout;
use Illuminate\Http\Request;
use App\Http\Requests\MassDestroyClientUserSettingsRequest;
use App\Http\Requests\StoreClientUserSettingsRequest;
use App\Http\Requests\UpdateClientUserSettingsRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Symfony\Component\HttpFoundation\Response;
use Orchid\Screen\Screen;


class ClientUserSettingsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Конструктор таблицы клиентов';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Конструктор таблицы клиентов';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * @var array
     */
    public $events = [];

    /**
     * @var string
     */
    public $choiceGroups = '';


    public function query(Request $request): array
    {

        return [

        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [

        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            ReportingFiltersLayout::class,

            Layout::wrapper('wrappers/reporting', [
                'table' => Layout::table('clients', $this->events),
            ]),

            Layout::modal('asyncModal', [
                ReportingModalLayout::class,
            ])->async('asyncGetData'),

        ];
    }

    public function methodForModal(Request $request)
    {
        ClientReporting::updateOrCreate(
            [
                'client_id' => $request->input('event_fields.client_id'),
                'event_name' => $request->input('event_fields.event_name'),
            ],
            [
                'event_action' => $request->input('event_fields.event_action'),
                'report_date' => $request->input('event_fields.report_date'),
            ]


        );

        Alert::info('Вы успешно добавили запись запись!');
        return back();
    }


    public function asyncGetData($event_fields): array
    {

        return [
            'event_fields' => $event_fields,
        ];
    }
}
