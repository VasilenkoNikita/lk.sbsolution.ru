<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\History;
use App\Traits\TracksHistoryTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ClientObserver
{

    use TracksHistoryTrait;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public $tableName = 'Клиенты';

    public $dictonary = [
        'name' => 'Имя клиента',
        'type_of_ownership' => 'Форма собственности',
        'organization' => 'Название организации',
        'tax_system' => 'Система налогооблажения',
        'type_of_company' => 'Вид деятельности',
        'region' => 'Регион регистрации',
        'reporting_system' => 'Система сдачи отчетности',
        'description' => 'Краткая заметка о клиенте',
        'client_active' => 'Активность клиента',
        'start_date' => 'Дата начала работы с клиентом',
        'client_transfer_date' => 'Дата передачи клиента внутри фирмы',
        'primary_documents' => 'Первичные документы',
        'contracting_documents' => 'Документы для сторонних контрагентов',
        'features_of_the_type_of_accounting' => 'Особенности вида деятельнсти по учету в компании',
        'features_of_calculating_taxes' => 'Особенности расчета клиента',
        'preliminary_tax_calculation' => 'Предварительный расчет налога',
        'payment_procedure' => 'Порядок уплаты прибыли/ндс/усн',
        'salary_payment_date' => 'Дата выплаты зарплаты',
        'advance_payment_date' => 'Дата выплаты аванса',
        'number_of_employees' => 'Количество сотрудников',
        'comment_of_employees' => 'Комментарий к сотрудникам',
        'other_calculations_phys_clients' => 'Прочие расчеты с физ. лицами',
        'loans' => 'Займы/фин.помощь/лизинг',
        'bank_operations' => 'Банковские операции (выписки)',
        'cashbox' => 'Кассы',
        'additional_information' => 'Дополнительная информация о клиенте',
        'current_troubles' => 'Текущие проблемы',
        'services_provided' => 'Оказываемые услуги',
        'access' => 'Доступы',
        'inn' => 'ИНН компании клиента',
        'history_cno' => 'История смены СНО',
        'accountant' => 'Бухгалтер',
        'assistant' => 'Помощник',
        'certificate' => 'Номер сертификата',
        'certificate_end_date' => 'Дата окончания сертификата',
        'tax_registrar' => 'Налоговая регистратор',
        'keeping_accounting' => 'Ведение учета'
    ];

    /**
     * Handle the Client "created" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function created(Client $client)
    {
        $this->track($client, function ($value, $field) {
            foreach($this->dictonary as $k => $val){
                if ($field === $k) {
                    $field = $val;
                }
            }

            return [
                'change_type' => 'INSERT',
                'body'        => "Создано поле \"{$field}\". {$value}",
            ];
        }, $this->tableName);
    }

    /**
     * Handle the Client "updated" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function updating(Client $client)
    {
        $this->track($client, function ($value, $field) {
            foreach($this->dictonary as $k => $val){
                if ($field === $k) {
                    $field = $val;
                }
            }

            return [
                'change_type' => 'UPDATE',
                'body' => "Обновлено поле \"{$field}\" <br> {$value}",
            ];
        }, $this->tableName);
    }

    /**
     * Handle the Client "deleted" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function deleted(Client $client)
    {
        History::create([
                'reference_table' => $this->tableName,
                'reference_id'    => $client->id,
                'user_id'         => Auth::user()->id,
                'change_type'     => 'DELETE',
                'body'            => "Удален клиент \"{$client->name}\"",
            ]);
    }

    /**
     * Handle the Client "restored" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function restored(Client $client)
    {
        //
    }

    /**
     * Handle the Client "force deleted" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function forceDeleted(Client $client)
    {
        //
    }
}
