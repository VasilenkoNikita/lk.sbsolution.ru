<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    public $table = 'custom_fields';
    public $timestamps = false;
    public $fillable = [
        'type',
        'name',
        'field_format',
        'possible_values',
        'regexp',
        'min_length',
        'max_length',
        'is_required',
        'is_for_all',
        'is_filter',
        'position',
        'searchable',
        'default_value',
        'editable'
    ];
}
