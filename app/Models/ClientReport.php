<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ClientReport extends Model
{
    use AsSource, Filterable;

    public $table = 'clients_reports';

    public $fillable = [
        'client_id',
        'report_id',
        'comment',
        'activity',
        'added_by_user'
    ];

}
