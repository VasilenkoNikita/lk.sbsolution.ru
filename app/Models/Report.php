<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Report extends Model
{
    use AsSource, Filterable;

    public $table = 'reports';

    /**
     * @var array
     */
    protected $fillable = [
        'report_name',
        'report_date',
        'type',
        'subtype',
        'formtype',
        'active',
        'visibility',
        'type_of_ownership'
    ];

	/**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'report_name',
        'report_date',
        'type',
        'subtype',
        'formtype',
        'type_of_ownership'
    ];

	/**
	 * Name of columns to which http filter can be applied
	 *
	 * @var array
	 */
	protected $allowedFilters = [
        'report_name',
        'type',
        'subtype',
	];

	public function clients(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
		return $this->belongsToMany(Client::class, 'clients_reports', 'client_id', 'report_id');
	}

    public function reportsTypes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ReportType::class, 'report_report_type', 'report_id', 'report_type_id');
    }

    public function reportsSubtypes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ReportSubtype::class, 'report_report_subtype', 'report_id', 'report_subtype_id');
    }

}
