<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    public $table = 'journal';
    const CREATED_AT = 'created_on';

    public $fillable = [
        'journalized_id',
        'journalized_type',
        'user_id',
        'notes',
        'created_on'
    ];

    protected $dateFormat = 'Y-m-d H:i:sO';
}
