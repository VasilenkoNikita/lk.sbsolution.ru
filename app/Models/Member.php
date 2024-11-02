<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Member extends Model
{
	use AsSource, Filterable;

    public $table = 'members';

    public $fillable = [
        'user_id',
        'project_id',
        'created_on',
        'mail_notification',
    ];

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(RolesProjects::class, 'member_roles', 'member_id', 'role_id');
    }
}
