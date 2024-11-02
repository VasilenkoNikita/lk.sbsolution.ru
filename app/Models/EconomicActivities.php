<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class EconomicActivities extends Model
{
    use AsSource, Filterable;

    public $table = 'economic_activities';

    /**
     * @var array
     */
    protected $fillable = [
        'section_description',
        'type_economic_activity',
        'section_economic_activity_id',
        'code_economic_activity'
    ];

	/**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */

    protected $allowedSorts = [
        'section_description',
        'type_economic_activity',
        'section_economic_activity_id',
        'code_economic_activity'
    ];

	/**
	 * Name of columns to which http filter can be applied
	 *
	 * @var array
	 */
	protected $allowedFilters = [
        'type_economic_activity',
	];

    public function SubActivities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SubActivity::class);
    }

    public function section_economic_activity()
    {
        return $this->belongsTo(SectionActivity::class);
    }
}
