<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Enumeration extends Model
{
    use AsSource, Filterable;

    public $table = 'enumerations';
    public $timestamps = false;
    public $fillable = [
        'name',
        'position',
        'is_default',
        'type',
        'active',
        'project_id',
        'parent_id'
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'name'
    ];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = [
        'name'
    ];
}
