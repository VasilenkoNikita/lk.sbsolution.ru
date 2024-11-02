<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class IssueStatus extends Model
{
	use AsSource, Filterable;

    public $table = 'issue_statuses';
    public $timestamps = false;

    /**
     * @var array
     */
    public $fillable = [
        'name',
        'is_closed',
        'is_default',
        'position',
        'default_done_ratio'
    ];

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
