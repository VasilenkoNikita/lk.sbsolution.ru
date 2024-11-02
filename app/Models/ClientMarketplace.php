<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ClientMarketplace extends Model
{
    use AsSource, Filterable;

    public $table = 'clients_marketplaces';

    public $fillable = [
        'marketplace_name',
        'marketplace_processing_date',
        'comment',
        'activity'
    ];
    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'marketplace_name',
        'marketplace_processing_date',
        'comment',
        'activity'
    ];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = [
        'marketplace_name'
    ];

    public function getMarketplaceProcessingDateAttribute($value)
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
