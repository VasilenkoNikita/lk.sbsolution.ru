<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnabledModule extends Model
{
    public $table = 'enabled_modules';
    public $timestamps = false;
    public $fillable = [
        'project_id',
        'name'
    ];
}
