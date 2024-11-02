<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ClientReporting extends Model
{
    use AsSource, Filterable;

    public $table = 'clients_reporting';

    public $fillable = [
        'client_id',
        'event_id',
        'event_type',
        'status',
        'event_name',
        'event_action',
        'report_date'
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'event_name',
        'event_action',
        'report_date'
    ];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = [
        'event_name'
    ];

    public function clients(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function userColors(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(UserColor::class, 'colors_reportings', 'color_id', 'reporting_id');
    }
}
