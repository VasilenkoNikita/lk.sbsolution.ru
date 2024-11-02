<?php

namespace App\Observers;

use App\Models\ClientCashDesk;
use App\Models\History;
use App\Traits\TracksHistoryTrait;
use Illuminate\Support\Facades\Auth;

class ClientCashDeskObserver
{

    use TracksHistoryTrait;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public $tableName = 'Кассы клиента';

    public $dictonary = [
        'client_id' => 'ID клиента',
        'name_cash_desks' => 'Касса',
        'date_of_cash_processing' => 'Дата обработки наличных',
    ];

    /**
     * Handle the ClientCashDesk "created" event.
     *
     * @param  \App\Models\ClientCashDesk  $clientCashDesk
     * @return void
     */
    public function created(ClientCashDesk $clientCashDesk)
    {
        $this->track($clientCashDesk, function ($value, $field) {
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
     * Handle the ClientCashDesk "updated" event.
     *
     * @param  \App\Models\ClientCashDesk  $clientCashDesk
     * @return void
     */
    public function updating(ClientCashDesk $clientCashDesk)
    {
        $this->track($clientCashDesk, function ($value, $field) {
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
     * Handle the ClientCashDesk "deleted" event.
     *
     * @param  \App\Models\ClientCashDesk  $clientCashDesk
     * @return void
     */
    public function deleted(ClientCashDesk $clientCashDesk)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientCashDesk->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удалена касса \"{$clientCashDesk->name_cash_desks}\"",
        ]);
    }

    /**
     * Handle the ClientCashDesk "restored" event.
     *
     * @param  \App\Models\ClientCashDesk  $clientCashDesk
     * @return void
     */
    public function restored(ClientCashDesk $clientCashDesk)
    {
        //
    }

    /**
     * Handle the ClientCashDesk "force deleted" event.
     *
     * @param  \App\Models\ClientCashDesk  $clientCashDesk
     * @return void
     */
    public function forceDeleted(ClientCashDesk $clientCashDesk)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientCashDesk->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удалена касса \"{$clientCashDesk->name_cash_desks}\"",
        ]);
    }
}
