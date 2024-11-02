<?php

namespace App\Observers;

use App\Models\ClientAccess;
use App\Models\History;
use App\Traits\TracksHistoryTrait;
use Illuminate\Support\Facades\Auth;

class ClientAccessObserver
{

    use TracksHistoryTrait;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public $tableName = 'Доступы клиента';

    public $dictonary = [
        'client_id' => 'ID клиента',
        'service_name' => 'Наименование сервиса',
        'service_login' => 'Логин',
        'service_password' => 'Пароль',

    ];

    /**
     * Handle the ClientAccess "created" event.
     *
     * @param  \App\Models\ClientAccess  $clientAccess
     * @return void
     */
    public function created(ClientAccess $clientAccess)
    {
        $this->track($clientAccess, function ($value, $field) {
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
     * Handle the ClientAccess "updated" event.
     *
     * @param  \App\Models\ClientAccess  $clientAccess
     * @return void
     */
    public function updating(ClientAccess $clientAccess)
    {
        $this->track($clientAccess, function ($value, $field) {
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
     * Handle the ClientAccess "deleted" event.
     *
     * @param  \App\Models\ClientAccess  $clientAccess
     * @return void
     */
    public function deleted(ClientAccess $clientAccess)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientAccess->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удален доступ \"{$clientAccess->service_name}\"",
        ]);
    }

    /**
     * Handle the ClientAccess "restored" event.
     *
     * @param  \App\Models\ClientAccess  $clientAccess
     * @return void
     */
    public function restored(ClientAccess $clientAccess)
    {
        //
    }

    /**
     * Handle the ClientAccess "force deleted" event.
     *
     * @param  \App\Models\ClientAccess  $clientAccess
     * @return void
     */
    public function forceDeleted(ClientAccess $clientAccess)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientAccess->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'FDELETE',
            'body'            => "Удален доступ \"{$clientAccess->service_name}\"",
        ]);
    }
}
