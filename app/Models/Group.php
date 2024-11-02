<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use App\Models\Client;

class Group extends Model
{
     use AsSource, Filterable;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
	    'group_active'
    ];

	/**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'name',
        'description',
    ];

	/**
	 * Name of columns to which http filter can be applied
	 *
	 * @var array
	 */
	protected $allowedFilters = [
        'name'
	];

	public function clients(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
		return $this->belongsToMany(Client::class, 'clients_groups', 'client_id', 'group_id');
	}

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_groups', 'user_id', 'group_id');
    }
}
