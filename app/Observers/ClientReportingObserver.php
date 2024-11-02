<?php

namespace App\Observers;

use App\Models\ClientReporting;
use App\Models\History;
use App\Traits\TracksHistoryTrait;
use Illuminate\Support\Facades\Auth;

class ClientReportingObserver
{

    use TracksHistoryTrait;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public $tableName = 'Отчеты клиентов';

    public $dictonary = [
        'client_id' => 'ID клиента',
        'event_name' => 'Имя события',
        'event_action' => 'Действие по событию',
        'report_date' => 'Дата события',
    ];

    /**
     * Handle the ClientReporting "created" event.
     *
     * @param  \App\Models\ClientReporting  $clientReporting
     * @return void
     */
    public function created(ClientReporting $clientReporting)
    {
        $this->track($clientReporting, function ($value, $field) {
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
     * Handle the ClientReporting "updated" event.
     *
     * @param  \App\Models\ClientReporting  $clientReporting
     * @return void
     */
    public function updated(ClientReporting $clientReporting)
    {
        $this->track($clientReporting, function ($value, $field) {
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
     * Handle the ClientReporting "deleted" event.
     *
     * @param  \App\Models\ClientReporting  $clientReporting
     * @return void
     */
    public function deleted(ClientReporting $clientReporting)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientReporting->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удален отчет \"{$clientReporting->event_name}\"",
        ]);
    }

    /**
     * Handle the ClientReporting "restored" event.
     *
     * @param  \App\Models\ClientReporting  $clientReporting
     * @return void
     */
    public function restored(ClientReporting $clientReporting)
    {
        //
    }

    /**
     * Handle the ClientReporting "force deleted" event.
     *
     * @param  \App\Models\ClientReporting  $clientReporting
     * @return void
     */
    public function forceDeleted(ClientReporting $clientReporting)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientReporting->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'FDELETE',
            'body'            => "Удален отчет \"{$clientReporting->event_name}\"",
        ]);
    }
}
