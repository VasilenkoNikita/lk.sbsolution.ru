<?php

namespace App\Observers;

use App\Models\EconomicActivities;
use App\Models\History;
use App\Traits\TracksHistoryTrait;
use Illuminate\Support\Facades\Auth;

class EconomicActivitiesObserver
{

    use TracksHistoryTrait;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public $tableName = 'Разделы ОКВЭД';

    public $dictonary = [
        'type_economic_activity' => 'Наименование раздела ОКВЭД',
        'code_economic_activity' => 'Код раздела ОКВЭД',
        'section_description' => 'Описание раздела',
    ];

    /**
     * Handle the EconomicActivities "created" event.
     *
     * @param  \App\Models\EconomicActivities  $economicActivities
     * @return void
     */
    public function created(EconomicActivities $economicActivities)
    {
        $this->track($economicActivities, function ($value, $field) {
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
     * Handle the EconomicActivities "updated" event.
     *
     * @param  \App\Models\EconomicActivities  $economicActivities
     * @return void
     */
    public function updating(EconomicActivities $economicActivities)
    {
        $this->track($economicActivities, function ($value, $field) {
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
     * Handle the EconomicActivities "deleted" event.
     *
     * @param  \App\Models\EconomicActivities  $economicActivities
     * @return void
     */
    public function deleted(EconomicActivities $economicActivities)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $economicActivities->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удален раздел ОКВЭД \"{$economicActivities->code_economic_activity}\"",
        ]);
    }

    /**
     * Handle the EconomicActivities "restored" event.
     *
     * @param  \App\Models\EconomicActivities  $economicActivities
     * @return void
     */
    public function restored(EconomicActivities $economicActivities)
    {
        //
    }

    /**
     * Handle the EconomicActivities "force deleted" event.
     *
     * @param  \App\Models\EconomicActivities  $economicActivities
     * @return void
     */
    public function forceDeleted(EconomicActivities $economicActivities)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $economicActivities->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'FDELETE',
            'body'            => "Удален раздел ОКВЭД \"{$economicActivities->code_economic_activity}\"",
        ]);
    }
}
