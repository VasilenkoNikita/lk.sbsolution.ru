<?php

declare(strict_types=1);

namespace App\Models;

use App\Model\MyBaseModel;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class PaymentType extends MyBaseModel
{
    use AsSource, Filterable;

    public $table = 'payments_types';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

	/**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'name',
    ];

	/**
	 * Name of columns to which http filter can be applied
	 *
	 * @var array
	 */
	protected $allowedFilters = [
        'name'
	];

    public function payments(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'payment_payment_types', 'payment_id', 'payment_type_id');
    }
}
