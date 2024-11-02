<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Client;
use App\Models\ClientPayment;
use App\Models\ClientReport;
use App\Models\PaymentSubtype;
use App\Models\PaymentType;
use App\Models\Report;
use App\Orchid\Layouts\Modal\PaymentsSubtypesModalLayout;
use App\Orchid\Layouts\Modal\PaymentsTypesModalLayout;
use App\Orchid\Layouts\PaymentsListLayout;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PaymentsListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список оплат';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Все оплаты';

    public $cansee = false;


    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {

        if(Auth::user()->name === 'natalia.s' || Auth::user()->name === 'anastasia.e' || Auth::user()->name === 'admin' ) {
            $this->cansee = true;
        }

        return [
            'payments' => Payment::filters()->defaultSort('payment_date')->paginate(30),
            'paymentsTypes' => PaymentType::get(),
            'paymentsSubtypes' => PaymentSubtype::get(),
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
            ModalToggle::make( "Типы оплат")
                ->icon('pencil')
                ->modal('paymentsTypesModal')
                ->modalTitle('Типы оплат')
                ->canSee($this->cansee)
                ->method('paymentsTypesModal'),

            ModalToggle::make( "Виды оплат")
                ->icon('pencil')
                ->modal('paymentsSubtypesModal')
                ->modalTitle('Виды оплат')
                ->canSee($this->cansee)
                ->method('paymentsSubtypesModal'),

            Link::make('Создать новую оплату')
                ->icon('pencil')
                ->canSee($this->cansee)
                ->route('platform.payments.create'),
/*
            Button::make('Обновить отчеты и оплаты клиентов')
                ->icon('number-list')
                ->method('updateReportsPayments'),

            Button::make('Обновить оплаты и отчеты')
                ->icon('number-list')
                ->method('updateNewReportsPayments'),
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
            PaymentsListLayout::class,
            Layout::modal('paymentsTypesModal', [
                PaymentsTypesModalLayout::class,
            ]),
            Layout::modal('paymentsSubtypesModal', [
                PaymentsSubtypesModalLayout::class,
            ]),
        ];
    }

    public function addIds($data, $source){
        if(!is_null($source)) {
            foreach ($source as $key => $s) {
                isset($data[$key]) ? $data[$key] = ['id' => $s->id] + $data[$key] : false;
            }
        }

        return $data;
    }

    public function paymentsTypesModal(Request $request)
    {

        $requestPaymentsTypesList = [];

        $paymentsTypesList = $this->addIds($request->input('paymentsTypes'), PaymentType::get());
        if ($request->input('paymentsTypes')){
            foreach($paymentsTypesList as $paymentsTypes => $paymentType){
                if(isset($paymentType["id"])){
                    PaymentType::updateOrCreate(['id' => $paymentType["id"]], ['name' => $paymentType["name"]]);
                    $requestPaymentsTypesList[] = $paymentType["id"];
                }else{
                    $paymentNewType = PaymentType::updateOrCreate(['name' => $paymentType["name"]]);
                    $requestPaymentsTypesList[] = $paymentNewType->id;
                }
            }
        }

        if(!PaymentType::get()->isEmpty()) {
            foreach (PaymentType::get() as $key => $val) {
                if(!in_array($val->id, $requestPaymentsTypesList, true)){
                    PaymentType::where('id', $val->id)->delete();
                }
            }
        }else{
            PaymentType::whereNotNull('name')->delete();
        }

        Toast::info('Вы успешно обновили типы оплат!');
        return back();
    }

    public function paymentsSubtypesModal(Request $request)
    {
        $requestPaymentsSubtypesList = [];

        $paymentsSubtypesList = $this->addIds($request->input('paymentsSubtypes'), PaymentSubtype::get());
        if ($request->input('paymentsSubtypes')){
            foreach($paymentsSubtypesList as $paymentsSubtypes => $paymentSubtype){
                if(isset($paymentSubtype["id"])){
                    PaymentSubtype::updateOrCreate(['id' => $paymentSubtype["id"]], ['name' => $paymentSubtype["name"]]);
                    $requestPaymentsSubtypesList[] = $paymentSubtype["id"];
                }else{
                    $paymentNewSubtype = PaymentSubtype::updateOrCreate(['name' => $paymentSubtype["name"]]);
                    $requestPaymentsSubtypesList[] = $paymentNewSubtype->id;
                }
            }
        }
        if(!PaymentSubtype::get()->isEmpty()) {
            foreach (PaymentSubtype::get() as $key => $val) {
                if(!in_array($val->id, $requestPaymentsSubtypesList, true)){
                    PaymentSubtype::where('id', $val->id)->delete();
                }
            }
        }else{
            PaymentSubtype::whereNotNull('name')->delete();
        }

        Toast::info('Вы успешно обновили виды оплат!');
        return back();
    }

    function updateNewReportsPayments (){
        //Оплаты
        $arrPay = array("2020" => "2021","2021" => "2022");
        $payments = Payment::with('paymentsTypes', 'paymentsSubtypes')->get();
        foreach ($payments as $payment){
            $newPaymentDate = date("Y-m-d H:i:s", strtotime('+1 year', strtotime($payment->payment_date)));
            $oldPaymentName = $payment->payment_name;
            $newPaymentName = strtr($oldPaymentName, $arrPay);


            $newPayment = $payment->replicate()->fill([
                'payment_name' => $newPaymentName,
                'payment_date' => $newPaymentDate,
                'active' => 0,
            ]);

            $newPayment->save();
        }

        //Отчеты
        $arrRep = array("2020" => "2021","2021" => "2022");
        $reports = Report::with('reportsTypes', 'reportsSubtypes')->get();
        foreach ($reports as $report){
            $newReportDate = date("Y-m-d H:i:s", strtotime('+1 year', strtotime($report->report_date)));
            $oldReportName = $report->report_name;
            $newReportName = strtr($oldReportName, $arrRep);


            $newReport = $report->replicate()->fill([
                'report_name' => $newReportName,
                'report_date' => $newReportDate,
                'active' => 0,
            ]);

            $newReport->save();
        }
    }
    function optimizeClientEvents($model){
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

    function updateReportsPayments (){
        $clientsList = Client::whereNotNull('type_of_ownership')->get();
        $paymentsList = Payment::whereNotNull('payment_date')->where('active', 1)->get();
        $reportsList = Report::whereNotNull('report_date')->where('active', 1)->get();
        $clientPaymentList = [];
        $clientReportList = [];

        function validateClientPayments($clientId, $payment){

            $client = Client::where('id', $clientId)->first();

            $paymentSubtype = $payment->paymentsSubtypes;

            if($client->type_of_ownership === 'ИП' && ($client->number_of_employees === 0 || $client->number_of_employees === null)){
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

            if($client->type_of_ownership === 'ИП' && $client->number_of_employees >= 1) {
                if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы)"){
                    //Оплата налога УСН || Страховые взносы ИП || Страховые взносы за сотрудников
                    if($paymentSubtype[0]->id === 4 || $paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 11){
                        return $payment;
                    }
                }
                if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" || $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН"){
                    //Оплата налога УСН || Страховые взносы ИП || Страховые взносы за сотрудников || Оплата патента
                    if($paymentSubtype[0]->id === 4 || $paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 11 || $paymentSubtype[0]->id === 13){
                        return $payment;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ОСНО"){
                    //Оплата НДС || Оплата НДФЛ за ИП || Страховые взносы ИП || Страховые взносы за сотрудников
                    if($paymentSubtype[0]->id === 1 || $paymentSubtype[0]->id === 3 || $paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 11){
                        return $payment;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ОСНО + ПСН"){
                    //Оплата НДС || Оплата НДФЛ за ИП || Страховые взносы ИП || Страховые взносы за сотрудников || Оплата патента
                    if($paymentSubtype[0]->id === 1 || $paymentSubtype[0]->id === 3 || $paymentSubtype[0]->id === 10 || $paymentSubtype[0]->id === 11 || $paymentSubtype[0]->id === 13) {
                        return $payment;
                    }
                }
            }

            if($client->type_of_ownership === 'ООО'){
                if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" || $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН"){
                    //Оплата налога УСН || Оплата транспортного налога || Оплата земельного налога || Оплата налога на имущество || Страховые взносы за сотрудников
                    if($paymentSubtype[0]->id === 4 ||
                        $paymentSubtype[0]->id === 9 ||
                        $paymentSubtype[0]->id === 5 ||
                        $paymentSubtype[0]->id === 6 ||
                        $paymentSubtype[0]->id === 11
                    ) {
                        return $payment;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ОСНО"){
                    //Оплата НДС || Оплата налога на прибыль || Оплата транспортного налога || Оплата земельного налога || Оплата налога на имущество || Страховые взносы за сотрудников
                    if($paymentSubtype[0]->id === 1 ||
                        $paymentSubtype[0]->id === 7 ||
                        $paymentSubtype[0]->id === 9 ||
                        $paymentSubtype[0]->id === 5 ||
                        $paymentSubtype[0]->id === 6 ||
                        $paymentSubtype[0]->id === 11
                    ) {
                        return $payment;
                    }
                }
            }
            return null;
        }

        function validateClientReports($clientId, $report){
            $client = Client::where('id', $clientId)->first();

            $reportSubtype = $report->reportsSubtypes;

            if($client->type_of_ownership === 'ИП' && ($client->number_of_employees === 0 || $client->number_of_employees === null)){
                if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы)"){
                    //Декларация по УСН у ИП
                    if($reportSubtype[0]->id === 10){
                        return $report;
                    }
                }
                if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" || $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН"){
                    //Декларация по УСН у ИП
                    if($reportSubtype[0]->id === 10){
                        return $report;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ОСНО"){
                    //НДС || 3-НДФЛ
                    if($reportSubtype[0]->id === 13 || $reportSubtype[0]->id === 3){
                        return $report;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ОСНО + ПСН"){
                    //НДС || 3-НДФЛ
                    if($reportSubtype[0]->id === 13 || $reportSubtype[0]->id === 3){
                        return $report;
                    }
                }
            }

            if($client->type_of_ownership === 'ИП' && $client->number_of_employees >= 1) {
                if ($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы)" ||
                    $client->typeOfTaxes[0]->name === "УСН (Доходы)" ||
                    $client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" ||
                    $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН") {
                    //Декларация по УСН у ИП || 2-НДФЛ || 6-НДФЛ || Расчет по страховым взносам || СЗВ-М || СЗВ-СТАЖ || ФСС
                    if ($reportSubtype[0]->id === 10 ||
                        $reportSubtype[0]->id === 2 ||
                        $reportSubtype[0]->id === 4 ||
                        $reportSubtype[0]->id === 15 ||
                        $reportSubtype[0]->id === 16 ||
                        $reportSubtype[0]->id === 17 ||
                        $reportSubtype[0]->id === 18
                    ) {
                        return $report;
                    }
                }
                if ($client->typeOfTaxes[0]->name === "ОСНО" || $client->typeOfTaxes[0]->name === "ОСНО + ПСН") {
                    //НДС || 3-НДФЛ || 2-НДФЛ || 6-НДФЛ || Расчет по страховым взносам || СЗВ-М || СЗВ-СТАЖ || ФСС
                    if ($reportSubtype[0]->id === 13 ||
                        $reportSubtype[0]->id === 3 ||
                        $reportSubtype[0]->id === 2 ||
                        $reportSubtype[0]->id === 4 ||
                        $reportSubtype[0]->id === 15 ||
                        $reportSubtype[0]->id === 16 ||
                        $reportSubtype[0]->id === 17 ||
                        $reportSubtype[0]->id === 18
                    ) {
                        return $report;
                    }
                }
            }
            if($client->type_of_ownership === 'ООО'){
                if($client->typeOfTaxes[0]->name === "УСН (Доходы - расходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы)" || $client->typeOfTaxes[0]->name === "УСН (Доходы - расходы) + ПСН" || $client->typeOfTaxes[0]->name === "УСН (Доходы) + ПСН"){
                    //Декларация по УСН у ООО || Бухотчетоность
                    if($reportSubtype[0]->id === 11 ||
                        $reportSubtype[0]->id === 5||
                        $reportSubtype[0]->id === 2 ||
                        $reportSubtype[0]->id === 4 ||
                        $reportSubtype[0]->id === 15 ||
                        $reportSubtype[0]->id === 16 ||
                        $reportSubtype[0]->id === 17 ||
                        $reportSubtype[0]->id === 18) {
                        return $report;
                    }
                }
                if($client->typeOfTaxes[0]->name === "ОСНО"){
                    //НДС || Декларация по прибыли || Бухотчетоность

                    if($reportSubtype[0]->id === 13 ||
                        $reportSubtype[0]->id === 9 ||
                        $reportSubtype[0]->id === 5 ||
                        $reportSubtype[0]->id === 2 ||
                        $reportSubtype[0]->id === 4 ||
                        $reportSubtype[0]->id === 15 ||
                        $reportSubtype[0]->id === 16 ||
                        $reportSubtype[0]->id === 17 ||
                        $reportSubtype[0]->id === 18) {
                        return $report;
                    }
                }
            }
            return null;
        }

        //идем по каждому клиенту в списке
        foreach ($clientsList as $clientKey => $client){

            $this->optimizeClientEvents($client->payments());
            $this->optimizeClientEvents($client->reports());
            $clientPaymentList = [];
            foreach ($paymentsList as $paymentKey => $payment){
                $clientPaymentList[] = validateClientPayments($client->id, $payment);
            }
            $flist = [];
            foreach(array_filter($clientPaymentList, function($v) { return !is_null($v); }) as $key => $value) {
                $flist[$value->id] = ['comment' => $value->payment_name, 'activity' => $value->active];
            }


            if(isset($flist)){
                $client->payments()->syncWithoutDetaching($flist);
            }

            $clientReportList = [];
            foreach ($reportsList as $reportKey => $report){
                $clientReportList[] = validateClientReports($client->id, $report);
            }
            $rlist = [];
            foreach(array_filter($clientReportList, function($v) { return !is_null($v); }) as $key => $value) {
                $rlist[$value->id] = ['comment' => $value->report_name, 'activity' => $value->active];
            }
            if(isset($rlist)){
                $client->reports()->syncWithoutDetaching($rlist);
            }

        }
        Toast::info('Синхронизация завершена');
    }
}
