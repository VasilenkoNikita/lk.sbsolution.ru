<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class TypeOfOwnershipFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'typeOfOwnership'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Форма собственности';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {

        if ($this->request->get('typeOfOwnership') === 'Все'){
            return $builder;
        }

        return $builder->where('type_of_ownership', $this->request->get('typeOfOwnership'));
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
            return [
                Select::make('typeOfOwnership')
                    ->options([
                        'Все' => 'Все',
                        'ИП'  => 'ИП',
                        'ООО' => 'ООО',
                        'АНО' => 'АНО',
                    ])
                    ->empty()
                    ->value($this->request->get('typeOfOwnership'))
                    ->title('Фильтр по форме собственности'),
            ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return 'Форма собственности: '.$this->request->get('typeOfOwnership');
    }
}
