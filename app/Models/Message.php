<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Message extends Model
{
    use AsSource, Filterable;

    public $table = 'messages';
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';
    public $fillable = [
        'board_id',
        'parent_id',
        'subject',
        'content',
        'author_id',
        'replies_count',
        'last_reply_id',
        'locked',
        'sticky'
    ];

    protected $allowedSorts = [
        'author_id',
        'subject'
    ];
}
