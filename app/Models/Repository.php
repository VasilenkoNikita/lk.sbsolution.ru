<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    public $table = 'repositories';
    public $timestamps = false;
    public $fillable = [
        'project_id',
        'url',
        'login',
        'password',
        'root_url',
        'type'
    ];


    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('Project');
    }

    public function changesets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('Changeset');
    }
}
