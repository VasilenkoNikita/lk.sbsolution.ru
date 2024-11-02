<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class NumberOfEmployeesFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'NumberOfEmployees'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Количество сотрудников';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        if ($this->request->get('NumberOfEmployees') === 'Все'){
            return $builder;
        }

        if ((int) $this->request->get('NumberOfEmployees') === 0){
            return $builder->where('number_of_employees', '=', null)->orWhere('number_of_employees', 0);
        }

        return $builder->where('number_of_employees', '>=', (int) $this->request->get('NumberOfEmployees'));
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
            return [
                Select::make('NumberOfEmployees')
                    ->options([
                        'Все' => 'Все',
                        0  => 'Отсутствуют',
                        1 => 'Есть',
                    ])
                    ->empty()
                    ->value($this->request->get('NumberOfEmployees'))
                    ->title('Сотрудники'),
            ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return 'Сотрудники: '. $this->request->get('NumberOfEmployees');
    }
}
