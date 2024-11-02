<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Payment extends Model
{
    use AsSource, Filterable;

    public $table = 'payments';

    /**
     * @var array
     */
    protected $fillable = [
        'payment_name',
        'payment_date',
        'type',
        'subtype',
        'formtype',
        'active',
        'visibility',
        'type_of_ownership'
    ];

	/**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */

    protected $allowedSorts = [
        'payment_name',
        'payment_date',
        'type',
        'subtype',
        'formtype',
        'type_of_ownership'
    ];

	/**
	 * Name of columns to which http filter can be applied
	 *
	 * @var array
	 */
	protected $allowedFilters = [
        'payment_name',
        'type',
        'subtype',
	];

	public function clients(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
		return $this->belongsToMany(Client::class, 'clients_payments', 'client_id', 'payment_id');
	}

    public function paymentsTypes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(PaymentType::class, 'payment_payment_type', 'payment_id', 'payment_type_id');
    }

    public function paymentsSubtypes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(PaymentSubtype::class, 'payment_payment_subtype', 'payment_id', 'payment_subtype_id');
    }

}
