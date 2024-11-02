<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ClientCashDesk extends Model
{
    use AsSource, Filterable;

    public $table = 'clients_cash_desks';

    public $fillable = [
        'name_cash_desks',
        'date_of_cash_processing',
        'comment'
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'name_cash_desks',
        'date_of_cash_processing'
    ];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = [
        'name_cash_desks',
        'date_of_cash_processing'
    ];

    public function getDateOfCashProcessingAttribute($value)
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
