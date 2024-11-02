<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Change extends Model
{
    public $table = 'change';
    public $timestamps = false;
    public $fillable = [
        'changeset_id',
        'action',
        'path',
        'form_path',
        'from_revision',
        'revision',
        'branch'
    ];
}
