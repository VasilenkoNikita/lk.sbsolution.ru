<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Manual extends Model
{
    use HasFactory, AsSource, Filterable;

    protected $table = 'manuals';

    protected $fillable = [
        'id',
        'section',
        'code',
        'header',
        'manual',
        'version',
        'created_at',
        'updated_at',
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */

    protected $allowedSorts = [
        'section',
        'code',
        'version',
    ];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = [
        'section',
    ];
}
