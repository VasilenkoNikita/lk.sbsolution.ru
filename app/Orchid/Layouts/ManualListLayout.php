<?php

namespace App\Orchid\Layouts;

use App\Models\Manual;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class ManualListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'manuals';

    /**
     * @return string
     */
    protected function iconNotFound(): string
    {
        return 'icon-table';
    }

    /**
     * @return string
     */
    protected function textNotFound(): string
    {
        return __('There are no records in this view');
    }

    /**
     * @return string
     */
    protected function subNotFound(): string
    {
        return '';
    }

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('section', 'Раздел')
                ->filter(TD::FILTER_TEXT)
                ->sort()
                ->render(function (Manual $manual) {
                    return Link::make($manual->section)
                        ->route('platform.manuals.edit', $manual);
                }),

            TD::make('header', 'Заголовок')
                ->sort(),

            TD::make('code', 'Код раздела')
                ->sort(),

            TD::make('version', 'Версия')
                ->sort(),

        ];
    }
}
