<?php

namespace App\Observers;

use App\Models\ClientPatent;
use App\Models\History;
use App\Traits\TracksHistoryTrait;
use Illuminate\Support\Facades\Auth;

class ClientPatentObserver
{

    use TracksHistoryTrait;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public $tableName = 'Патенты';

    public $dictonary = [
        'client_id' => 'ID клиента',
        'patent_number' => 'Номер патента',
        'type_of_company' => 'Вид деятельности',
        'point_address' => 'Адрес точки',
        'patent_start_date' => 'Дата начала действия патента',
        'patent_end_date' => 'Дата окончания действия патента',
        'first_date_of_payment' => 'Дата оплаты первого платежа',
        'second_date_of_payment' => 'Дата оплаты второго платежа',
        'patent_comment' => 'Комментарий',
    ];

    /**
     * Handle the ClientPatent "created" event.
     *
     * @param  \App\Models\ClientPatent  $clientPatent
     * @return void
     */
    public function created(ClientPatent $clientPatent)
    {

        $this->track($clientPatent, function ($value, $field) {
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
     * Handle the ClientPatent "updated" event.
     *
     * @param  \App\Models\ClientPatent  $clientPatent
     * @return void
     */
    public function updating(ClientPatent $clientPatent)
    {
        $this->track($clientPatent, function ($value, $field) {
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
     * Handle the ClientPatent "deleted" event.
     *
     * @param  \App\Models\ClientPatent  $clientPatent
     * @return void
     */
    public function deleted(ClientPatent $clientPatent)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientPatent->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удален патент клиента \"{$clientPatent->name}\"",
        ]);
    }

    /**
     * Handle the ClientPatent "restored" event.
     *
     * @param  \App\Models\ClientPatent  $clientPatent
     * @return void
     */
    public function restored(ClientPatent $clientPatent)
    {
        //
    }

    /**
     * Handle the ClientPatent "force deleted" event.
     *
     * @param  \App\Models\ClientPatent  $clientPatent
     * @return void
     */
    public function forceDeleted(ClientPatent $clientPatent)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientPatent->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'FDELETE',
            'body'            => "Удален патент клиент \"{$clientPatent->name}\"",
        ]);
    }
}
