<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    public $table = 'workflows';
    public $timestamps = false;
    public $fillable = [
        'tracker_id',
        'old_status_id',
        'new_status_id',
        'role_id'
    ];
}
