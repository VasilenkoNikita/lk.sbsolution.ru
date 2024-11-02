<?php

namespace App\Orchid\Screens;
use App\Models\ClientPayment;
use App\Models\ClientReport;
use App\Models\Payment;
use App\Models\Report;
use App\Models\User;
use App\Orchid\Layouts\Modal\ClientAddPaymentModalLayout;
use App\Orchid\Layouts\Modal\ClientAddReportModalLayout;
use App\Models\ClientAccess;
use App\Models\SubActivity;
use App\Models\TypesOfTaxes;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Group;
use App\Models\Rate;
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Facades\Toast;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group as Groupfields;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Switcher;
use App\Imports\ClientsImport;
use Maatwebsite\Excel\Facades\Excel;

class ClientEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Создать нового клиента';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Клиенты компании';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * Display header description.
     *
     * @var array
     */
    public array $regions = [];

    public int $paymentsCount = 0;
    public int $reportsCount = 0;

    public array $patents = [];
    public array $events = [];

    /**
     * Private services.
     *
     * @var string
     */
    public $services = '';

    /**
     * Query data.
     *
     * @param Client $client
     *
     * @return array
     */
    public function query(Client $client): array
    {
        $this->exists = $client->exists;

        if($this->exists){
            $this->name = 'Редактировать клиента';
        }

        $client->load('attachment');

        $this->regions = [
            'Алтайский край' => 'Алтайский край',
            'Амурская область' => 'Амурская область',
            'Архангельская область' => 'Архангельская область',
            'Астраханская область' => 'Астраханская область',
            'Байконур' => 'Байконур',
            'Белгородская область' => 'Белгородская область',
            'Брянская область' => 'Брянская область',
            'Владимирская область' => 'Владимирская область',
            'Волгоградская область' => 'Волгоградская область',
            'Вологодская область' => 'Вологодская область',
            'Воронежская область' => 'Воронежская область',
            'Еврейская автономная область' => 'Еврейская автономная область',
            'Забайкальский край' => 'Забайкальский край',
            'Ивановская область' => 'Ивановская область',
            'Иркутская область' => 'Иркутская область',
            'Калининградская область' => 'Калининградская область',
            'Калужская область' => 'Калужская область',
            'Камчатский край' => 'Камчатский край',
            'Кемеровская область - Кузбасс' => 'Кемеровская область - Кузбасс',
            'Кировская область' => 'Кировская область',
            'Костромская область' => 'Костромская область',
            'Краснодарский край' => 'Краснодарский край',
            'Красноярский край' => 'Красноярский край',
            'Курганская область' => 'Курганская область',
            'Курская область' => 'Курская область',
            'Ленинградская область' => 'Ленинградская область',
            'Липецкая область' => 'Липецкая область',
            'Магаданская область' => 'Магаданская область',
            'Москва' => 'Москва',
            'Московская область' => 'Московская область',
            'Мурманская область' => 'Мурманская область',
            'Ненецкий автономный округ' => 'Ненецкий автономный округ',
            'Нижегородская область' => 'Нижегородская область',
            'Новгородская область' => 'Новгородская область',
            'Новосибирская область' => 'Новосибирская область',
            'Омская область' => 'Омская область',
            'Оренбургская область' => 'Оренбургская область',
            'Орловская область' => 'Орловская область',
            'Пензенская область' => 'Пензенская область',
            'Пермский край' => 'Пермский край',
            'Приморский край' => 'Приморский край',
            'Псковская область' => 'Псковская область',
            'Республика Адыгея' => 'Республика Адыгея',
            'Республика Алтай' => 'Республика Алтай',
            'Республика Башкортостан' => 'Республика Башкортостан',
            'Республика Бурятия' => 'Республика Бурятия',
            'Республика Дагестан' => 'Республика Дагестан',
            'Республика Ингушетия' => 'Республика Ингушетия',
            'Республика Кабардино-Балкария' => 'Республика Кабардино-Балкария',
            'Республика Калмыкия' => 'Республика Калмыкия',
            'Республика Карачаево-Черкессия' => 'Республика Карачаево-Черкессия',
            'Республика Карелия' => 'Республика Карелия',
            'Республика Коми' => 'Республика Коми',
            'Республика Крым' => 'Республика Крым',
            'Республика Марий Эл' => 'Республика Марий Эл',
            'Республика Мордовия' => 'Республика Мордовия',
            'Республика Саха (Якутия)' => 'Республика Саха (Якутия)',
            'Республика Северная Осетия (Алания)' => 'Республика Северная Осетия (Алания)',
            'Республика Татарстан' => 'Республика Татарстан',
            'Республика Тыва (Тува)' => 'Республика Тыва (Тува)',
            'Республика Удмуртия' => 'Республика Удмуртия',
            'Республика Хакасия' => 'Республика Хакасия',
            'Республика Чечня' => 'Республика Чечня',
            'Республика Чувашия' => 'Республика Чувашия',
            'Ростовская область' => 'Ростовская область',
            'Рязанская область' => 'Рязанская область',
            'Самарская область' => 'Самарская область',
            'Санкт-Петербург' => 'Санкт-Петербург',
            'Саратовская область' => 'Саратовская область',
            'Сахалинская область' => 'Сахалинская область',
            'Свердловская область' => 'Свердловская область',
            'Севастополь' => 'Севастополь',
            'Смоленская область' => 'Смоленская область',
            'Ставропольский край' => 'Ставропольский край',
            'Тамбовская область' => 'Тамбовская область',
            'Тверская область' => 'Тверская область',
            'Томская область' => 'Томская область',
            'Тульская область' => 'Тульская область',
            'Ульяновская область' => 'Ульяновская область',
            'Хабаровский край' => 'Хабаровский край',
            'Ханты-Мансийский автономный округ' => 'Ханты-Мансийский автономный округ',
            'Челябинская область' => 'Челябинская область',
            'Чукотский автономный округ' => 'Чукотский автономный округ',
            'Ямало-Ненецкий автономный округ' => 'Ямало-Ненецкий автономный округ',
            'Ярославская область' => 'Ярославская область',
        ];


        if(\request()->user()->name === 'natalia.s') {
            $this->services = TextArea::make('client.services_provided')
                ->title('Предоставляемые клиенту услуги')
                ->rows(6)
                ->maxlength(2500);
        }else{
            $this->services = TextArea::make('client.services_provided')
                ->title('Предоставляемые клиенту услуги')
                ->rows(6)
                ->maxlength(2500)
                ->canSee(false);
        }



    if($client->type_of_ownership === 'ИП'){
        $this->patents = [Layout::rows([
            Matrix::make('client.patents')
                ->title('Патенты клиента')
                ->columns([
                    'Номер патента' => 'patent_number',
                    'Вид деятельности' => 'type_of_company',
                    'Адрес точки' => 'point_address',
                    'Дата начала' => 'patent_start_date',
                    'Дата окончания' => 'patent_end_date',
                    'Дата оплаты первого платежа' => 'first_date_of_payment',
                    'Дата оплаты второго платежа' => 'second_date_of_payment',
                    'Комментарий' => 'patent_comment'
                ])
                ->fields([
                    'patent_number' => Input::make(),
                    'type_of_company' => Input::make(),
                    'point_address' => Input::make(),
                    'patent_start_date' => DateTimer::make()->format('d-m-Y')->allowInput(),
                    'patent_end_date' => DateTimer::make()->format('d-m-Y')->allowInput(),
                    'first_date_of_payment' => DateTimer::make()->format('d-m-Y')->allowInput(),
                    'second_date_of_payment' => DateTimer::make()->format('d-m-Y')->allowInput(),
                    'patent_comment' => TextArea::make()->rows(3)
                ]),
        ])];
    }

    if($this->exists){
        $this->events = [
             Layout::tabs([
                    'Оплаты' => [
                        Layout::rows([
                            ModalToggle::make( "Добавить оплаты")
                                ->icon('pencil')
                                ->modal('addPayments')
                                ->modalTitle('Новые оплаты для добавления в список клиента')
                                ->class('btn btn-link bg-light float-right')
                                ->method('addPayments'),

                            Matrix::make('client.payments')
                                ->title('Оплаты')
                                ->columns([
                                    'Наименование оплаты' => 'payment_name',
                                    'Вид оплаты' => 'subtype',
                                    'Дата сдачи оплаты' => 'payment_date',
                                ])
                                ->fields([
                                    'payment_name' => Input::make(),
                                    'subtype' => Input::make(),
                                    'payment_date' => DateTimer::make()->allowInput(),
                                ])->maxRows($this->paymentsCount),
                        ]),
                    ],
                    'Отчеты' => [
                        Layout::rows([
                            ModalToggle::make( "Добавить отчеты")
                                ->icon('pencil')
                                ->modal('addReports')
                                ->modalTitle('Новые отчета для добавления в список клиента')
                                ->class('btn btn-link bg-light float-right')
                                ->method('addReports'),

                            Matrix::make('client.reports')
                                ->title('Отчеты')
                                ->columns([
                                    'Наименование отчета' => 'report_name',
                                    'Вид оплаты' => 'subtype',
                                    'Дата сдачи отчета' => 'report_date',
                                ])
                                ->fields([
                                    'report_name' => Input::make(),
                                    'subtype' => Input::make(),
                                    'report_date' => DateTimer::make()->allowInput(),
                                ])->maxRows($this->reportsCount),
                        ]),
                    ],
                ]
            ),
        ];
    }


        $this->paymentsCount = $client->payments()->get()->count();
        $this->reportsCount = $client->payments()->get()->count();
        return [
            'client' => $client,
            'bankStatements' => $client->bankStatements()->get(),
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
            /*ModalToggle::make('Импорт клиентов')
                ->icon('lock-open')
                ->method('asyncImport')
                ->modal('uploadClients'),*/

            Button::make('Создать клиента')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make('Обновить')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make('Синхронизировать оплаты')
                ->icon('note')
                ->method('updatePayments')
                ->canSee($this->exists),

            Button::make('Синхронизировать отчеты')
                ->icon('note')
                ->method('updateReports')
                ->canSee($this->exists),
            /*
                      Button::make('ЗП')
                          ->icon('note')
                          ->method('updateSalaries')
                          ->canSee($this->exists),

                     Button::make('Удалить')
                          ->icon('trash')
                          ->method('remove')
                          ->canSee($this->exists), */
        ];
    }

    /**
     * Views.
     *
     * @return
     */
    public function layout(): array
    {
        return [
            Layout::tabs([
                'Основная информация' => [
                    Layout::rows([
                        Groupfields::make([
                            Input::make('client.name')
                                ->title('Имя клиента')
                                ->placeholder('Иван Иванович')
                                ->help('Укажите имя клиента'),

                            Select::make('client.type_of_ownership')
                                ->options([
                                    'ИП'  => 'ИП',
                                    'ООО' => 'ООО',
                                    'АНО' => 'АНО',
                                ])
                                ->title('Форма собственности')
                                ->help('Укажите форму собственности клиента'),

                            Input::make('client.organization')
                                ->title('Наименование организации')
                                ->placeholder('"ООО" Лабеан')
                                ->help('Укажите наименование организации')
                        ])->fullWidth(),

                        Groupfields::make([
                            Select::make('client.typeOfTaxes.')
                                ->fromModel(TypesOfTaxes::class, 'name')
                                ->title('Система налогооблажения')
                                ->help('Укажите систему налогооблажения клиента'),

                            Input::make('client.reporting_system')
                                ->title('Система сдачи отчетности')
                                ->placeholder('СБИС')
                                ->help('Укажите систему сдачи отчетности'),

                            Input::make('client.inn')
                                ->title('ИНН Клиента')
                                ->required()
                                ->help('Укажите ИНН клиента')
                        ])->fullWidth(),

                        Groupfields::make([
                           Relation::make('client.type_of_company')
                                ->fromModel(SubActivity::class, 'code')
                                ->displayAppend('name_code')
                                ->title('Вид деятельности ОКВЭД')
                                ->help('Укажите вид деятельности организации'),

                            Select::make('client.region')
                                ->options($this->regions)
                                ->title('Регион регистрации')
                                ->help('Укажите регион')
                                ->empty('Москва', 'Москва'),

                            Select::make('client.groups.')
                                ->fromModel(Group::class, 'name')
                                ->multiple()
                                ->required()
                                ->title('Добавьте клиента в группу')
                        ])->fullWidth(),

                        Groupfields::make([
                            Input::make('client.type_of_company_actual')
                                ->title('Фактическая деятельность организации'),

							Select::make('client.rates.')
                                ->fromModel(Rate::class, 'name')
                                ->title('Тариф клиента')
								->empty('Не указано'),

							TextArea::make('client.rate_comment')
								->title('Комментарий к тарифу')
								->rows(6)
								->maxlength(2500)
                        ])->fullWidth(),

                        Matrix::make('client.placesBusinesses')
                            ->title('Места ведения деятельности')
                            ->columns([
                                'Город' => 'city',
                                'Регион' => 'region',
                                'Налоговая' => 'tax_registrar',
                            ])
                            ->fields([
                                'city' => Input::make(),
                                'region' => Select::make()
                                    ->options($this->regions)
                                    ->empty('Москва', 'Москва'),
                                'tax_registrar' => Input::make(),
                            ]),

                        Matrix::make('client.phones')
                            ->title('Телефоны клиента/организации')
                            ->columns([
                                'Телефон' => 'phone',
                                'Справочная информация' => 'additional_information',
                            ])
                            ->fields([
                                'phone' => Input::make()->placeholder('+7(999) 999-99-99')->mask('8(999) 999-99-99'),
                                'additional_information'  => Input::make()->value('Укажите email клиента'),
                            ]),

                        Matrix::make('client.emails')
                            ->title('E-mail клиента')
                            ->columns([
                                'Email' => 'email',
                                'Справочная информация' => 'additional_information',
                            ])
                            ->fields([
                                'email' => Input::make()->placeholder('example@domain.com'),
                                'additional_information' => Input::make()->value('Укажите email клиента'),
                            ]),

                        Matrix::make('client.accesses')
                            ->title('Доступы')
                            ->columns([
                                'Сервис' => 'service_name',
                                'Логин' => 'service_login',
                                'Пароль' => 'service_password',
                                'Комментарий' => 'comment',
                            ])
                            ->fields([
                                'service_name' => Input::make()->type('url'),
                                'service_login' => Input::make(),
                                'service_password' => Input::make(),
                                'comment' => Input::make(),
                            ]),

                        Groupfields::make([
                            Relation::make('client.accountant')
                                ->fromModel(User::class, 'name')
                                ->displayAppend('FullName')
                                ->title('Бухгалтер'),

                            Relation::make('client.assistant')
                                ->fromModel(User::class, 'name')
                                ->displayAppend('FullName')
                                ->title('Бухгалтер'),


                            Switcher::make('client.client_active')
                                ->sendTrueOrFalse()
                                ->title('Активность клиента')
                                ->help('Переключите активность клиента')
                        ])->fullWidth(),

                        Groupfields::make([

                            $this->services,

                            TextArea::make('client.description')
                                ->title('Краткая заметка о клиенте')
                                ->rows(6)
                                ->maxlength(2500)
                                ->placeholder('Можете добавить заметки по клиенту'),

                            TextArea::make('client.keeping_accounting')
                                ->title('Ведение учета')
                                ->rows(6)
                                ->maxlength(2500)
                                ->placeholder('Укажите где ведется учет')
                        ])->fullWidth(),

                        Upload::make('client.attachment')
                            ->title('Все файлы'),

                        Input::make('client.id')
                            ->type('hidden')
                    ])
                ],

                'Дополнительная информация' => [
                    Layout::rows([
                        Groupfields::make([
                            Input::make('client.certificate')
                                ->title('Номер сертификата')
                                ->help('Укажите номер сертификата'),

                            DateTimer::make('client.CertDate')
                                ->title('Дата окончания действия сертфиката')
                                ->placeholder('Укажите дату окончания действия сертификата')
                                ->format('d-m-Y')
                                ->allowInput()

                        ]),

                        Groupfields::make([
                            DateTimer::make('client.salary_payment_date')
                                ->title('Дата выплаты зарплаты')
                                ->placeholder('Укажите дату')
                                ->format('d')
                                ->allowInput(),

                            DateTimer::make('client.advance_payment_date')
                                ->title('Дата выплаты аванса')
                                ->placeholder('Укажите дату')
                                ->format('d')
                                ->allowInput()
                        ]),

                        Groupfields::make([
                            DateTimer::make('client.start_date')
                                ->title('Дата начала работы с клиентом')
                                ->placeholder('Укажите дату')
                                ->format('d-m-Y')
                                ->required()
                                ->allowInput(),

                            DateTimer::make('client.client_transfer_date')
                                ->title('Дата передачи клиента внутри фирмы')
                                ->placeholder('Укажите дату')
                                ->format('d-m-Y')
                                ->allowInput()
                        ]),

                        Groupfields::make([
                            Input::make('client.number_of_employees')
                                ->title('Количество сотрудников')
                                ->type('number'),

                            TextArea::make('client.primary_documents')
                                ->title('Первичная документация')
                                ->rows(3)
                                ->help('Укажите информацию о ведении первичной документации')
                        ]),

                        Groupfields::make([
                            TextArea::make('client.contracting_documents')
                                ->title('Документы для сторонних контрагентов')
                                ->rows(3)
                                ->help('Укажите информацию о ведении документации для сторонних контрагентов'),

                            TextArea::make('client.features_of_the_type_of_accounting')
                                ->title('Особенности вида деятельности по учету в компании')
                                ->rows(3)
                        ]),

                        Groupfields::make([
                            TextArea::make('client.features_of_calculating_taxes')
                                ->title('Особенности расчета налогов')
                                ->rows(3),

                            TextArea::make('client.preliminary_tax_calculation')
                                ->title('Предварительный расчет налога')
                                ->rows(3)
                        ]),

                        Groupfields::make([
                            TextArea::make('client.payment_procedure')
                                ->title('Порядок уплаты прибыли/ндс/усн/страх. взносов')
                                ->rows(3),

                            TextArea::make('client.comment_of_employees')
                                ->title('Комментарий к сотрудникам')
                                ->rows(3)
                        ]),

                        Groupfields::make([
                            TextArea::make('client.other_calculations_phys_clients')
                                ->title('Прочие расчеты с физ. лицами')
                                ->rows(3),

                            TextArea::make('client.loans')
                                ->title('Займы/фин. помощь/лизинг')
                                ->rows(3)
                        ]),

                        Groupfields::make([
                            TextArea::make('client.additional_information')
                                ->title('Дополнительная информация о клиенте')
                                ->rows(3),

                            TextArea::make('client.current_troubles')
                                ->title('Текущие проблемы')
                                ->rows(3)
                        ]),

                        Groupfields::make([
                            TextArea::make('client.history_cno')
                                ->title('История смены СНО')
                                ->rows(3),
                        ]),
                    ]),
                ],
                 'Патенты' => $this->patents,
                'Банковские выписки' => [
                    Layout::rows([
                        ModalToggle::make('Настройка порядка отображения')
                            ->modal('ModalBankStatementsPositions')
                            ->icon('list')
                            ->modalTitle('Настройка порядка отображения')
                            ->class('btn btn-link bg-light float-right')
                            ->method('methodForModalBankStatementsPositions'),

                        Matrix::make('client.bankStatements')
                            ->title('Банковские выписки')
                            ->columns([
                                'Наименование банка' => 'bank_name',
                                'Тип счета' => 'account_type',
                                'Расчетный счет' => 'checking_account',
                                'Дата обработки выписки' => 'bank_statement_processing_date',
                                'Комментарий' => 'comment',
                                'Активность' => 'active'
                            ])
                            ->fields([
                                'bank_name' => Input::make(),
                                'account_type' => Input::make(),
                                'checking_account' => Input::make(),
                                'bank_statement_processing_date' => DateTimer::make()->format('d-m-Y')->allowInput(),
                                'comment' =>  TextArea::make()->rows(3),
                                'active' =>  Switcher::make()
                                    ->sendTrueOrFalse(),
                            ]),
                        ]),
                ],
                'Маркетплейсы' => [
                    Layout::rows([
                        Matrix::make('client.marketplaces')
                            ->title('Данные по маркетплейсам')
                            ->columns([
                                'Наименование маркетплейса' => 'marketplace_name',
                                'Дата обработки отчета' => 'marketplace_processing_date',
                                'Комментарий' => 'comment',
                                'Активность' => 'activity',
                            ])
                            ->fields([
                                'marketplace_name' => Input::make(),
                                'marketplace_processing_date' => DateTimer::make()->format('d-m-Y')->allowInput(),
                                'comment' =>  TextArea::make()->rows(3),
                                'activity' =>  Switcher::make()->sendTrueOrFalse(),
                            ]),
                    ]),
                ],
                'Кассы' => [
                    Layout::rows([
                        Matrix::make('client.cashDesks')
                            ->title('Кассы')
                            ->columns([
                                'Наименование точки/кассы' => 'name_cash_desks',
                                'Дата обработки наличных' => 'date_of_cash_processing',
                                'Комментарий' => 'comment',
                            ])
                            ->fields([
                                'name_cash_desks'   => Input::make(),
                                'date_of_cash_processing' => DateTimer::make()->format('d-m-Y')->allowInput(),
                                'comment' =>  TextArea::make()->rows(3)
                            ]),
                    ]),
                ],

                'Доступы' => [
                    Layout::table('client.accesses',[
                        TD::make('service_name', 'Сервис')
                            ->sort()
                            ->filter(TD::FILTER_TEXT)
                            ->render(function (ClientAccess $clientAccess) {
                                if(!is_null($clientAccess->service_name)){
                                    $cl = $clientAccess->service_name;
                                }else{
                                    $cl = 'https://google.com';
                                }
                                return Link::make('https://'.parse_url($cl, PHP_URL_HOST))
                                    ->href($cl)
                                    ->target('_blank');
                            }),

                        TD::make('service_login', 'Логин'),
                        TD::make('service_password', 'Пароль'),
                        TD::make('comment', 'Комментарий'),
                    ]),
                ],

                'Оплаты и отчеты' => $this->events,
            ]),

            Layout::modal('ModalBankStatementsPositions', [
                Layout::view('BankStatementSettings/index'),
            ])->withoutCloseButton(),

            Layout::modal('addPayments', [
                ClientAddPaymentModalLayout::class,
            ]),

            Layout::modal('addReports', [
                ClientAddReportModalLayout::class,
            ]),

            Layout::modal('uploadClients', [
                Layout::rows([
                    Upload::make('upload')
                        ->title('Тема сообщение'),
                ]),
            ]),
        ];
    }



    public function addPayments(Client $client, Request $request)
    {

        if ($request->input('newClientPayments')){
            $paymentsIds = [];
            foreach($request->input('newClientPayments') as $payments => $payment){
                $paymentsIds[(int) $payment['payment_name']] = ['added_by_user' => 1];
            }
            $client->payments()->attach($paymentsIds);
        }

        Toast::info('Вы успешно добавли оплаты!');
        return back();
    }

    public function addReports(Client $client, Request $request)
    {
        if ($request->input('newClientReports')){
            $reportsIds = [];
            foreach($request->input('newClientReports') as $reports => $report){
                $reportsIds[(int) $report['report_name']] = ['added_by_user' => 1];
            }
            $client->reports()->attach($reportsIds);
        }

        Toast::info('Вы успешно добавли отчеты!');
        return back();
    }

    /**
     * @param Client $client
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function createOrUpdate(Client $client, Request $request): RedirectResponse
    {
        $request->validate([
            'client.inn' => 'required|unique:clients,inn,'.$client->id,
            'client.start_date' => 'required',
        ],
            [
                'client.inn.required' => 'Необходимо указать ИНН!',
                'client.inn.unique' => 'Такой клиент уже есть!',
                'client.start_date.required' => 'Необходимо указать дату начала работы с клиентом!',
            ]);

        $requestData = $request->get('client');

        if($request->input('client.patents')) {
            foreach ($requestData['patents'] as $key => $v) {
                foreach ($v as $s) {
                    if (!is_null($v['patent_start_date'])) {
                        $requestData['patents'][$key]['patent_start_date'] = (string)date("Y-m-d H:i:s", strtotime($v['patent_start_date']));
                    }
                    if (!is_null($v['patent_end_date'])) {
                        $requestData['patents'][$key]['patent_end_date'] = (string)date("Y-m-d H:i:s", strtotime($v['patent_end_date']));
                    }
                    if (!is_null($v['first_date_of_payment'])) {
                        $requestData['patents'][$key]['first_date_of_payment'] = (string)date("Y-m-d H:i:s", strtotime($v['first_date_of_payment']));
                    }
                    if (!is_null($v['second_date_of_payment'])) {
                        $requestData['patents'][$key]['second_date_of_payment'] = (string)date("Y-m-d H:i:s", strtotime($v['second_date_of_payment']));
                    }
                }
            }
        }
        if($request->input('client.bankStatements')) {
            foreach ($requestData['bankStatements'] as $key => $v) {
                foreach ($v as $s) {
                    if (!is_null($v['bank_statement_processing_date'])) {
                        $requestData['bankStatements'][$key]['bank_statement_processing_date'] = (string)date("Y-m-d H:i:s", strtotime($v['bank_statement_processing_date']));
                    }
                }
            }
        }
        if($request->input('client.marketplaces')) {
            foreach ($requestData['marketplaces'] as $key => $v) {
                foreach ($v as $s) {
                    if (!is_null($v['marketplace_processing_date'])) {
                        $requestData['marketplaces'][$key]['marketplace_processing_date'] = (string)date("Y-m-d H:i:s", strtotime($v['marketplace_processing_date']));
                    }
                }
            }
        }

        if($request->input('client.cashDesks')) {
            foreach ($requestData['cashDesks'] as $key => $v) {
                foreach ($v as $s) {
                    if (!is_null($v['date_of_cash_processing'])) {
                        $requestData['cashDesks'][$key]['date_of_cash_processing'] = (string)date("Y-m-d H:i:s", strtotime($v['date_of_cash_processing']));
                    }
                }
            }
        }

        function addIds($data, $source){
            if(!is_null($source)) {
                foreach ($source as $key => $s) {
                    isset($data[$key]) ? $data[$key] = ['id' => $s->id] + $data[$key] : false;
                }
            }

            return $data;
        }


        if(!is_null($request->input('client.start_date'))){
            $requestData['start_date'] = (string) date("Y-m-d H:i:s", strtotime($request->input('client.start_date')));
        }

        if(!is_null($request->input('client.client_transfer_date'))){
            $requestData['client_transfer_date'] = (string) date("Y-m-d H:i:s", strtotime($request->input('client.client_transfer_date')));
        }

        if(!is_null($request->input('client.salary_payment_date'))){
            $requestData['salary_payment_date'] = (int) $request->input('client.salary_payment_date');
        }

        if(!is_null($request->input('client.advance_payment_date'))){
            $requestData['advance_payment_date'] = (int) $request->input('client.advance_payment_date');
        }

        if(!is_null($request->input('client.CertDate'))){
            $requestData['certificate_end_date'] = (string) date("Y-m-d H:i:s", strtotime($request->input('client.CertDate')));
        }

        if(empty($request->input('client.groups', []))){
            $requestData['groups'] = [2];
        }

        if(!in_array(2, $requestData['groups'])){
            $requestData['groups'][] = 2;
        }

        if($request->input('client.payments')){
            foreach(addIds($requestData['payments'],$client->payments()->get()) as $payment){
                $requestPaymentsList[] = $payment['id'];
            }
            $client->payments()->sync($requestPaymentsList);
        }else{
            $client->payments()->detach();
        }
        if($request->input('client.reports')){
            foreach(addIds($requestData['reports'],$client->reports()->get()) as $report){
                $requestReportsList[] = $report['id'];
            }
            $client->reports()->sync($requestReportsList);
        }else{
            $client->reports()->detach();
        }


        $client->fill($requestData)->save();
        $client->groups()->sync($requestData['groups']);
		if(!is_null($request->input('client.rates')[0])){
			$client->rates()->sync($requestData['rates']);
		}else{
			$client->rates()->detach();
		}

        $client->typeOfTaxes()->sync($request->input('client.typeOfTaxes', []));




        $request->input('client.phones')
            ? $client->phones()->sync(addIds($requestData['phones'], $client->phones()->get()))
            : $client->phones->each(function($phones){$phones->delete();});

        $request->input('client.emails')
            ? $client->emails()->sync(addIds($requestData['emails'],$client->emails()->get()))
            : $client->emails->each(function($emails){$emails->delete();});

        $request->input('client.patents')
            ? $client->patents()->sync(addIds($requestData['patents'], $client->patents()->get()))
            : $client->patents->each(function($patents){$patents->delete();});

        $request->input('client.placesBusinesses')
            ? $client->placesBusinesses()->sync(addIds($requestData['placesBusinesses'], $client->placesBusinesses()->get()))
            : $client->placesBusinesses->each(function($placesBusinesses){$placesBusinesses->delete();});

        $request->input('client.bankStatements')
            ? $client->bankStatements()->sync(addIds($requestData['bankStatements'], $client->bankStatements()->get()))
            : $client->bankStatements->each(function($bankStatements){$bankStatements->delete();});

        $request->input('client.marketplaces')
            ? $client->marketplaces()->sync(addIds($requestData['marketplaces'], $client->marketplaces()->get()))
            : $client->marketplaces->each(function($marketplaces){$marketplaces->delete();});


        $request->input('client.cashDesks')
            ? $client->cashDesks()->sync(addIds($requestData['cashDesks'], $client->cashDesks()->get()))
            : $client->cashDesks->each(function($cashDesks){$cashDesks->delete();});

        $request->input('client.accesses')
            ? $client->accesses()->sync(addIds($requestData['accesses'], $client->accesses()->get()))
            : $client->accesses->each(function($accesses){$accesses->delete();});

        if (!$client->salaries()->exists()){
                $currentYear = date('Y');
                $clientsSalariesList = [];
                    for($iM =1;$iM<=12;$iM++){
                        $clientsSalariesList[] = [
                            'prepayment_day' => $client->advance_payment_date ?? 15,
                            'payment_day' => $client->salary_payment_date ?? 30,
                            'month' => (string) date("Y-m-d H:i:s", strtotime("$currentYear-$iM-01")),
                            'status' => 'Не обработано',
                        ];
                    }
                    $client->salaries()->sync($clientsSalariesList);
        }


        $client->attachment()->syncWithoutDetaching($request->input('client.attachment', []));
        Alert::info('Вы успешно создали запись о клиенте!');

        if(Auth::user()->name === 'elena.g') {
            return redirect()->route('platform.clients.list')->with(['scroll' => true, 'scrollId' => $client->id]);
        }


        return redirect()->route('platform.clients.edit', $client);
        //return redirect()->route('platform.clients.list')->with(['scroll' => true, 'scrollId' => $client->id]);
    }

    /**
     * @param Client $client
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function remove(Client $client): RedirectResponse
    {
        $client->delete()
            ? Alert::info('Вы успешно удалили запись о клиенте!')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.clients.list');
    }

    public function updatePayments(Client $client){
        //$client->payments()->detach();

        $paymentsList = Payment::whereNotNull('payment_date')->where('active', 1)->get();
        $clientPaymentList = [];

        function validateClientPayments($client, $payment){
            $currentClientPaymentsList = !$client->payments()->get()->isEmpty() ? $client->payments()->get() : false;

            if($currentClientPaymentsList){
                foreach($currentClientPaymentsList as $key => $currentPayment){
                    if ($currentPayment->pivot->added_by_user !== 1 || $currentPayment->active === 0){
                        $paymentsForDelete[] = $currentPayment->id;
                    }
                }
                if(isset($paymentsForDelete)){
                    $client->payments()->detach($paymentsForDelete);
                }
            }

            $paymentSubtype = $payment->paymentsSubtypes;

            if($client->type_of_ownership === 'ИП' && ($payment->type_of_ownership === 'Любая' || $payment->type_of_ownership === 'ИП') && ($client->number_of_employees === 0 || $client->number_of_employees === null)){
                if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы)"){
                    //Оплата налога УСН || Страховые взносы ИП
                    if($paymentSubtype[0]->id === 4 || $paymentSubtype[0]->id === 10){
                        return $payment;
                    }
                }
                if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" || $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН"){
                    //Оплата налога УСН || Страховые взносы ИП || Оплата патента
                    if($paymentSubtype[0]->id === 4 || $paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 13){
                        return $payment;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ОСНО"){
                    //Оплата НДС || Оплата НДФЛ за ИП || Страховые взносы ИП
                    if($paymentSubtype[0]->id === 1 || $paymentSubtype[0]->id === 3 || $paymentSubtype[0]->id === 10){
                        return $payment;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ОСНО + ПСН"){
                    //Оплата НДС || Оплата НДФЛ за ИП || Страховые взносы ИП || Оплата патента
                    if($paymentSubtype[0]->id === 1 || $paymentSubtype[0]->id === 3 || $paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 13){
                        return $payment;
                    }
                }
            }

            if($client->type_of_ownership === 'ИП' && ($payment->type_of_ownership === 'Любая' || $payment->type_of_ownership === 'ИП') && $client->number_of_employees >= 1) {
                if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы)"){
                    //Оплата налога УСН || Страховые взносы ИП || Страховые взносы за сотрудников
                    if($paymentSubtype[0]->id === 4 || $paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 11 || $paymentSubtype[0]->id === 15){
                        return $payment;
                    }
                }
                if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" || $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН"){
                    //Оплата налога УСН || Страховые взносы ИП || Страховые взносы за сотрудников || Оплата патента
                    if($paymentSubtype[0]->id === 4 || $paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 11 || $paymentSubtype[0]->id === 13 || $paymentSubtype[0]->id === 15){
                        return $payment;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ОСНО"){
                    //Оплата НДС || Оплата НДФЛ за ИП || Страховые взносы ИП || Страховые взносы за сотрудников
                    if($paymentSubtype[0]->id === 1 || $paymentSubtype[0]->id === 3 || $paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 11 || $paymentSubtype[0]->id === 15){
                        return $payment;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ОСНО + ПСН"){
                    //Оплата НДС || Оплата НДФЛ за ИП || Страховые взносы ИП || Страховые взносы за сотрудников || Оплата патента
                    if($paymentSubtype[0]->id === 1 || $paymentSubtype[0]->id === 3 || $paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 11 || $paymentSubtype[0]->id === 13 || $paymentSubtype[0]->id === 15) {
                        return $payment;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ПСН"){
                    //Страховые взносы ИП || Страховые взносы за сотрудников || Оплата патента
                    if($paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 11 || $paymentSubtype[0]->id === 13 || $paymentSubtype[0]->id === 15) {
                        return $payment;
                    }
                }

            }

            if(($client->type_of_ownership === 'ООО' || $client->type_of_ownership === 'АНО') && ($payment->type_of_ownership === 'Любая' || $payment->type_of_ownership === 'ООО') && ($client->number_of_employees === 0 || $client->number_of_employees === null)){
                if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" || $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН"){
                    //Оплата налога УСН || Оплата транспортного налога || Оплата земельного налога || Оплата налога на имущество || Страховые взносы за сотрудников
                    if($paymentSubtype[0]->id === 4 ||
                        $paymentSubtype[0]->id === 9 ||
                        $paymentSubtype[0]->id === 5 ||
                        $paymentSubtype[0]->id === 6
                    ) {
                        return $payment;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ОСНО"){
                    //Оплата НДС || Оплата налога на прибыль || Оплата транспортного налога || Оплата земельного налога || Оплата налога на имущество || Страховые взносы за сотрудников || НДФЛ
                    if($paymentSubtype[0]->id === 1 ||
                        $paymentSubtype[0]->id === 7 ||
                        $paymentSubtype[0]->id === 9 ||
                        $paymentSubtype[0]->id === 5 ||
                        $paymentSubtype[0]->id === 6
                    ) {
                        return $payment;
                    }
                }
            }

            if(($client->type_of_ownership === 'ООО' || $client->type_of_ownership === 'АНО') && ($payment->type_of_ownership === 'Любая' || $payment->type_of_ownership === 'ООО') && $client->number_of_employees >= 1){
                if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" || $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН"){
                    //Оплата налога УСН || Оплата транспортного налога || Оплата земельного налога || Оплата налога на имущество || Страховые взносы за сотрудников
                    if($paymentSubtype[0]->id === 4 ||
                        $paymentSubtype[0]->id === 9 ||
                        $paymentSubtype[0]->id === 5 ||
                        $paymentSubtype[0]->id === 6 ||
                        $paymentSubtype[0]->id === 11 ||
                        $paymentSubtype[0]->id === 15
                    ) {
                        return $payment;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ОСНО"){
                    //Оплата НДС || Оплата налога на прибыль || Оплата транспортного налога || Оплата земельного налога || Оплата налога на имущество || Страховые взносы за сотрудников || НДФЛ
                    if($paymentSubtype[0]->id === 1 ||
                        $paymentSubtype[0]->id === 7 ||
                        $paymentSubtype[0]->id === 9 ||
                        $paymentSubtype[0]->id === 5 ||
                        $paymentSubtype[0]->id === 6 ||
                        $paymentSubtype[0]->id === 11 ||
                        $paymentSubtype[0]->id === 15
                    ) {
                        return $payment;
                    }
                }
            }
            return null;
        }
        foreach ($paymentsList as $paymentKey => $payment){
            $clientPaymentList[] = validateClientPayments($client, $payment);
        }

        foreach(array_filter($clientPaymentList, function($v) { return !is_null($v); }) as $key => $value) {
            $flist[$value->id] = ['comment' => $value->payment_name, 'activity' => $value->active];
        }
        if(isset($flist)){
            $client->payments()->syncWithoutDetaching($flist);
        }

        return back();

    }

    public function updateReports(Client $client){
        //$client->reports()->detach();
        $reportsList = Report::whereNotNull('report_date')->where('active', 1)->get();
        $clientReportList = [];

        function validateClientReports($client, $report){

            $currentClientReportsList = !$client->reports()->get()->isEmpty() ? $client->reports()->get() : false;

            if($currentClientReportsList){
                foreach($currentClientReportsList as $key => $currentReport){
                    if ($currentReport->pivot->added_by_user !== 1 || $currentReport->active === 0){
                        $reportsForDelete[] = $currentReport->id;
                    }
                }
                if(isset($reportsForDelete)){
                    $client->reports()->detach($reportsForDelete);
                }
            }

            $reportSubtype = $report->reportsSubtypes;

            if($client->type_of_ownership === 'ИП' && ($report->type_of_ownership === 'Любая' || $report->type_of_ownership === 'ИП') && ($client->number_of_employees === 0 || $client->number_of_employees === null)){
                if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы)"){
                    //Декларация по УСН у ИП
                    if($reportSubtype[0]->id === 10 || $reportSubtype[0]->id === 22){
                        return $report;
                    }
                }
                if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" || $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН"){
                    //Декларация по УСН у ИП
                    if($reportSubtype[0]->id === 10 || $reportSubtype[0]->id === 22){
                        return $report;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ОСНО"){
                    //НДС || 3-НДФЛ
                    if($reportSubtype[0]->id === 13 || $reportSubtype[0]->id === 3 || $reportSubtype[0]->id === 22){
                        return $report;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ОСНО + ПСН"){
                    //НДС || 3-НДФЛ
                    if($reportSubtype[0]->id === 13 || $reportSubtype[0]->id === 3 || $reportSubtype[0]->id === 22){
                        return $report;
                    }
                }
            }

            if($client->type_of_ownership === 'ИП' && ($report->type_of_ownership === 'Любая' || $report->type_of_ownership === 'ИП') && $client->number_of_employees >= 1) {
                if ($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы)" ||
                    $client->typeOfTaxes[0]->name === "УСН (Доходы)" ||
                    $client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" ||
                    $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН") {
                    //Декларация по УСН у ИП || 2-НДФЛ || 6-НДФЛ || Расчет по страховым взносам || СЗВ-М || СЗВ-СТАЖ || ФСС || ЕФС-1
                    if ($reportSubtype[0]->id === 10 ||
                        $reportSubtype[0]->id === 2 ||
                        $reportSubtype[0]->id === 4 ||
                        $reportSubtype[0]->id === 15 ||
                        $reportSubtype[0]->id === 16 ||
                        $reportSubtype[0]->id === 17 ||
                        $reportSubtype[0]->id === 18 ||
                        $reportSubtype[0]->id === 20 ||
                        $reportSubtype[0]->id === 21 ||
                        $reportSubtype[0]->id === 22

                    ) {
                        return $report;
                    }
                }
                if ($client->typeOfTaxes[0]->name === "ОСНО" || $client->typeOfTaxes[0]->name === "ОСНО + ПСН") {
                    //НДС || 3-НДФЛ || 2-НДФЛ || 6-НДФЛ || Расчет по страховым взносам || СЗВ-М || СЗВ-СТАЖ || ФСС || ЕФС-1
                    if ($reportSubtype[0]->id === 13 ||
                        $reportSubtype[0]->id === 3 ||
                        $reportSubtype[0]->id === 2 ||
                        $reportSubtype[0]->id === 4 ||
                        $reportSubtype[0]->id === 15 ||
                        $reportSubtype[0]->id === 16 ||
                        $reportSubtype[0]->id === 17 ||
                        $reportSubtype[0]->id === 18 ||
                        $reportSubtype[0]->id === 20 ||
                        $reportSubtype[0]->id === 21 ||
                        $reportSubtype[0]->id === 22
                    ) {
                        return $report;
                    }
                }
            }
            if($client->type_of_ownership === 'ООО'  || $client->type_of_ownership === 'АНО'  && ($report->type_of_ownership === 'Любая' || $report->type_of_ownership === 'ООО')){
                if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" || $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН"){
                    //Декларация по УСН у ООО || Бухотчетоность || Подтверждение основного вида деятельности || Персонифицированные сведения о физлицах || ЕФС-1
                    if($reportSubtype[0]->id === 11 ||
                        $reportSubtype[0]->id === 5||
                        $reportSubtype[0]->id === 2 ||
                        $reportSubtype[0]->id === 4 ||
                        $reportSubtype[0]->id === 15 ||
                        $reportSubtype[0]->id === 16 ||
                        $reportSubtype[0]->id === 17 ||
                        $reportSubtype[0]->id === 18 ||
                        $reportSubtype[0]->id === 19 ||
                        $reportSubtype[0]->id === 20 ||
                        $reportSubtype[0]->id === 21 ||
                        $reportSubtype[0]->id === 22
                    ) {
                        return $report;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ОСНО"){
                    //НДС || Декларация по прибыли || Бухотчетоность || Подтверждение основного вида деятельности || Персонифицированные сведения о физлицах || ЕФС-1
                    if($reportSubtype[0]->id === 13 ||
                        $reportSubtype[0]->id === 9 ||
                        $reportSubtype[0]->id === 5 ||
                        $reportSubtype[0]->id === 2 ||
                        $reportSubtype[0]->id === 4 ||
                        $reportSubtype[0]->id === 15 ||
                        $reportSubtype[0]->id === 16 ||
                        $reportSubtype[0]->id === 17 ||
                        $reportSubtype[0]->id === 18 ||
                        $reportSubtype[0]->id === 19 ||
                        $reportSubtype[0]->id === 20 ||
                        $reportSubtype[0]->id === 21 ||
                        $reportSubtype[0]->id === 22
                    ) {
                        return $report;
                    }
                }
            }
            return null;
        }


        foreach ($reportsList as $reportKey => $report){
            $clientReportList[] = validateClientReports($client, $report);
        }

        foreach(array_filter($clientReportList, function($v) { return !is_null($v); }) as $key => $value) {
            $rlist[$value->id] = ['comment' => $value->report_name, 'activity' => $value->active];
        }

        if(isset($rlist)){
            $client->reports()->syncWithoutDetaching($rlist);
        }

        return back();
    }

    public function asyncImport(Client $client, Request $request)
    {
        $attach = Attachment::where('id', $request->get('upload'))->get();
        $attachfile = "";
        foreach ($attach as $attachs) {
            $attachfile = public_path() .'/storage/' . $attachs->path . $attachs->name . '.' . $attachs->extension;
        }
        Excel::import(new ClientsImport(), $attachfile);

        return back();
    }

    public function updateSalaries(Client $client){
        if (!$client->salaries()->exists()){
            $currentYear = date('Y');
            $clientsSalariesList = [];
            for($iM =1;$iM<=12;$iM++){
                $clientsSalariesList[] = [
                    'prepayment_day' => $client->advance_payment_date ?? 15,
                    'payment_day' => $client->salary_payment_date ?? 30,
                    'month' => (string) date("Y-m-d H:i:s", strtotime("$currentYear-$iM-01")),
                    'status' => 'Не обработано',
                ];
            }
            $client->salaries()->sync($clientsSalariesList);
        }
        Alert::info('Вы успешно обновили зп!');
        return back();
    }

    public function updateBankStatementsSettings(Client $client, Request $request)
    {
        function addIds($data, $source){
            if(!is_null($source)) {
                foreach ($source as $key => $s) {
                    isset($data[$key]) ? $data[$key] = ['id' => $s->id] + $data[$key] : false;
                }
            }

            return $data;
        }

        $requestData = $request->get('bankStatements');

        if($requestData) {
            foreach ($requestData as $key => $v) {
                foreach ($v as $s) {
                    if (!is_null($v['bank_statement_processing_date'])) {
                        $requestData[$key]['bank_statement_processing_date'] = (string)date("Y-m-d H:i:s", strtotime($v['bank_statement_processing_date']));
                    }
                }
            }
        }


        $requestData
            ? $client->bankStatements()->sync(addIds($requestData, $client->bankStatements()->get()))
            : $client->bankStatements->each(function($bankStatements){
            $bankStatements->delete();
        });

        return response('BankStatements Update Successful.', 200);
    }

    public function methodForModalBankStatementsPositions()
    {
        return back();
    }

}
