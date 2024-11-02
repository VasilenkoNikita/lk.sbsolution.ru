<?php

namespace App\Modules\Reporting\Filters;

use App\Models\TypesOfTaxes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;

class TypeOfTaxesFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'typeOfTaxes',
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'СНО';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereHas('typeOfTaxes', function (Builder $query) {
            $query->where('type_of_tax_id', (int) $this->request->get('typeOfTaxes'));
        });
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
            return [
                Select::make('typeOfTaxes')
                    ->fromModel(TypesOfTaxes::class, 'name', 'id')
                    ->empty()
                    ->value((int) $this->request->get('typeOfTaxes'))
                    ->title('Фильтр по СНО'),
            ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return 'СНО : '.TypesOfTaxes::where('id', $this->request->get('typeOfTaxes'))->first()->name;
    }
}
