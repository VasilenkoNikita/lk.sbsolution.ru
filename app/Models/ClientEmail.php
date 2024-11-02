<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ClientEmail extends Model
{
    use AsSource, Filterable;

    public $table = 'clients_emails';
    public $timestamps = false;

    public $fillable = [
        'email',
        'additional_information'
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'email'
    ];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = [
        'email'
    ];

    public function clients()
    {
        return $this->belongsTo(Client::class);
    }
}
