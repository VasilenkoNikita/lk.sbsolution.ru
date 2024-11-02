<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ClientUserSettings extends Model
{
    use AsSource, Filterable;

    public $table = 'clients_users_settings';

    public $fillable = [
        'id',
        'user_id',
        'name',
        'row_name',
        'position',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
