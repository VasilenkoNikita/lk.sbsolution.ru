<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ClientPlaceOfBusiness extends Model
{
    use AsSource, Filterable;

    public $table = 'clients_place_of_business';

    public $fillable = [
        'city',
        'region',
        'tax_registrar'
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */

    protected $allowedSorts = [
        'city',
        'region',
        'tax_registrar'
    ];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = [
        'region',
        'tax_registrar'
    ];

    public function clients(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
