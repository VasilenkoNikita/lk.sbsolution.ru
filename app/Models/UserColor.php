<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class UserColor extends Model
{
    use AsSource, Filterable;

    public $table = 'users_colors';

    public $fillable = [
        'id',
        'user_id',
        'name',
        'color',
        'position',
    ];


    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function clients(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'colors_clients', 'color_id', 'client_id');
    }

    public function reportings(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ClientReporting::class, 'colors_reportings', 'color_id', 'reporting_id');
    }
}
