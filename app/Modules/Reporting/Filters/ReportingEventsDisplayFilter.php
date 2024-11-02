<?php

namespace App\Modules\Reporting\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class ReportingEventsDisplayFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'reportingEventDisplay',
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Режим фильтрации событий';
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
            Select::make('reportingEventDisplay')
                ->options([
                    0 => 'Выключить',
                    1 => 'Включить',
                ])
                ->empty()
                ->value((int) $this->request->get('reportingEventDisplay'))
                ->title('Режим фильтрации событий'),
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return (int) $this->request->get('reportingEventDisplay') === 1 ? 'Режим фильтрации: Включен' : 'Режим фильтрации: Выключен';
    }
}
