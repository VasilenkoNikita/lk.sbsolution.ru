<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class RolesProjects extends Model
{
	use AsSource, Filterable;

	public $table = 'roles_projects';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'position',
        'assignable',
        'builtin',
        'permissions'
    ];

	 protected $allowedSorts = [
        'name',
        'assignable'
    ];

    public function getNameAttribute(): string
    {
        return $this->attributes['name'];
    }
}
