<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class IssueCategory extends Model
{
	use AsSource, Filterable;

    public $table = 'issue_categories';
    public $timestamps = false;

    public $fillable = [
        'project_id',
        'name',
        'assigned_to_id'
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
    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'projects', 'project_id', 'id');
    }
}
