<?php

namespace App\Orchid\Filters;

use App\Models\UserColor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class ColorFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'userColors'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Цвет';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereHas('userColors', function (Builder $query) {
            $query->where('color_id', $this->request->get('userColors'));
        });
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
            return [
                Select::make('userColors')
                    ->fromQuery(UserColor::where('user_id', '=', Auth::user()->id), 'name')
                    ->empty()
                    ->value($this->request->get('userColors'))
                    ->title('Фильтр по цвету'),
            ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return 'Цвет : '.UserColor::where('id', $this->request->get('userColors'))->first()->name;
    }
}
