<?php

namespace App\Orchid\Filters;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class TagFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'tag'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Тэг';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {

        return $builder->whereHas('tags', function (Builder $query) {
            $query->where('name->ru', $this->request->get('tag'));
        });

    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Select::make('tag')
                ->fromQuery(Tag::where('type', $this->request->get('tagType')), 'name', 'name')
                ->value($this->request->get('tag'))
                ->title('Фильтр по тегу'),
        ];
    }
}
