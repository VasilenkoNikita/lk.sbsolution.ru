<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Project extends Model
{
	use AsSource, Attachable, Filterable;

    public $table = 'projects';

    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';

    public $fillable = [
        'name',
        'description',
        'homepage',
        'is_public',
        'parent_id',
        'created_on',
        'updated_on',
        'identifier',
        'status',
        'lft',
        'rgt'
    ];

	/**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
       'name',
	   'homepage',
	   'identifier',
	   'status'
    ];

	/**
	 * Name of columns to which http filter can be applied
	 *
	 * @var array
	 */
	protected $allowedFilters = [
       'name',
	   'homepage',
	   'identifier',
	   'status'
	];

    public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'members')->orderBy('last_name');
    }

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'queries');
    }

    public function issueCategories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(IssueCategory::class);
    }

    public function versions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Version::class);
    }

    public function repositories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Repository::class);
    }
}
