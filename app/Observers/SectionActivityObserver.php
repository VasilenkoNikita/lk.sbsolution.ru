<?php

namespace App\Observers;

use App\Models\SectionActivity;
use App\Models\History;
use App\Traits\TracksHistoryTrait;
use Illuminate\Support\Facades\Auth;

class SectionActivityObserver
{

    use TracksHistoryTrait;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public $tableName = 'Секции ОКВЭД';

    public $dictonary = [
        'section_name' => 'Наименование раздела',
        'section_code' => 'Код раздела',
        'section_description' => 'Описание раздела',
    ];

    /**
     * Handle the SectionActivity "created" event.
     *
     * @param  \App\Models\SectionActivity  $sectionActivity
     * @return void
     */
    public function created(SectionActivity $sectionActivity)
    {
        $this->track($sectionActivity, function ($value, $field) {
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
     * Handle the SectionActivity "updated" event.
     *
     * @param  \App\Models\SectionActivity  $sectionActivity
     * @return void
     */
    public function updating(SectionActivity $sectionActivity)
    {
        $this->track($sectionActivity, function ($value, $field) {
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
     * Handle the SectionActivity "deleted" event.
     *
     * @param  \App\Models\SectionActivity  $sectionActivity
     * @return void
     */
    public function deleted(SectionActivity $sectionActivity)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $sectionActivity->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удалена секция \"{$sectionActivity->section_name}\"",
        ]);
    }

    /**
     * Handle the SectionActivity "restored" event.
     *
     * @param  \App\Models\SectionActivity  $sectionActivity
     * @return void
     */
    public function restored(SectionActivity $sectionActivity)
    {
        //
    }

    /**
     * Handle the SectionActivity "force deleted" event.
     *
     * @param  \App\Models\SectionActivity  $sectionActivity
     * @return void
     */
    public function forceDeleted(SectionActivity $sectionActivity)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $sectionActivity->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'FDELETE',
            'body'            => "Удалена секция \"{$sectionActivity->section_name}\"",
        ]);
    }
}
