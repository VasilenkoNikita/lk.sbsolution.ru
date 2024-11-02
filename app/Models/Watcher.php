<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watcher extends Model
{
    public $table = 'watchers';
    public $timestamps = false;

    public $fillable = [
        'watchable_id',
        'user_id'
    ];
}
