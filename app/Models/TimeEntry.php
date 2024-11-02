<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeEntry extends Model
{
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';

    public $table = 'time_entries';
    public $fillable = [
        'project_id',
        'user_id',
        'issue_id',
        'hour',
        'comments',
        'activity_id',
        'spent_on',
        'tyear',
        'tmonth',
        'tweek',
        'created_on',
        'updated_on'
    ];

    protected $dateFormat = 'Y-m-d H:i:sO';
}
