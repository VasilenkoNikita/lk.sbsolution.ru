<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalDetail extends Model
{
    public $table = 'journal_details';
    public $timestamps = false;
    public $fillable = [
        'journal_id',
        'property',
        'prop_key',
        'old_value',
        'value'
    ];
}
