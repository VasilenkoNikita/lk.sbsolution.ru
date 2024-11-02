<?php

namespace App\Observers;

use App\Models\ClientBankStatement;
use App\Models\History;
use App\Traits\TracksHistoryTrait;
use Illuminate\Support\Facades\Auth;

class ClientBankStatementObserver
{

    use TracksHistoryTrait;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public $tableName = 'Банковские выписки';

    public $dictonary = [
        'client_id' => 'ID клиента',
        'bank_name' => 'Наименование банка',
        'account_type' => 'Тип счета',
        'checking_account' => 'Расчетный счет',
        'bank_statement_processing_date' => 'Дата обработки',

    ];

    /**
     * Handle the ClientBankStatement "created" event.
     *
     * @param  \App\Models\ClientBankStatement  $clientBankStatement
     * @return void
     */
    public function created(ClientBankStatement $clientBankStatement)
    {
        $this->track($clientBankStatement, function ($value, $field) {
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
     * Handle the ClientBankStatement "updated" event.
     *
     * @param  \App\Models\ClientBankStatement  $clientBankStatement
     * @return void
     */
    public function updating(ClientBankStatement $clientBankStatement)
    {
        $this->track($clientBankStatement, function ($value, $field) {
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
     * Handle the ClientBankStatement "deleted" event.
     *
     * @param  \App\Models\ClientBankStatement  $clientBankStatement
     * @return void
     */
    public function deleted(ClientBankStatement $clientBankStatement)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientBankStatement->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удален банковский счет \"{$clientBankStatement->checking_account}\"",
        ]);
    }

    /**
     * Handle the ClientBankStatement "restored" event.
     *
     * @param  \App\Models\ClientBankStatement  $clientBankStatement
     * @return void
     */
    public function restored(ClientBankStatement $clientBankStatement)
    {
        //
    }

    /**
     * Handle the ClientBankStatement "force deleted" event.
     *
     * @param  \App\Models\ClientBankStatement  $clientBankStatement
     * @return void
     */
    public function forceDeleted(ClientBankStatement $clientBankStatement)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $clientBankStatement->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'FDELETE',
            'body'            => "Удален банковский счет \"{$clientBankStatement->checking_account}\"",
        ]);
    }
}
