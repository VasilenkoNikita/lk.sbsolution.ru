<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public $table = 'tokens';
    const CREATED_AT = 'created_on';

    public $fillable = [
        'user_id',
        'action',
        'value',
        'created_on'
    ];

    protected $dateFormat = 'Y-m-d H:i:sO';
}
