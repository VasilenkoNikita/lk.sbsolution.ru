<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ClientPatent extends Model
{
    use AsSource, Filterable;

    public $table = 'clients_patents';

    public $fillable = [
        'id',
        'patent_number',
        'type_of_company',
        'patent_code',
        'point_address',
        'patent_start_date',
        'patent_end_date',
        'first_date_of_payment',
        'second_date_of_payment',
        'patent_comment',
        'patent_status'
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */

    protected $allowedSorts = [
        'patent_number',
        'type_of_company',
        'patent_code',
        'point_address',
        'patent_start_date',
        'patent_end_date',
        'first_date_of_payment',
        'second_date_of_payment',
        'patent_comment',
        'patent_status'
    ];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = [
        'patent_number'
    ];

    public function getPatentStartDateAttribute($value)
    {
        if (!is_null($value)){
            return date("d-m-Y", strtotime($value));
        }

        return null;
    }

    public function getPatentEndDateAttribute($value)
    {
        if (!is_null($value)){
            return date("d-m-Y", strtotime($value));
        }

        return null;
    }

    public function getFirstDateOfPaymentAttribute($value)
    {
        if (!is_null($value)){
            return date("d-m-Y", strtotime($value));
        }

        return null;
    }

    public function getSecondDateOfPaymentAttribute($value)
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
