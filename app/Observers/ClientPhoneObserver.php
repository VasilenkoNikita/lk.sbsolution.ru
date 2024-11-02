<?php

namespace App\Observers;

use App\Models\ClientPhone;
use App\Models\History;
use App\Traits\TracksHistoryTrait;
use Illuminate\Support\Facades\Auth;

class ClientPhoneObserver
{

    use TracksHistoryTrait;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public $tableName = 'Телефоны клиента';

    public $dictonary = [
        'client_id' => 'ID клиента',
        'phone' => 'Телефон клиента',
        'additional_information' => 'Дополнительная информация',
    ];

    /**
     * Handle the ClientPhone "created" event.
     *
     * @param  \App\Models\ClientPhone  $clientPhone
     * @return void
     */
    public function created(ClientPhone $clientPhone)
    {
        $this->track($clientPhone, function ($value, $field) {
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
     * Handle the ClientPhone "updated" event.
     *
     * @param  \App\Models\ClientPhone  $clientPhone
     * @return void
     */
    public function updating(ClientPhone $clientPhone)
    {
        $this->track($clientPhone, function ($value, $field) {
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
     * Handle the ClientPhone "deleted" event.
     *
     * @param  \App\Models\ClientPhone  $clientPhone
     * @return void
     */
    public function deleted(ClientPhone $clientPhone)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientPhone->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удален телефон \"{$clientPhone->phone}\"",
        ]);
    }

    /**
     * Handle the ClientPhone "restored" event.
     *
     * @param  \App\Models\ClientPhone  $clientPhone
     * @return void
     */
    public function restored(ClientPhone $clientPhone)
    {
        //
    }

    /**
     * Handle the ClientPhone "force deleted" event.
     *
     * @param  \App\Models\ClientPhone  $clientPhone
     * @return void
     */
    public function forceDeleted(ClientPhone $clientPhone)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientPhone->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'FDELETE',
            'body'            => "Удален телефон \"{$clientPhone->phone}\"",
        ]);
    }
}
