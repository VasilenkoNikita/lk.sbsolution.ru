<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\History;
use App\Traits\TracksHistoryTrait;
use Illuminate\Support\Facades\Auth;

class PaymentObserver
{

    use TracksHistoryTrait;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public $tableName = 'Оплаты';

    public $dictonary = [
        'payment_name' => 'Наименование оплаты',
        'payment_date' => 'Дата оплаты',
        'type' => 'Тип оплаты',
        'subtype' => 'Вид оплаты',
        'active' => 'Активность',

    ];

    /**
     * Handle the Payment "created" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function created(Payment $payment)
    {
        $this->track($payment, function ($value, $field) {
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
     * Handle the Payment "updated" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function updating(Payment $payment)
    {
        $this->track($payment, function ($value, $field) {
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
     * Handle the Payment "deleted" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function deleted(Payment $payment)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $payment->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'DELETE',
            'body'            => "Удалена оплата \"{$payment->payment_name}\"",
        ]);
    }

    /**
     * Handle the Payment "restored" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function restored(Payment $payment)
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function forceDeleted(Payment $payment)
    {
        History::create([
            'reference_table' => $this->tableName,
            'reference_id'    => $payment->id,
            'user_id'         => Auth::user()->id,
            'change_type'     => 'FDELETE',
            'body'            => "Удалена оплата \"{$payment->payment_name}\"",
        ]);
    }
}
