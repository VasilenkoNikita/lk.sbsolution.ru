<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ReportSubtype extends Model
{
    use AsSource, Filterable;

    public $table = 'reports_subtypes';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'name',
    ];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = [
        'name'
    ];

    public function reports(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Report::class, 'report_report_subtypes', 'report_id', 'report_subtype_id');
    }
}
