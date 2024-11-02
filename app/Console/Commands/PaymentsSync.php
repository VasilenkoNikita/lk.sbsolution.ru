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

class PaymentsSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paymentsSync:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Синхронизация оплат у клиентов';

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
        $clientsList = Client::whereNotNull('type_of_ownership')->get();
        $paymentsList = Payment::whereNotNull('payment_date')->where('active', 1)->get();

        foreach ($clientsList as $clientKey => $client){

            $this->optimizeClientEvents($client->payments());

            $clientPaymentList = [];
            foreach ($paymentsList as $paymentKey => $payment){
                $clientPaymentList[] = $this->validateClientPayments($client->id, $payment);
            }

            $flist = [];
            foreach(array_filter($clientPaymentList, function($v) { return !is_null($v); }) as $key => $value) {
                $flist[$value->id] = ['comment' => $value->payment_name, 'activity' => $value->active];
            }

            if(isset($flist)){
                $client->payments()->syncWithoutDetaching($flist);
            }

            Log::info("Синхронизация оплат клиента $client->organization завершена");
        }

        Log::info("Синхронизация оплат завершена");
        return "Синхронизация оплат завершена";
    }

    public function optimizeClientEvents($model){
        $currentClientEventsList = !$model->get()->isEmpty() ? $model->get() : false;

        if($currentClientEventsList){
            foreach($currentClientEventsList as $key => $currentEvent){
                if ($currentEvent->pivot->added_by_user !== 1 || $currentEvent->active === 0){
                    $eventsForDelete[] = $currentEvent->id;
                }
            }

            if(isset($eventsForDelete)){
                $model->detach($eventsForDelete);
            }
        }
    }

    public function validateClientPayments($clientId, $payment){

        $client = Client::where('id', $clientId)->first();

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
                if($paymentSubtype[0]->id === 4 || $paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 11 || $paymentSubtype[0]->id === 15 || $paymentSubtype[0]->id === 16){
                    return $payment;
                }
            }
            if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" || $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН"){
                //Оплата налога УСН || Страховые взносы ИП || Страховые взносы за сотрудников || Оплата патента
                if($paymentSubtype[0]->id === 4 || $paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 11 || $paymentSubtype[0]->id === 13 || $paymentSubtype[0]->id === 15 || $paymentSubtype[0]->id === 16){
                    return $payment;
                }
            }
            if($client->typeOfTaxes[0]->name === "ОСНО"){
                //Оплата НДС || Оплата НДФЛ за ИП || Страховые взносы ИП || Страховые взносы за сотрудников
                if($paymentSubtype[0]->id === 1 || $paymentSubtype[0]->id === 3 || $paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 11 || $paymentSubtype[0]->id === 15 || $paymentSubtype[0]->id === 16){
                    return $payment;
                }
            }
            if($client->typeOfTaxes[0]->name === "ОСНО + ПСН"){
                //Оплата НДС || Оплата НДФЛ за ИП || Страховые взносы ИП || Страховые взносы за сотрудников || Оплата патента
                if($paymentSubtype[0]->id === 1 || $paymentSubtype[0]->id === 3 || $paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 11 || $paymentSubtype[0]->id === 13 || $paymentSubtype[0]->id === 15 || $paymentSubtype[0]->id === 16) {
                    return $payment;
                }
            }
            if($client->typeOfTaxes[0]->name === "ПСН"){
                //Страховые взносы ИП || Страховые взносы за сотрудников || Оплата патента
                if($paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 11 || $paymentSubtype[0]->id === 13 || $paymentSubtype[0]->id === 15 || $paymentSubtype[0]->id === 16) {
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
                    $paymentSubtype[0]->id === 15 ||
                    $paymentSubtype[0]->id === 16

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
                    $paymentSubtype[0]->id === 15 ||
                    $paymentSubtype[0]->id === 16
                ) {
                    return $payment;
                }
            }
        }
        return null;
    }

}
