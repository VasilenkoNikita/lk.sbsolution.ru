<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use App\Models\IssueStatus;
use App\Models\Enumeration;
use App\Models\IssueCategory;
use App\Models\Project;
use App\Models\Changeset;
use App\Models\User;

class Issue extends Model
{
	use AsSource, Filterable;

    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';

    public $table = 'issues';
    public $fillable = [
        'tracker_id',
        'project_id',
        'subject',
        'description',
        'due_date',
        'category_id',
        'status_id',
        'assigned_to_id',
        'priority_id',
        'fixed_version_id',
        'author_id',
        'lock_version',
        'created_on',
        'updated_on',
        'start_date',
        'done_ratio',
        'estimated_hours',
        'parent_id',
        'root_id',
        'lft',
        'rgt'
    ];

	/**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */

    protected $allowedSorts = [
        'tracker_id',
        'project_id',
        'subject',
        'description',
        'due_date',
        'category_id',
        'status_id',
        'assigned_to_id',
        'priority_id',
        'fixed_version_id',
        'author_id',
        'lock_version',
        'created_on',
        'updated_on',
        'start_date',
        'done_ratio',
        'estimated_hours',
        'parent_id',
        'root_id',
        'lft',
        'rgt'
    ];

	/**
	 * Name of columns to which http filter can be applied
	 *
	 * @var array
	 */
	protected $allowedFilters = [
        'subject',
        'description',
        'status_id',
        'author_id'
	];

    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(IssueStatus::class);
    }

    public function priority(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Enumeration::class);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(IssueCategory::class);
    }

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function changesets(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Changeset::class, 'changesets_issues');
    }

    public function assignedUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }
}
