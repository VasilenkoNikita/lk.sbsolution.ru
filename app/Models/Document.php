<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public $table = 'documents';
    public $fillable = [
        'project_id',
        'category_id',
        'title',
        'description',
        'created_on'
    ];

    const CREATED_AT = 'created_on';
    const UPDATED_AT = null;
}
