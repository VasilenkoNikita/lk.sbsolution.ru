<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    public $table = 'queries';
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';

    public $fillable = [
        'project_id',
        'user_id',
        'name',
        'filters',
        'is_public',
        'column_names',
        'sort_criteria',
        'group_by'
    ];

    protected $dateFormat = 'Y-m-d H:i:sO';
}
