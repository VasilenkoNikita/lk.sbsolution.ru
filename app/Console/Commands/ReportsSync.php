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

class ReportsSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reportsSync:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Синхронизация отчетов у клиентов';

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
        $clientsList = Client::whereNotNull('type_of_ownership')->get(); //Список клиентов
        $reportsList = Report::whereNotNull('report_date')->where('active', 1)->get(); //Список активных отчетов

        foreach ($clientsList as $clientKey => $client){

            $this->optimizeClientEvents($client->reports());

            $clientReportList = [];
            foreach ($reportsList as $reportKey => $report){
                $clientReportList[] = $this->validateClientReports($client->id, $report);
            }
            $rlist = [];
            foreach(array_filter($clientReportList, function($v) { return !is_null($v); }) as $key => $value) {
                $rlist[$value->id] = ['comment' => $value->report_name, 'activity' => $value->active];
            }
            if(isset($rlist)){
                $client->reports()->syncWithoutDetaching($rlist);
            }

            Log::info("Синхронизация отчетов клиента $client->organization завершена");
        }
        Log::info("Синхронизация отчетов завершена");
        return "Синхронизация отчетов завершена";
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

    public function validateClientReports($clientId, $report){
        $client = Client::where('id', $clientId)->first();

        $reportSubtype = $report->reportsSubtypes;

        if($client->type_of_ownership === 'ИП' && ($report->type_of_ownership === 'Любая' || $report->type_of_ownership === 'ИП') && ($client->number_of_employees === 0 || $client->number_of_employees === null)){
            if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы)"){
                //Декларация по УСН у ИП
                if($reportSubtype[0]->id === 10 || $report->id === 395 || $report->id === 398 || $report->id === 401 || $reportSubtype[0]->id === 24){

                    return $report;
                }
            }
            if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" || $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН"){
                //Декларация по УСН у ИП
                if($reportSubtype[0]->id === 10 || $report->id === 395 || $report->id === 398 || $report->id === 401 || $reportSubtype[0]->id === 24){
                    return $report;
                }
            }
            if($client->typeOfTaxes[0]->name === "ОСНО"){
                //НДС || 3-НДФЛ
                if($reportSubtype[0]->id === 13 || $reportSubtype[0]->id === 3 || $reportSubtype[0]->id === 22 || $reportSubtype[0]->id === 24){
                    return $report;
                }
            }
            if($client->typeOfTaxes[0]->name === "ОСНО + ПСН"){
                //НДС || 3-НДФЛ
                if($reportSubtype[0]->id === 13 || $reportSubtype[0]->id === 3 || $reportSubtype[0]->id === 22 || $reportSubtype[0]->id === 24){
                    return $report;
                }
            }
            if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" || $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН" || $client->typeOfTaxes[0]->name === "ОСНО + ПСН"){
                //Только для клиентов с патентом
                if($reportSubtype[0]->id === 23 || $reportSubtype[0]->id === 24){
                    return $report;
                }
            }
        }

        if($client->type_of_ownership === 'ИП' && ($report->type_of_ownership === 'Любая' || $report->type_of_ownership === 'ИП') && $client->number_of_employees >= 1) {
            
            if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" || $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН" || $client->typeOfTaxes[0]->name === "ОСНО + ПСН"){
                //Только для клиентов с патентом
                if($reportSubtype[0]->id === 23 || $reportSubtype[0]->id === 24){
                    return $report;
                }
            }

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
                    $reportSubtype[0]->id === 22 ||
                    $reportSubtype[0]->id === 24
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
                    $reportSubtype[0]->id === 22 ||
                    $reportSubtype[0]->id === 24
                ) {
                    return $report;
                }
            }
        }
        if($client->type_of_ownership === 'ООО'  || $client->type_of_ownership === 'АНО'  && ($report->type_of_ownership === 'Любая' || $report->type_of_ownership === 'ООО')){
            if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" || $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН" || $client->typeOfTaxes[0]->name === "ОСНО + ПСН"){
                //Только для клиентов с патентом
                if($reportSubtype[0]->id === 23 || $reportSubtype[0]->id === 24){
                    return $report;
                }
            }
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
                    $reportSubtype[0]->id === 22 ||
                    $reportSubtype[0]->id === 24
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
                    $reportSubtype[0]->id === 22 ||
                    $reportSubtype[0]->id === 24
                ) {
                    return $report;
                }
            }
        }
        return null;
    }
}
