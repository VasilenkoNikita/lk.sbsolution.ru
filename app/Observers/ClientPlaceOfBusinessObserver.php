<?php

namespace App\Observers;

use App\Models\ClientPlaceOfBusiness;
use App\Models\History;
use App\Traits\TracksHistoryTrait;
use Illuminate\Support\Facades\Auth;

class ClientPlaceOfBusinessObserver
{

    use TracksHistoryTrait;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public $tableName = 'Места ведения деятельности';

    public $dictonary = [
        'client_id' => 'ID клиента',
        'region' => 'Регион ведения деятельности',
        'tax_registrar' => 'Налоговая регистратор',
    ];

    /**
     * Handle the ClientPlaceOfBusiness "created" event.
     *
     * @param  \App\Models\ClientPlaceOfBusiness  $clientPlaceOfBusiness
     * @return void
     */
    public function created(ClientPlaceOfBusiness $clientPlaceOfBusiness)
    {
        $this->track($clientPlaceOfBusiness, function ($value, $field) {
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
     * Handle the ClientPlaceOfBusiness "updated" event.
     *
     * @param  \App\Models\ClientPlaceOfBusiness  $clientPlaceOfBusiness
     * @return void
     */
    public function updating(ClientPlaceOfBusiness $clientPlaceOfBusiness)
    {
        $this->track($clientPlaceOfBusiness, function ($value, $field) {
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
     * Handle the ClientPlaceOfBusiness "deleted" event.
     *
     * @param  \App\Models\ClientPlaceOfBusiness  $clientPlaceOfBusiness
     * @return void
     */
    public function deleted(ClientPlaceOfBusiness $clientPlaceOfBusiness)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientPlaceOfBusiness->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удалена запись о месте ведения деятельности \"{$clientPlaceOfBusiness->client_id}\"",
        ]);
    }

    /**
     * Handle the ClientPlaceOfBusiness "restored" event.
     *
     * @param  \App\Models\ClientPlaceOfBusiness  $clientPlaceOfBusiness
     * @return void
     */
    public function restored(ClientPlaceOfBusiness $clientPlaceOfBusiness)
    {
        //
    }

    /**
     * Handle the ClientPlaceOfBusiness "force deleted" event.
     *
     * @param  \App\Models\ClientPlaceOfBusiness  $clientPlaceOfBusiness
     * @return void
     */
    public function forceDeleted(ClientPlaceOfBusiness $clientPlaceOfBusiness)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientPlaceOfBusiness->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удалена запись о месте ведения деятельности \"{$clientPlaceOfBusiness->client_id}\"",
        ]);
    }
}
