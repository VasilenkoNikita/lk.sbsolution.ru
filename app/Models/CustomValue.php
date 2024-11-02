<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomValue extends Model
{
    public $table = 'custom_values';
    public $timestamps = false;
    public $fillable = [
        'customized_types',
        'customized_id',
        'custom_field_id',
        'value'
    ];
}
