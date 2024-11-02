<?php

namespace App\Orchid\Screens;

use App\Jobs\ProcessCheckingClientCertificateStatus;
use App\Jobs\ProcessCheckingClientSalariesStatus;
use App\Jobs\ProcessCheckingReportsStatus;
use App\Models\Client;
use App\Models\ClientSalaries;
use App\Models\Group;
use App\Models\User;
use App\Models\UserColor;
use App\Modules\Reporting\Repositories\ReportingRepository;
use App\Modules\Reporting\Settings\ReportingSettings;
use App\Orchid\Filters\DateIntervalFilter;
use App\Orchid\Filters\GroupFilter;
use App\Modules\Reporting\Filters\ReportingEventsDisplayFilter;
use App\Modules\Reporting\Filters\ReportingFilter;
use App\Orchid\Filters\TypeOfTaxesFilter;
use App\Orchid\Layouts\Modal\ClientSalariesModalLayout;
use App\Orchid\Layouts\Modal\ReportingModalLayout;
use App\Models\Payment;
use App\Models\Report;
use App\Models\ClientReporting;
use App\Orchid\Layouts\Reporting\ReportingFiltersLayout;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Modal;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;

class ReportingScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить новую запись в отчетность';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Отчетность';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * @var array
     */
    public $events = [];

    public ReportingSettings $reportingSettings;

    public ReportingRepository $reportingRepository;
    /**
     * @var string
     */
    public $choiceGroups = '';

    /**
     * @param ReportingSettings $reportingSettings
     */
    public function __construct(ReportingSettings $reportingSettings, ReportingRepository $reportingRepository)
    {
        $this->reportingSettings = $reportingSettings;
        $this->reportingRepository = $reportingRepository;
    }

    public function query(Request $request, ReportingSettings $reportingSettings, ReportingRepository $reportingRepository): array
    {

        //Проставляем дефолтные настройки для пользователя
        $reportingSettings->setDefaultSettings($request);
        $defaultSettings = $reportingSettings;
        //Получаем все возможные отчеты и оплаты
        $events = $reportingRepository->getEvents($request);

        //Получаем всех клиентов с записями по отчетам и оплатам
        $clients = Client::with('typeOfTaxes', 'events', 'groups', 'payments', 'reports')
            ->filtersApply([GroupFilter::class, TypeOfTaxesFilter::class, ReportingEventsDisplayFilter::class])
            ->filters()
            ->orderBy('type_of_ownership', 'ASC')
            ->orderBy('organization', 'ASC')
            ->where('client_active', 1)
            ->paginate(100);

        //Начало формирования таблицы
        $eventslist[] = TD::make('organization', 'Организация')->width('200px')->filter(TD::FILTER_TEXT)->sort()->render(function (Client $client){
            $colors = UserColor::where('user_id', Auth::user()->id)->orderby('position')->get();
            //dd(Auth::user()->id);
            $color = !$client->userColors->isEmpty() ? $client->userColors[0]->color : '';
            $colorid = !$client->userColors->isEmpty() ? $client->userColors[0]->id : '';
            $infotext = "";
            $badge = "";
            $notification = false;
            $thisdate = date('Y-m-01');
            if(!$client->bankStatements->isEmpty()) {
                foreach ($client->bankStatements as $bankStatement) {
                    if($bankStatement->active == 1) {
                        if (strtotime($bankStatement->bank_statement_processing_date) < strtotime(date('Y-m-d', strtotime($thisdate . " - 1 day")))) {
                            $infotext .= "Не обновлена информация по банковской выписке - $bankStatement->checking_account" .
                                "<br /><b>Дата обработки выписки " . date('d-m-Y', strtotime($bankStatement->bank_statement_processing_date)) . "</b><br />---------------------------<br />";
                            $badge = "!";
                            $notification = true;
                        }
                    }
                }
                if(!$client->marketplaces->isEmpty()) {
                    foreach ($client->marketplaces as $marketplace) {
                        if ($marketplace->activity == 1) {
                            if (strtotime($marketplace->marketplace_processing_date) < strtotime(date('Y-m-d', strtotime($thisdate . " - 1 day")))) {
                                $infotext .= "Не обновлена информация по маркетплейсу - $marketplace->marketplace_name" .
                                    "<br /><b>Дата обработки " . date('d-m-Y', strtotime($marketplace->marketplace_processing_date)) . "</b><br />---------------------------<br />";
                                $badge = "!";
                                $notification = true;
                            }
                        }
                    }
                }
            }
            $target = '_blank';

            $modal = ModalToggle::make('🖌')
                ->modal('asyncModalColorsChoose')
                ->id((string)  $client->id)
                ->modalTitle('Укажите цвет')
                ->method('methodForChooseColorModal')
                ->asyncParameters([
                    'client' => $client->id,
                    'colors' => $colors,
                    'color' => $colorid
                ]);

            return view('colorsSettings/reporting-td', [
                'client' => $client,
                'modal' => $modal,
                'color' => $color,
                'infotext' => $infotext,
                'badge' => $badge,
                'notification' => $notification,
                'target' => $target
            ]);
        });
        $eventslist[] = TD::make('typeOfTaxes.name.', 'СНО')->width('200px')->sort()->render(function (Client $client) {
            return $client->typeOfTaxes[0]->name;
        });

        $eventslist[] = TD::make('salaries', 'Зарплаты и авансы')->width('150px')->align(TD::ALIGN_CENTER)->sort()->render(function (Client $client) {
            if($client->number_of_employees >= 1) {
                //валидируем текущием месяц по зарплатам клиента
                $clientSalariesDataCurrentMonth = $client->validateSalariesDeadlines(1);
                //валидируем предыдущий месяц по зарплатам клиента
                $clientSalariesDataPrevMonth = $client->validateSalariesDeadlines(2);
                $modalSalaries = ModalToggle::make('')
                    ->modal('asyncSalaries')
                    ->id(md5($client->id . 'Salaries'))
                    ->modalTitle($client->organization)
                    ->icon('note')
                    ->method('methodForSalariesModal')
                    ->asyncParameters([
                        'clientSalaries' =>
                            [
                                'salaries' => $client->salaries()
                                    ->whereBetween('month', [
                                        date("Y-m-01 H:i:s", strtotime("first day of -2 month")),
                                        date("Y-m-01 H:i:s", strtotime("+2 month"))
                                    ])
                                    ->get(),
                                'client_id' => $client->id,
                            ],
                    ]);

                return view('reporting/client-salaries-td', [
                    'background' => $clientSalariesDataCurrentMonth['background'],
                    'background2' => $clientSalariesDataPrevMonth['background'],
                    'modalSalaries' => $modalSalaries,
                ]);

            }

            return view('reporting/td', [
                'client' => $client,
            ]);
        });

        //перебираем каждый отчет и оплату
        foreach ($events as $key => $event) {
            $eventslist[] = TD::make('allEvents.0.'.$key.'.type', $event->name)
                ->align(TD::ALIGN_CENTER)
                ->width('170px')
                ->render(function (Client $client) use ($key, $event, $defaultSettings){
                    //мы выбраем из уже существующих записей по отчетам и оплатам клиента текущий отчет или оплату и проверяем что тип события и id совпадают
                    $clientEventValidate = $client->events->where('event_id', $event->id)
                        ->where('event_type', $event->type);

                    $backgroundWithValue = '';
                    $hiddenEvent = false;
                    //если фильтрация ключена, то отчеты и оплаты отсутствующие у клиента ячейки будут неактивны
                    if($defaultSettings->filterEvents) {
                        if ($event->type === "Отчет") {
                            if ($client->reports()->where('report_id', $event->id)->exists()) {
                                $backgroundWithValue = '';
                            } else {
                                $backgroundWithValue = 'unavailableReportModal';
                                $hiddenEvent = true;
                            }
                        }
                        if ($event->type === "Оплата") {
                            if ($client->payments()->where('payment_id', $event->id)->exists()) {
                                $backgroundWithValue = '';
                            } else {
                                $backgroundWithValue = 'unavailableReportModal';
                                $hiddenEvent = true;
                            }
                        }
                    }

                    //если у нас есть данные о записи то мы заполняем модальное окно и ячейку таблицы данными
                    if(!$clientEventValidate->isEmpty()){
                        /*
                        if($client->id === 3942 && $client->allEvents[0][$key]->id === 480){
                            dd($client->allEvents[0]);
                            dd($client->events->where('event_id', $client->allEvents[0][$key]->id));
                            dd($clientEventValidate->isEmpty());
                        }*/
                        //изменяем бэкграунд у ячеек в зависимости от условий
                        if ($clientEventValidate->first()->status === "Отправлено"){
                            $backgroundWithValue = 'reportStatusPass';
                        }
                        if ($clientEventValidate->first()->status === "Отказ"){
                            $backgroundWithValue = 'reportStatusFailure';
                        }
                        if ($clientEventValidate->first()->status === "Сдано"){
                            $backgroundWithValue = 'reportStatusSuccess';
                        }
                        return ModalToggle::make(!$clientEventValidate->isEmpty() ? $clientEventValidate->first()->event_action : "")
                            ->modal('asyncModal')
                            ->id(md5($client->id.$event->id))
                            ->modalTitle('Запись о событии')
                            ->class('btn btn-link '.$backgroundWithValue.'')
                            ->method('updateEventReporting')
                            ->asyncParameters([
                                'eventinfo' =>
                                    [
                                        'client_organization' => $client->organization,
                                        'event_id' => $event->id,
                                        'event_name' => $event->name,
                                        'event_type' => $event->type,
                                        'report_date' => $event->date,
                                        'event_action' => !$clientEventValidate->isEmpty() ? $clientEventValidate->first()->event_action : "",
                                        'status' => !$clientEventValidate->isEmpty() ? $clientEventValidate->first()->status : "",
                                        'client_id' => $client->id,
                                    ],
                            ]);
                    }
                    if($hiddenEvent){
                        return view('reporting/td', [
                            'client' => $client,
                        ]);
                    }
                    return ModalToggle::make( "+")
                        ->modal('asyncModal')
                        ->id(md5($client->id.$event->id))
                        ->class('btn border border-success text-success bg-light')
                        ->modalTitle('Запись о событии')
                        ->method('updateEventReporting')
                        ->asyncParameters([
                            'eventinfo' =>
                                [
                                    'client_organization' => $client->organization,
                                    'event_id' => $event->id,
                                    'event_name' => $event->name,
                                    'event_type' => $event->type,
                                    'report_date' => $event->date,
                                    'event_action' => !$clientEventValidate->isEmpty() ? $clientEventValidate->first()->event_action : "",
                                    'status' => !$clientEventValidate->isEmpty() ? $clientEventValidate->first()->status : "",
                                    'client_id' => $client->id,
                                ],
                        ]);

                });
        }

        $this->events = $eventslist;

        return [
            'clients' => $clients,
            'scroll' => $defaultSettings->scroll,
            'colors' => UserColor::where('user_id', $request->user()->id)->orderby('position')->get(),
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
            Link::make('Инструкция по работе с разделом')
                ->icon('cloud-download')
                ->href('/dashboard/manuals/3/view')
                ->target('_blank'),

            ModalToggle::make('Цвета')
                ->modal('ModalColors')
                ->icon('brush')
                ->modalTitle('Настройка цветов')
                ->method('methodForModalColors'),

            /*
                                  Button::make('Обновить записи о зарплатах')
                                  ->icon('number-list')
                                  ->method('updateClientSalaries'),

                              Button::make('Обновить записи об отчетах')
                                  ->icon('number-list')
                                  ->method('updateClientReporting'),

                              Button::make('Отправить уведомление')
                                  ->icon('number-list')
                                  ->method('pushNotify'),
                              */

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

            Layout::modal('asyncSalaries', [
                ClientSalariesModalLayout::class,
            ])->size(Modal::SIZE_LG)->async('asyncGetDataSalaries'),

            Layout::modal('ModalColors', [
                Layout::view('colorsSettings/index'),
            ])->withoutCloseButton(),

            Layout::modal('ModalColorsCreate', [
                Layout::view('colorsSettings/create'),
            ])->withoutCloseButton(),

            Layout::modal('asyncModalColorsChoose', [
                Layout::view('colorsSettings/choose'),
            ])->async('asyncGetData')->withoutApplyButton()->withoutCloseButton(),

        ];
    }

    public function updateClientReporting(){
        $clients = Client::whereNotNull('type_of_ownership')->get();

        foreach ($clients as $client) {
            if (!$client->events()->get()->isEmpty()){
                foreach($client->events()->get() as $event){
                    if (Payment::where('payment_name', $event->event_name)->exists()){
                        $payment = Payment::where('payment_name', $event->event_name)->get();
                        ClientReporting::where('id', $event->id)->update(['event_id' => $payment[0]->id, 'event_type' => 'Оплата']);
                    }
                    if (Report::where('report_name', $event->event_name)->exists()){
                        $report = Report::where('report_name', $event->event_name)->get();
                        ClientReporting::where('id', $event->id)->update(['event_id' => $report[0]->id, 'event_type' => 'Отчет']);
                    }
                }
            }
        }

        Toast::info('Вы синхронизировали записи о клиентах!');
        return back();
    }

    public function updateClientSalaries(){
        $clients = Client::whereNotNull('type_of_ownership')->get();
        foreach ($clients as $client) {
            $clientsSalariesList = [];
            for ($iM = 1; $iM <= 12; $iM++) {
                $clientsSalariesList[] = [
                    'prepayment_day' => $client->advance_payment_date ?? 15,
                    'payment_day' => $client->salary_payment_date ?? 30,
                    'month' => (string)date("Y-m-d H:i:s", strtotime("2024-$iM-01")),
                    'status' => 'Не обработано',
                ];
            }
            $client->salaries()->createMany($clientsSalariesList);
        }

        Toast::info('Вы синхронизировали зарплатные записи о клиентах!');
        return back();
    }

    public function methodForSalariesModal(Request $request)
    {
        $scrollId = md5($request->input('clientSalaries.client_id').'Salaries');
        $client = Client::where('id', (int) $request->input('clientSalaries.client_id'))->first();
        $clientSalaries = $client->salaries()->whereBetween('month', [
            date("Y-m-01 H:i:s", strtotime("first day of -2 month")),
            date("Y-m-01 H:i:s", strtotime("+2 month"))
        ])->get();

        foreach($client->addIds($request->input('clientSalaries.salaries'), $clientSalaries) as $salary){
            $clientSalary = ClientSalaries::where('id', $salary['id']);
            $clientSalary->update(
                [
                    'payment_day' => $salary['payment_day'],
                    'prepayment_day' => $salary['prepayment_day'],
                    'prepayment_status' => $salary['prepayment_status'],
                    'status' => $salary['status'],
                ]
            );

            $currentMonth = ClientSalaries::select('month as currentMonth')->where('id', $salary['id'])->first()->currentMonth;

            if ($salary['status'] === 'Обработано' && $currentMonth === date("Y-m-01 00:00:00")){

                $payments = Payment::with('paymentsSubtypes')->whereNotNull('payment_date')->whereBetween('payment_date', [
                    date("Y-m-01 H:i:s", strtotime("last day of next month")), date("Y-m-01 H:i:s", strtotime("+2 month"))
                ])->where('active', 1)->whereHas('paymentsSubtypes', function (Builder $query) {
                    $query->whereIn('payment_subtype_id', [11, 15, 16]);
                })->get() ?? false;

                foreach ($payments as $payment){
                    if($payment->exists() && $client->events->where('event_id', $payment->id ?? null)->where('event_type', 'Оплата')->isEmpty()){
                        ClientReporting::updateOrCreate(
                            [
                                'client_id' => (int) $request->input('clientSalaries.client_id'),
                                'event_id' => $payment->id,
                            ],
                            [
                                'event_action' => 'Обработано',
                                'event_name' => $payment->payment_name,
                                'report_date' => $payment->payment_date,
                                'event_id' => $payment->id,
                                'event_type' => 'Оплата',
                                'status' => 'Отправлено',
                            ]
                        );
                    }
                }

            }
        }

        Toast::info('Вы обновили данные о зарплатах и авансах!');
        return back()->with(['scroll' => true, 'scrollId' => $scrollId]);
    }

    //сохранение информации из модального окна
    public function updateEventReporting(Request $request)
    {
        //для возвращения на конкретную ячейку таблицы после обновления страницы
        $scrollId = md5($request->input('event_fields.client_id').$request->input('event_fields.event_id'));

        //добавить условие для сверки event_type, иначе могут перезаписываться другие события клиента с одинаковым id у отчета и оплаты
        ClientReporting::updateOrCreate(
            [
                'client_id' => $request->input('event_fields.client_id'),
                'event_id' => $request->input('event_fields.event_id'),
                'event_type' => $request->input('event_fields.event_type'),
            ],
            [
                'event_action' => $request->input('event_fields.event_action'),
                'report_date' => $request->input('event_fields.report_date'),
                'event_id' => $request->input('event_fields.event_id'),
                'event_type' => $request->input('event_fields.event_type'),
                'status' => $request->input('event_fields.status'),
            ]
        );

        Toast::info('Вы успешно добавили запись запись!');
        return back()->with(['scroll' => true, 'scrollId' => $scrollId]);
    }

    public function updateColors(Request $request)
    {
        function in_array_r($needle, $haystack, $strict = false) {
            foreach ($haystack as $item) {
                if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                    return true;
                }
            }

            return false;
        }

        $colors = UserColor::where('user_id', $request->user()->id)->orderby('position')->get();

        foreach ($colors as $key => $color) {
            $color->timestamps = false;
            $id = $color->id;


            if(!in_array_r($id, $request->colors)){
                $color->delete();
                //Toast::info('Цвет удален');
            }

            foreach ($request->colors as $colorFrontEnd) {

                if ($colorFrontEnd['id'] == $id) {
                    $color->update(['position' => $colorFrontEnd['position']]);
                    $color->update(['name' => $colorFrontEnd['name']]);
                    $color->update(['color' => $colorFrontEnd['color']]);
                }
            }
        }

        return response('Color Update Successful.', 200);
    }

    public function methodForChooseColorModal(Request $request)
    {
        $client = Client::where('id', $request->client)->with('userColors')->first();

        if ($request->get('detach_color') === '1' && $client->userColors()->exists()){
            $client->userColors()->detach($client->userColors[0]->id);
            Toast::info('Цвет удален');
            return back()->with(['scroll' => true, 'scrollId' => $request->client]);
        }

        if(!$client->userColors()->exists()){
            $client->userColors()->attach($request->get('color_id'));
        }else{
            $client->userColors()->detach($client->userColors[0]->id);
            $client->userColors()->attach($request->get('color_id'));
        }

        Toast::info('Цвет установлен');
        return back()->with(['scroll' => true, 'scrollId' => $request->client]);
    }

    public function methodForModalCreateColors(Request $request)
    {
        $requestData = $request->get('color');

        $requestData['user_id'] = Auth::user()->id;
        $requestData['position'] = 1;

        UserColor::create($requestData);

        Toast::info('Цвет добавлен');

        return back()->with('colorCreated', true);
    }

    public function pushNotify(Request $request)
    {

        //Выбираем клиента для теста работы отправки уведомлений.
        $client = Client::with('events', 'groups', 'payments', 'reports')->where('id', 3795)->first();
        $user = User::find(1);
        /*
                //Смотрим актуальность записией по модулю "Зарплаты и авансы"
                if ($client && $client->number_of_employees >= 1) {
                    $clientSalariesDeadlineData = $client->validateSalariesDeadlines();
                    $clientSalariesDeadlineType = "";
                    //Если дедлайн для смены статуса аванса просрочен (salariesPrepaymentDeadline === true) то отправляем уведомление
                    if ($clientSalariesDeadlineData['salariesPrepaymentDeadline']) {
                        //Проставляем что это аванс
                        $clientSalariesDeadlineType = "Аванс";
                        //Формируем уведомление и заполняем его данными
                        ProcessCheckingClientSalariesStatus::dispatch($user, $client, $clientSalariesDeadlineData['month'], $clientSalariesDeadlineType);
                    }
                    //Если дедлайн для смены статуса зарплаты просрочен (salariesPrepaymentDeadline === true) то отправляем уведомление
                    if ($clientSalariesDeadlineData['salariesPaymentDeadline']) {
                        //Проставляем что это аванс
                        $clientSalariesDeadlineType = "Зарплата";
                        //Формируем уведомление и заполняем его данными
                        ProcessCheckingClientSalariesStatus::dispatch($user, $client, $clientSalariesDeadlineData['month'], $clientSalariesDeadlineType);
                    }
                }
        */
        //dd(new DateTime() < new DateTime($client->certificate_end_date));
        //смотрим есть ли у клиента акутальная дата действия сертификата
        /*
                if ($client && !is_null($client->certificate_end_date)) {
                    if(new DateTime() < new DateTime($client->certificate_end_date)) {
                        if (date_diff(new DateTime(), new DateTime($client->certificate_end_date))->days <= 20) {
                            ProcessCheckingClientCertificateStatus::dispatch($user, $client, 20, "Истекает");
                        }
                    }else{
                        ProcessCheckingClientCertificateStatus::dispatch($user, $client, date_diff(new DateTime(), new DateTime($client->certificate_end_date))->days, "Истек");
                    }
                }
        */
        /*
                //смотрим есть ли у клиента патент
                if ($client && $client->patents()->where('client_id', $client->id)->exists()) {
                    $client->validatePatentsDeadlines();
                }
        */

        /*
                //Выборка всех отчетов и оплат за указанный диапазон дат (Сейчас стоит 2 месяца)
                $events = Report::select('id', 'report_name as name', 'report_date as date' , DB::raw('\'Отчет\' as type'))
                    ->whereBetween('report_date', [date('Y-m-01 H:i:s'), date("Y-m-01 H:i:s", strtotime("+2 month"))])
                    ->union(Payment::select('id', 'payment_name as name', 'payment_date as date' , DB::raw('\'Оплата\' as type'))
                    ->whereBetween('payment_date', [date('Y-m-01 H:i:s'), date("Y-m-01 H:i:s", strtotime("+2 month"))
                    ]))
                    ->orderBy('date')
                    ->get();


                if ($client) {
                //Идем по списку отчетов и оплат
                foreach ($events as $event) {
                    //Если клиент существует
                        //Если событие является отчетом и входит в список отчетов клиента в указанном диапазоне дат
                        if (($event->type === "Отчет") && $client->reports()->whereBetween('report_date', [date('Y-m-01 H:i:s'), date("Y-m-01 H:i:s", strtotime("+2 month"))])->where('report_id', $event->id)->exists()) {
                            //Если запись по отчету существует в таблице reporting
                            if ($client->events()->where('event_id', $event->id)->exists()) {
                                //Получаем данные о записи по данному событию из таблицы reporting
                                $eventData = $client->events()->where('event_id', $event->id)->get();
                                //если статус равен null то отправляем уведомление о необходимости заполнения информации о отчета
                                if (is_null($eventData[0]->status)) {
                                    //отправлеяем уведомление (с статусом об требовании изменения статуса для записи)
                                    ProcessCheckingReportsStatus::dispatch($user, $client, $event->id, $event->type);
                                }
                            }else{//если отчетов входит в список отчетов клиента но по нему нет записи за указанный диапазон
                                //отправляем уведомление (с статусом о незаполнености информации по отчету)
                                ProcessCheckingReportsStatus::dispatch($user, $client, $event->id, $event->type);
                            }
                        }
                        //Если событие является оплатой и входит в список оплат клиента в указанном диапазоне дат
                        if (($event->type === "Оплата") && $client->payments()->whereBetween('payment_date', [date('Y-m-01 H:i:s'), date("Y-m-01 H:i:s", strtotime("+2 month"))])->where('payment_id', $event->id)->exists()) {
                            //Если такая оплата существует в таблице отчетности у этого клиента
                            if ($client->events()->where('event_id', $event->id)->exists()) {
                                //Получаем данные о записи по данному событию из таблицы reporting
                                $eventData = $client->events()->where('event_id', $event->id)->get();
                                if (is_null($eventData[0]->status)) {
                                    //отправлеяем уведомление (с статусом об требовании изменения статуса для записи)
                                    ProcessCheckingReportsStatus::dispatch($user, $client, $event->id, $event->type);
                                }
                            }else{//если оплата входит в список оплат клиента но по нему нет записи за указанный диапазон
                                ProcessCheckingReportsStatus::dispatch($user, $client, $event->id, $event->type);
                            }
                        }
                    }

                }
        */
    }

    public function asyncGetDataSalaries($clientSalaries): array
    {

        return [
            'clientSalaries' => $clientSalaries,
        ];
    }

    public function asyncGetData($event_fields): array
    {
        return [
            'event_fields' => $event_fields,
        ];
    }

}
