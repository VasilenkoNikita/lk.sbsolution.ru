<?php

namespace App\Observers;

use App\Models\SubActivity;
use App\Models\History;
use App\Traits\TracksHistoryTrait;
use Illuminate\Support\Facades\Auth;

class SubActivityObserver
{

    use TracksHistoryTrait;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public $tableName = 'Классы ОКВЭД';

    public $dictonary = [
        'name' => 'Название группировки класса ОКВЭД',
        'code' => 'Код группировки класса ОКВЭД',
        'description' => 'Описание группировки класса ОКВЭД',
    ];

    /**
     * Handle the SubActivity "created" event.
     *
     * @param  \App\Models\SubActivity  $subActivity
     * @return void
     */
    public function created(SubActivity $subActivity)
    {
        $this->track($subActivity, function ($value, $field) {
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
     * Handle the SubActivity "updated" event.
     *
     * @param  \App\Models\SubActivity  $subActivity
     * @return void
     */
    public function updating(SubActivity $subActivity)
    {
        $this->track($subActivity, function ($value, $field) {
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
     * Handle the SubActivity "deleted" event.
     *
     * @param  \App\Models\SubActivity  $subActivity
     * @return void
     */
    public function deleted(SubActivity $subActivity)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $subActivity->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удален класс \"{$subActivity->name}\"",
        ]);
    }

    /**
     * Handle the SubActivity "restored" event.
     *
     * @param  \App\Models\SubActivity  $subActivity
     * @return void
     */
    public function restored(SubActivity $subActivity)
    {
        //
    }

    /**
     * Handle the SubActivity "force deleted" event.
     *
     * @param  \App\Models\SubActivity  $subActivity
     * @return void
     */
    public function forceDeleted(SubActivity $subActivity)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $subActivity->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'FDELETE',
            'body'            => "Удален класс \"{$subActivity->name}\"",
        ]);
    }
}
