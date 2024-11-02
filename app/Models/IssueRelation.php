<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class IssueRelation extends Model
{
	use AsSource;

    public $table = 'issue_relations';
    public $timestamps = false;
    public $fillable = [
        'issue_from_id',
        'issue_to_id',
        'relation_type',
        'delay'
    ];
}
