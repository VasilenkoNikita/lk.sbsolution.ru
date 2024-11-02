<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ClientBankStatement extends Model
{
    use AsSource, Filterable;

    public $table = 'clients_bank_statements';

    public $fillable = [
        'bank_name',
        'account_type',
        'checking_account',
        'bank_statement_processing_date',
        'comment',
        'position',
        'active'
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'bank_name',
        'account_type',
        'checking_account',
        'bank_statement_processing_date'
    ];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = [
        'bank_name',
        'account_type'
    ];

    public function getBankStatementProcessingDateAttribute($value)
    {
        if (!is_null($value)){
            return date("d-m-Y", strtotime($value));
        }

        return null;
    }

    public function clients()
    {
        return $this->belongsTo(Client::class);
    }
}
