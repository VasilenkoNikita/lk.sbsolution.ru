<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;

class ClientActivityFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'clientActivity'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Архивные клиенты';
    }
    
    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->where('client_active', (int) $this->request->get('clientActivity'));
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
            return [
                Select::make('clientActivity')
                    ->options([
                        1  => 'Скрыть архивных клиентов',
                        0 => 'Показать архивных клиентов',
                    ])
                    ->empty()
                    ->value($this->request->get('clientActivity') ?? (int) $this->request->get('clientActivity'))
                    ->title('Активность клиента')
                    ->help('Переключите активность клиентов'),
            ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return (int) $this->request->get('clientActivity') === 1 ? 'Архивные клиенты : Скрыты' : ' Архивные клиенты : Показаны';
    }
}
