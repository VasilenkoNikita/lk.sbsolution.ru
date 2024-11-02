<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    public $table = 'versions';
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';

    public $fillable = [
        'project_id',
        'name',
        'description',
        'effective_date',
        'created_on',
        'updated_on',
        'status',
        'sharing'
    ];

    protected $dateFormat = 'Y-m-d H:i:sO';
}
