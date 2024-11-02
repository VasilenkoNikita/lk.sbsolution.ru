<?php

declare(strict_types=1);

namespace App\Orchid\Layouts;

use App\Models\SubActivity;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class SubActivitiesListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'SubActivity';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [

            TD::make('code', 'Код группы ОКВЭД')
                ->width('100px'),
            TD::make('name', 'Наименование группы ОКВЭД')
                ->width('400px')
				->sort()
				->filter(TD::FILTER_TEXT),

            TD::make('description', 'Примечание')
                ->width('300px')
                ->sort(),
        ];
    }
}
