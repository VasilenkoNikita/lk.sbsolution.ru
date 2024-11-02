<?php

namespace App\Modules\Reporting\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class ReportingFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'reporting',
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Типы событий';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder;
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Select::make('reporting')
                ->options([
                    'Отчеты'  => 'Отчеты',
                    'Оплаты' => 'Оплаты',
                    'Все' => 'Все',
                ])
                ->value($this->request->get('reporting'))
                ->title('Типы событий')
                ->class('col-md-6'),
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->name().': '.$this->request->get('reporting');
    }
}
