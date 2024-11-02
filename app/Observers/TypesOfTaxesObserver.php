<?php

namespace App\Observers;

use App\Models\TypesOfTaxes;
use App\Models\History;
use App\Traits\TracksHistoryTrait;
use Illuminate\Support\Facades\Auth;

class TypesOfTaxesObserver
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
        'name' => 'Наименование типа налога',
        'alias' => 'Альтернативное название СНО',
        'types_of_tax_active' => 'Статус активности типа налога',
    ];

    /**
     * Handle the TypesOfTaxes "created" event.
     *
     * @param  \App\Models\TypesOfTaxes  $typesOfTaxes
     * @return void
     */
    public function created(TypesOfTaxes $typesOfTaxes)
    {
        $this->track($typesOfTaxes, function ($value, $field) {
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
     * Handle the TypesOfTaxes "updated" event.
     *
     * @param  \App\Models\TypesOfTaxes  $typesOfTaxes
     * @return void
     */
    public function updating(TypesOfTaxes $typesOfTaxes)
    {
        $this->track($typesOfTaxes, function ($value, $field) {
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
     * Handle the TypesOfTaxes "deleted" event.
     *
     * @param  \App\Models\TypesOfTaxes  $typesOfTaxes
     * @return void
     */
    public function deleted(TypesOfTaxes $typesOfTaxes)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $typesOfTaxes->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удалена запись типа налога \"{$typesOfTaxes->name}\"",
        ]);
    }

    /**
     * Handle the TypesOfTaxes "restored" event.
     *
     * @param  \App\Models\TypesOfTaxes  $typesOfTaxes
     * @return void
     */
    public function restored(TypesOfTaxes $typesOfTaxes)
    {
        //
    }

    /**
     * Handle the TypesOfTaxes "force deleted" event.
     *
     * @param  \App\Models\TypesOfTaxes  $typesOfTaxes
     * @return void
     */
    public function forceDeleted(TypesOfTaxes $typesOfTaxes)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $typesOfTaxes->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'FDELETE',
            'body'            => "Удалена запись типа налога \"{$typesOfTaxes->name}\"",
        ]);
    }
}
