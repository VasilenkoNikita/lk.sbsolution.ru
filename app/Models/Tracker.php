<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracker extends Model
{
    public $table = 'trackers';
    public $timestamps = false;
    public $fillable = [
        'name',
        'is_in_chlog',
        'position',
        'is_in_roadmap'
    ];
}
