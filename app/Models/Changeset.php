<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Changeset extends Model
{
	use AsSource, Filterable;

    public $table = 'changesets';
    public $timestamps = false;
    public $fillable = [
        'repository_id',
        'revision',
        'committer',
        'commited_on',
        'comments',
        'commit_date',
        'scmid',
        'user_id'
    ];

	/**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */

    protected $allowedSorts = [
        'committer'
    ];

	/**
	 * Name of columns to which http filter can be applied
	 *
	 * @var array
	 */
	protected $allowedFilters = [
	    'committer'
	];
}
