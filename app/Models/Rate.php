<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Rate extends Model
{
    use AsSource, Filterable;

    public $table = 'rates';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'active',
    ];

	/**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */

    protected $allowedSorts = [
        'name',
        'active',
    ];

	/**
	 * Name of columns to which http filter can be applied
	 *
	 * @var array
	 */
	protected $allowedFilters = [
        'name',
	];
	
	public function clients(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
		return $this->belongsToMany(Client::class, 'clients_rates', 'client_id', 'rate_id');
	}

}
