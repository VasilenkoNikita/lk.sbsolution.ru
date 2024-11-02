<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class SectionActivity extends Model
{
    use AsSource, Filterable;

    public $table = 'sections_of_economic_activity';

    /**
     * @var array
     */
    protected $fillable = [
        'section_name',
        'section_code',
        'section_description'
    ];

	/**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */

    protected $allowedSorts = [
        'section_name',
        'section_code',
        'section_description'
    ];

	/**
	 * Name of columns to which http filter can be applied
	 *
	 * @var array
	 */
	protected $allowedFilters = [
        'section_name',
	];

    public function EcoActivities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EconomicActivities::class);
    }
}
