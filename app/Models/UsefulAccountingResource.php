<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;


class UsefulAccountingResource extends Model
{
    use AsSource, Filterable, HasTags;

    public $table = 'useful_accounting_resources';
    public $fillable = [
        'resource_name',
        'resource_link',
        'resource_status',
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'resource_name',
        'resource_link',
        'resource_status',
    ];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = [
        'resource_name',
        'resource_link',
    ];
}
