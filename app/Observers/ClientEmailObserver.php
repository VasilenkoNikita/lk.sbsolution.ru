<?php

namespace App\Observers;

use App\Models\ClientEmail;
use App\Models\History;
use App\Traits\TracksHistoryTrait;
use Illuminate\Support\Facades\Auth;

class ClientEmailObserver
{

    use TracksHistoryTrait;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public $tableName = 'Email-ы клиента';

    public $dictonary = [
        'client_id' => 'ID клиента',
        'email' => 'Email клиента',
        'additional_information' => 'Дополнительная информация',
    ];


    /**
     * Handle the ClientEmail "created" event.
     *
     * @param  \App\Models\ClientEmail  $clientEmail
     * @return void
     */
    public function created(ClientEmail $clientEmail)
    {
        $this->track($clientEmail, function ($value, $field) {
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
     * Handle the ClientEmail "updated" event.
     *
     * @param  \App\Models\ClientEmail  $clientEmail
     * @return void
     */
    public function updating(ClientEmail $clientEmail)
    {
        $this->track($clientEmail, function ($value, $field) {
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
     * Handle the ClientEmail "deleted" event.
     *
     * @param  \App\Models\ClientEmail  $clientEmail
     * @return void
     */
    public function deleted(ClientEmail $clientEmail)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientEmail->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удалена почта \"{$clientEmail->email}\"",
        ]);
    }

    /**
     * Handle the ClientEmail "restored" event.
     *
     * @param  \App\Models\ClientEmail  $clientEmail
     * @return void
     */
    public function restored(ClientEmail $clientEmail)
    {
        //
    }

    /**
     * Handle the ClientEmail "force deleted" event.
     *
     * @param  \App\Models\ClientEmail  $clientEmail
     * @return void
     */
    public function forceDeleted(ClientEmail $clientEmail)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientEmail->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'FDELETE',
            'body'            => "Удалена почта \"{$clientEmail->email}\"",
        ]);
    }
}
