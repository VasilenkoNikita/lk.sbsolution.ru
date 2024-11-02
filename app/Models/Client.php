<?php

declare(strict_types=1);

namespace App\Models;

use App\Jobs\ProcessCheckingClientPatentsStatus;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use App\Model\MyBaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Client extends MyBaseModel
{
     use AsSource, Attachable, Filterable;

    /**
     * @var string
     */
    protected $table = 'clients';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'type_of_ownership',
        'organization',
        'description',
		'type_of_company',
        'type_of_company_actual',
		'region',
        'reporting_system',
        'description',
        'client_active',
        'start_date',
        'client_transfer_date',
        'primary_documents',
        'contracting_documents',
        'features_of_the_type_of_accounting',
        'features_of_calculating_taxes',
        'preliminary_tax_calculation',
        'payment_procedure',
        'salary_payment_date',
        'advance_payment_date',
        'number_of_employees',
        'comment_of_employees',
        'other_calculations_phys_clients',
        'loans',
        'bank_operations',
        'cashbox',
        'additional_information',
        'current_troubles',
        'services_provided',
        'inn',
        'history_cno',
        'accountant',
        'assistant',
        'certificate',
        'certificate_end_date',
        'tax_registrar',
        'keeping_accounting',
        'comment',
		'rate_comment',
    ];

	/**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */

    protected $allowedSorts = [
        'id',
        'name',
		'type_of_ownership',
		'organization',
        'created_at',
        'updated_at',
		'type_of_company',
        'type_of_company_actual',
		'region',
        'reporting_system',
        'description',
        'client_active',
        'start_date',
        'client_transfer_date',
        'primary_documents',
        'contracting_documents',
        'features_of_the_type_of_accounting',
        'features_of_calculating_taxes',
        'preliminary_tax_calculation',
        'payment_procedure',
        'salary_payment_date',
        'advance_payment_date',
        'number_of_employees',
        'comment_of_employees',
        'other_calculations_phys_clients',
        'loans',
        'bank_operations',
        'cashbox',
        'additional_information',
        'current_troubles',
        'services_provided',
        'inn',
        'history_cno',
        'accountant',
        'assistant',
        'certificate',
        'certificate_end_date',
        'tax_registrar',
        'keeping_accounting'
    ];

	/**
	 * Name of columns to which http filter can be applied
	 *
	 * @var array
	 */
	protected $allowedFilters = [
		'name',
		'organization',
		'type_of_company',
		'reporting_system',
        'accountant',
        'assistant',
        'inn',
        'keeping_accounting'
	];

    public function getCertDateAttribute()
    {
        if (!is_null($this->certificate_end_date)) {
            return date("d-m-Y", strtotime($this->certificate_end_date));
        }

        return null;
    }

    public function getStartDateAttribute($value)
    {
        if (!is_null($value)){
            return date("d-m-Y", strtotime($value));
        }

        return null;
    }

    public function getClientTransferDateAttribute($value)
    {
        if (!is_null($value)){
            return date("d-m-Y", strtotime($value));
        }

        return null;
    }

	public function groups(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
		return $this->belongsToMany(Group::class, 'clients_groups', 'client_id', 'group_id');
	}

	public function rates(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
		return $this->belongsToMany(Rate::class, 'clients_rates', 'client_id', 'rate_id');
	}

    public function typeOfTaxes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(TypesOfTaxes::class, 'clients_type_of_tax', 'client_id', 'type_of_tax_id');
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'clients_payments', 'client_id', 'payment_id')->withPivot('comment', 'added_by_user')->orderby('payment_date');
    }

    public function reports(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Report::class, 'clients_reports', 'client_id', 'report_id')->withPivot('comment', 'added_by_user')->orderby('report_date');
    }

    public function phones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientPhone::class);
    }

    public function emails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientEmail::class);
    }

    public function patents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientPatent::class);
    }

    public function bankStatements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientBankStatement::class);
    }

    public function marketplaces(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientMarketplace::class);
    }

    public function salaries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientSalaries::class);
    }

    public function accesses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientAccess::class);
    }

    public function cashDesks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientCashDesk::class);
    }

    public function placesBusinesses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientPlaceOfBusiness::class);
    }

    public function events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientReporting::class);
    }

    public function history(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(History::class, 'clients', 'reference_table', 'reference_id');
    }

    public function userColors(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(UserColor::class, 'colors_clients', 'client_id', 'color_id')
            ->where('users_colors.user_id',  Auth::user()->id)
            ->whereRaw('colors_clients.color_id',  'users_colors.id');
    }

    public function addIds($data, $source){
        if(!is_null($source)) {
            foreach ($source as $key => $s) {
                isset($data[$key]) ? $data[$key] = ['id' => $s->id] + $data[$key] : false;
            }
        }
        return $data;
    }

    public function validatePatentsDeadlines()
    {
        $user = User::find(1);
        $patents = $this->patents()->get();
        foreach ($patents as $patent){


        //$patent = $this->patents()->where('id', $patentId)->first();

        $patentsDeadlinesData = [];
        $patentsDeadlinesData['patent_number'] = $patent->patent_number ?? null;
        $patentsDeadlinesData['patent_end_date'] = $patent->patent_end_date ?? null;
        $patentsDeadlinesData['first_date_of_payment'] = $patent->first_date_of_payment ?? null;
        $patentsDeadlinesData['second_date_of_payment'] = $patent->second_date_of_payment ?? null;
        $patentsDeadlinesData['patentDeadline'] = false;
        $patentsDeadlinesData['patentDeadlineLeft'] = false;
        $patentsDeadlinesData['patentDaysLeft'] = 0;

            if($patentsDeadlinesData['patent_end_date'] && !is_null($patentsDeadlinesData['patent_end_date'])) {
                //если текущая дата проверки раньше чем дата окончания действия патента
                if (new DateTime() < new DateTime($patentsDeadlinesData['patent_end_date'])) {
                    //если разница между текущей датой и датой окончания патента меньше 20 то отправляем уведомление о истечении
                    if (date_diff(new DateTime(), new DateTime($patentsDeadlinesData['patent_end_date']))->days <= 20) {
                        $patentsDeadlinesData['patentDeadline'] = true;
                        Log::info("Отправка уведомления для пользователя $user->name, по клиенту $this->name, paten истекает");
                        ProcessCheckingClientPatentsStatus::dispatch(
                            $user,
                            $this,
                            date_diff(new DateTime(), new DateTime($patentsDeadlinesData['patent_end_date']))->days,
                            "Истекает",
                            $patentsDeadlinesData['patent_end_date'],
                            "Patent",
                            md5($this->id . $user->id . $patentsDeadlinesData['patent_end_date'] . 'patent'))->delay(now()->addSeconds(30));
                        Log::info("Сделано!");
                    }
                } else {//если дата проверки превышает дату истечения патента, отправляем уведомление о просрочке
                    $patentsDeadlinesData['patentDaysLeft'] = date_diff(new DateTime(), new DateTime($patentsDeadlinesData['patent_end_date']))->days;
                    $patentsDeadlinesData['patentDeadlineLeft'] = true;
                    Log::info("Отправка уведомления для пользователя $user->name, по клиенту $this->name, paten истек");
                    ProcessCheckingClientPatentsStatus::dispatch(
                        $user,
                        $this,
                        date_diff(new DateTime(), new DateTime($patentsDeadlinesData['patent_end_date']))->days,
                        "Истек",
                        $patentsDeadlinesData['patent_end_date'],
                        "Patent",
                        md5($this->id . $user->id . $patentsDeadlinesData['patent_end_date'] . 'patent'))->delay(now()->addSeconds(30));
                    Log::info("Сделано!");
                }
            }
                //dd($patentsDeadlinesData);
               // return $patentsDeadlinesData;
            }

    }


    public function validateSalariesDeadlines($monthNumber = 1): array
    {
        $salariesDeadlinesData = [];
        $salariesDeadlinesData['id'] = null;
        $salariesDeadlinesData['salariesPrepaymentDeadline'] = false;
        $salariesDeadlinesData['salariesPaymentDeadline'] = false;
        $salariesData = null;
        $salariesDeadlinesData['background'] = "";
        $salariesDeadlinesData['month'] = "";
        $salariesDeadlinesData['salariesPrepaymentDate'] = 15;
        $salariesDeadlinesData['salariesPaymentDate'] = 30;
        $salariesDeadlinesData['salariesPrepaymentStatus'] = "";
        $salariesDeadlinesData['salariesPaymentStatus'] = "";


        $currentMonth = $this->salaries()->select(['month AS salariesPrepaymentMonth'])->whereBetween('month', [
            date("Y-m-01 H:i:s", strtotime("first day of -$monthNumber month")),
            date("Y-m-t 23:59:59")
        ])->first();
        $salariesData = $this->salaries()->select(['id','prepayment_day', 'payment_day', 'status', 'prepayment_status', 'month'])
            ->where('month', $currentMonth->salariesPrepaymentMonth ?? date("Y-m-01 00:00:00"))->first();
        $salariesDeadlinesData['id'] = $salariesData->id;
        $salariesDeadlinesData['month'] = $currentMonth->salariesPrepaymentMonth ?? date("Y-m-01 00:00:00");
        $salariesDeadlinesData['salariesPrepaymentDate'] = $salariesData->prepayment_day;
        $salariesDeadlinesData['salariesPaymentDate'] = $salariesData->payment_day ;
        $salariesDeadlinesData['salariesPrepaymentStatus'] = $salariesData->prepayment_status;
        $salariesDeadlinesData['salariesPaymentStatus'] = $salariesData->status;
        $currentDay = (int) date("d");

        //проверка на дедлайн аванса
        if ($salariesDeadlinesData['salariesPrepaymentDate'] - $currentDay <= 3){
            $salariesDeadlinesData['salariesPrepaymentDeadline'] = true;
        }elseif($currentMonth->salariesPrepaymentMonth <  date("Y-m-01 00:00:00")){
            $salariesDeadlinesData['salariesPrepaymentDeadline'] = true;
        }

        //проверка на дедлайн зарплаты
        if ($salariesDeadlinesData['salariesPaymentDate'] - $currentDay <= 3){
            $salariesDeadlinesData['salariesPaymentDeadline'] = true;
        }elseif($currentMonth->salariesPrepaymentMonth < date("Y-m-01 00:00:00")){
            $salariesDeadlinesData['salariesPrepaymentDeadline'] = true;
        }

        if ($salariesDeadlinesData['salariesPrepaymentDeadline'] && ($salariesDeadlinesData['salariesPrepaymentStatus'] !== "Обработано")) {
            $salariesDeadlinesData['background'] = 'warningSalaries'.$monthNumber;
        }elseif ($salariesDeadlinesData['salariesPrepaymentDeadline'] && ($salariesDeadlinesData['salariesPrepaymentStatus'] === "Обработано")){
            $salariesDeadlinesData['background'] = 'successSalaries'.$monthNumber;
        }elseif (!$salariesDeadlinesData['salariesPrepaymentDeadline'] && ($salariesDeadlinesData['salariesPrepaymentStatus'] !== "Обработано") && $salariesDeadlinesData['background'] === ""){
            $salariesDeadlinesData['background'] = 'normalSalaries'.$monthNumber;
        }

        if ($salariesDeadlinesData['salariesPaymentDeadline'] && ($salariesDeadlinesData['salariesPaymentStatus'] !== "Обработано")) {
            $salariesDeadlinesData['background'] = 'warningSalaries'.$monthNumber;
        }elseif ($salariesDeadlinesData['salariesPaymentDeadline'] && ($salariesDeadlinesData['salariesPaymentStatus'] === "Обработано")){
            $salariesDeadlinesData['background'] = 'successSalaries'.$monthNumber;
        }elseif (!$salariesDeadlinesData['salariesPrepaymentDeadline'] && ($salariesDeadlinesData['salariesPrepaymentStatus'] !== "Обработано") && $salariesDeadlinesData['background'] === ""){
            $salariesDeadlinesData['background'] = 'normalSalaries'.$monthNumber;
        }

        return $salariesDeadlinesData;
    }

}
