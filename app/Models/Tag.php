<?php

declare(strict_types=1);

namespace App\Models;

use Spatie\Tags\Tag as SpatieTag;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Spatie\Tags\HasTags;

class Tag extends SpatieTag
{
    use AsSource, Filterable, HasTags;

    public $fillable = [
        'id',
        'name',
        'slug',
        'type',
        'order_column'
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'slug',
        'type',
        'order_column'
    ];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = [
        'name',
        'type'
    ];
}
