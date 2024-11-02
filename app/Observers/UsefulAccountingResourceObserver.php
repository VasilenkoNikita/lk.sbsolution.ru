<?php

namespace App\Observers;

use App\Models\UsefulAccountingResource;
use App\Models\History;
use App\Traits\TracksHistoryTrait;
use Illuminate\Support\Facades\Auth;

class UsefulAccountingResourceObserver
{

    use TracksHistoryTrait;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public $tableName = 'Полезные ресурсы';

    public $dictonary = [
        'resource_name' => 'Имя ресурса',
        'resource_link' => 'Ссылка на ресурс',
    ];

    /**
     * Handle the UsefulAccountingResource "created" event.
     *
     * @param  \App\Models\UsefulAccountingResource  $usefulAccountingResource
     * @return void
     */
    public function created(UsefulAccountingResource $usefulAccountingResource)
    {
        $this->track($usefulAccountingResource, function ($value, $field) {
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
     * Handle the UsefulAccountingResource "updated" event.
     *
     * @param  \App\Models\UsefulAccountingResource  $usefulAccountingResource
     * @return void
     */
    public function updating(UsefulAccountingResource $usefulAccountingResource)
    {
        $this->track($usefulAccountingResource, function ($value, $field) {
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
     * Handle the UsefulAccountingResource "deleted" event.
     *
     * @param  \App\Models\UsefulAccountingResource  $usefulAccountingResource
     * @return void
     */
    public function deleted(UsefulAccountingResource $usefulAccountingResource)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $usefulAccountingResource->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удалена запись о ресурсе \"{$usefulAccountingResource->resource_name}\"",
        ]);
    }

    /**
     * Handle the UsefulAccountingResource "restored" event.
     *
     * @param  \App\Models\UsefulAccountingResource  $usefulAccountingResource
     * @return void
     */
    public function restored(UsefulAccountingResource $usefulAccountingResource)
    {
        //
    }

    /**
     * Handle the UsefulAccountingResource "force deleted" event.
     *
     * @param  \App\Models\UsefulAccountingResource  $usefulAccountingResource
     * @return void
     */
    public function forceDeleted(UsefulAccountingResource $usefulAccountingResource)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $usefulAccountingResource->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'FDELETE',
            'body'            => "Удалена запись о ресурсе \"{$usefulAccountingResource->resource_name}\"",
        ]);
    }
}
