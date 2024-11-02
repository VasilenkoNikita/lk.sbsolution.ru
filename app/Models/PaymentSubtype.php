<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class PaymentSubtype extends Model
{
    use AsSource, Filterable;

    public $table = 'payments_subtypes';

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
        return $this->belongsToMany(Payment::class, 'payment_payment_subtypes', 'payment_id', 'payment_subtype_id');
    }
}
