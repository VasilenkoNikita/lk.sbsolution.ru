<?php

namespace App\Console\Commands;

use App\Models\Group;
use Illuminate\Console\Command;
use App\Jobs\ProcessCheckingClientCertificateStatus;
use App\Jobs\ProcessCheckingClientSalariesStatus;
use App\Jobs\ProcessCheckingReportsStatus;
use App\Models\Client;
use App\Models\ClientSalaries;
use App\Models\User;
use DateTime;
use App\Models\Payment;
use App\Models\Report;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clientNotifications:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param Log
     */
    public function handle()
    {
        $users = User::where('lk_client_notification', 1)->orWhere('lk_client_mail_notification', 1)->with('groups')->get();
        foreach ($users as $user) {
            $userGroups = User::where('id', $user->id)->with('groups')->get();

            $groupdata = [];
            foreach ($userGroups[0]->groups()->get() as $group) {
                $groupdata[] = $group->id;
            }

            //Смотрим актуальность записией по модулю "Зарплаты и авансы"
            $userClients = Client::with('events', 'groups', 'payments', 'reports')->whereHas('groups', function (Builder $query) use ($groupdata) {
                $query->whereIn('group_id', $groupdata);
            })->where('client_active', 1)->get();

            foreach ($userClients as $client) {
                if ($client && $client->number_of_employees >= 1) {
                    $clientSalariesDeadlineData = $client->validateSalariesDeadlines();
                    $clientSalariesDeadlineType = "";
                    //Если дедлайн для смены статуса аванса просрочен (salariesPrepaymentDeadline === true) то отправляем уведомление
                    if ($clientSalariesDeadlineData['salariesPrepaymentDeadline']) {
                        //Проставляем что это аванс
                        $clientSalariesDeadlineType = "Аванс";
                        //Формируем уведомление и заполняем его данными
                        Log::info("Отправка уведомления для пользователя $user->name, по клиенту $client->name, ClientSalary истекает");
                        ProcessCheckingClientSalariesStatus::dispatch(
                            $user,
                            $client,
                            $clientSalariesDeadlineData['month'],
                            $clientSalariesDeadlineType,
                            "ClientSalary",
                            md5($client->id . $user->id . $clientSalariesDeadlineData['id'] . $clientSalariesDeadlineType)
                        )->delay(now()->addSeconds(10));
                        Log::info("Сделано!");
                    }
                    //Если дедлайн для смены статуса зарплаты просрочен (salariesPrepaymentDeadline === true) то отправляем уведомление
                    if ($clientSalariesDeadlineData['salariesPaymentDeadline']) {
                        //Проставляем что это аванс
                        $clientSalariesDeadlineType = "Зарплата";
                        //Формируем уведомление и заполняем его данными
                        Log::info("Отправка уведомления для пользователя $user->name, по клиенту $client->name, ClientSalary истекло");
                        ProcessCheckingClientSalariesStatus::dispatch(
                            $user,
                            $client,
                            $clientSalariesDeadlineData['month'],
                            $clientSalariesDeadlineType,
                            "ClientSalary",
                            md5($client->id . $user->id . $clientSalariesDeadlineData['id'] . $clientSalariesDeadlineType)
                        )->delay(now()->addSeconds(10));
                        Log::info("Сделано!");
                    }
                }
                sleep(15);
                //смотрим есть ли у клиента акутальная дата действия сертификата
                if ($client && !is_null($client->certificate_end_date)) {
                    if (new DateTime() < new DateTime($client->certificate_end_date)) {
                        if (date_diff(new DateTime(), new DateTime($client->certificate_end_date))->days <= 20) {
                            Log::info("Отправка уведомления для пользователя $user->name, по клиенту $client->name, Certificate истекает");
                            ProcessCheckingClientCertificateStatus::dispatch(
                                $user,
                                $client,
                                date_diff(new DateTime(), new DateTime($client->certificate_end_date))->days,
                                "Истекает",
                                "Certificate",
                                md5($client->id . $user->id . 'certificate'))->delay(now()->addSeconds(10));
                            Log::info("Сделано!");
                        }
                    } else {
                        Log::info("Отправка уведомления для пользователя $user->name, по клиенту $client->name, Certificate истек");
                        ProcessCheckingClientCertificateStatus::dispatch(
                            $user,
                            $client,
                            date_diff(new DateTime(), new DateTime($client->certificate_end_date))->days,
                            "Истек",
                            "Certificate",
                            md5($client->id . $user->id . 'certificate'))->delay(now()->addSeconds(10));
                        Log::info("Сделано!");
                    }
                }
                sleep(15);
                //смотрим есть ли у клиента патент
                if ($client && $client->patents()->where('client_id', $client->id)->exists()) {
                    $client->validatePatentsDeadlines();
                }
                sleep(15);
                //Выборка всех отчетов и оплат за указанный диапазон дат (Сейчас стоит 2 месяца)
                $events = Report::select('id', 'report_name as name', 'report_date as date', DB::raw('\'Отчет\' as type'))
                    ->whereBetween('report_date', [date('Y-m-01 H:i:s'), date("Y-m-01 H:i:s", strtotime("+1 month"))])
                    ->union(Payment::select('id', 'payment_name as name', 'payment_date as date', DB::raw('\'Оплата\' as type'))
                        ->whereBetween('payment_date', [date('Y-m-01 H:i:s'), date("Y-m-01 H:i:s", strtotime("+1 month"))
                        ]))
                    ->orderBy('date')
                    ->get();
                if ($client) {
                    //Идем по списку отчетов и оплат
                    foreach ($events as $event) {
                        //Если клиент существует
                        //Если событие является отчетом и входит в список отчетов клиента в указанном диапазоне дат
                        if (($event->type === "Отчет") && $client->reports()->whereBetween('report_date', [date('Y-m-01 H:i:s'), date("Y-m-01 H:i:s", strtotime("+1 month"))])->where('report_id', $event->id)->exists()) {
                            //Если запись по отчету существует в таблице reporting
                            if(date_diff(new DateTime(), new DateTime($event->date))->days <= 5){
                                if ($client->events()->where('event_id', $event->id)->exists()) {
                                    //Получаем данные о записи по данному событию из таблицы reporting
                                    $eventData = $client->events()->where('event_id', $event->id)->get();
                                    //если статус равен null то отправляем уведомление о необходимости заполнения информации о отчета
                                    if (is_null($eventData[0]->status)) {
                                        //отправлеяем уведомление (с статусом об требовании изменения статуса для записи)
                                        Log::info("Отправка уведомления для пользователя $user->name, по клиенту $client->name, Report  $event->type истекает");
                                        ProcessCheckingReportsStatus::dispatch(
                                            $user,
                                            $client,
                                            $event->id,
                                            $event->type,
                                            "Report",
                                            md5($client->id.$user->id.$event->id.'report')
                                        )->delay(now()->addSeconds(10));
                                        Log::info("Сделано!");
                                    }
                                }else{//если отчетов входит в список отчетов клиента но по нему нет записи за указанный диапазон
                                    //отправляем уведомление (с статусом о незаполнености информации по отчету)
                                    Log::info("Отправка уведомления для пользователя $user->name, по клиенту $client->name, Report  $event->type истек");
                                    ProcessCheckingReportsStatus::dispatch(
                                        $user,
                                        $client,
                                        $event->id,
                                        $event->type,
                                        "Report",
                                        md5($client->id.$user->id.$event->id.'report')
                                    )->delay(now()->addSeconds(10));
                                    Log::info("Сделано!");
                                }
                            }
                        }
                        //Если событие является оплатой и входит в список оплат клиента в указанном диапазоне дат
                        if (($event->type === "Оплата") && $client->payments()->whereBetween('payment_date', [date('Y-m-01 H:i:s'), date("Y-m-01 H:i:s", strtotime("+1 month"))])->where('payment_id', $event->id)->exists()) {
                            if(date_diff(new DateTime(), new DateTime($event->date))->days <= 5) {
                                //Если такая оплата существует в таблице отчетности у этого клиента
                                if ($client->events()->where('event_id', $event->id)->exists()) {
                                    //Получаем данные о записи по данному событию из таблицы reporting
                                    $eventData = $client->events()->where('event_id', $event->id)->get();
                                    if (is_null($eventData[0]->status)) {
                                        //отправлеяем уведомление (с статусом об требовании изменения статуса для записи)
                                        Log::info("Отправка уведомления для пользователя $user->name, по клиенту $client->name, Payment  $event->type истекает");
                                        ProcessCheckingReportsStatus::dispatch(
                                            $user,
                                            $client,
                                            $event->id,
                                            $event->type,
                                            "Payment",
                                            md5($client->id.$user->id.$event->id.'payment')
                                        )->delay(now()->addSeconds(10));
                                        Log::info("Сделано!");
                                    }
                                } else {//если оплата входит в список оплат клиента но по нему нет записи за указанный диапазон
                                    Log::info("Отправка уведомления для пользователя $user->name, по клиенту $client->name, Payment  $event->type истек");
                                    ProcessCheckingReportsStatus::dispatch(
                                        $user,
                                        $client,
                                        $event->id,
                                        $event->type,
                                        "Payment",
                                        md5($client->id.$user->id.$event->id.'payment')
                                    )->delay(now()->addSeconds(10));
                                    Log::info("Сделано!");
                                }
                            }
                        }
                    }
                }
                sleep(15);
            }
        }

        Log::info("Cron is working fine!");
        return "done";
    }
}
