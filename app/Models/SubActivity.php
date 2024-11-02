<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class SubActivity extends Model
{
    use AsSource, Filterable;

    public $table = 'sub_activities';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'code',
        'economic_activity_id',
        'description'
    ];

	/**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */

    protected $allowedSorts = [
        'id',
        'name',
        'code',
        'economic_activity_id',
        'description'
    ];

	/**
	 * Name of columns to which http filter can be applied
	 *
	 * @var array
	 */
	protected $allowedFilters = [
        'name',
        'code'
	];

    public function getNameCodeAttribute(): string
    {
        return $this->attributes['code'] . ' - ' . $this->attributes['name'] . '';
    }
}
