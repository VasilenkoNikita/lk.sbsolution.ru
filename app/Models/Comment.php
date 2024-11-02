<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $table = 'comments';
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';

    public $fillable = [
        'commented_type',
        'commented_id',
        'author_id',
        'comments',
        'created_on',
        'updated_on'
    ];

    protected $dateFormat = 'Y-m-d H:i:sO';
}
