<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class History extends Model
{
    use HasFactory, AsSource, Filterable;

    protected $table = 'history';

    protected $fillable = [
        'id',
        'reference_table',
        'reference_id',
        'user_id',
        'change_type',
        'body',
        'created_at',
        'updated_at',
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */

    protected $allowedSorts = [
        'id',
        'reference_table',
        'reference_id',
        'user_id',
        'change_type',
        'body',
        'created_at',
        'updated_at',
    ];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = [
        'reference_table',
        'user_id',
    ];
}
