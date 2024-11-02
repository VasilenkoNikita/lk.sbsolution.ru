<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ClientSalaries extends Model
{
    use AsSource, Filterable;

    public $table = 'clients_salaries';

    public $fillable = [
        'client_id',
        'prepayment_day',
        'payment_day',
        'month',
        'status',
        'prepayment_status',
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */

    protected $allowedSorts = [
        'month',
        'status',
        'prepayment_status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'month' => 'datetime:Y-m',
    ];

    public function clients(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
