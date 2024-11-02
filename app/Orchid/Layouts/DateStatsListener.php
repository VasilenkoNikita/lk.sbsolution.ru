<?php

namespace App\Orchid\Layouts;

use App\Orchid\Layouts\UserStatistics\CnoPieLayout;
use App\Orchid\Layouts\UserStatistics\UserLineLayout;
use App\Orchid\Layouts\UserStatistics\UserPieLayout;
use App\Orchid\Layouts\UserStatistics\WorkersBarLayout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Listener;
use Orchid\Support\Facades\Layout;

class DateStatsListener extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [
        'number_of_days',
    ];

    /**
     * What screen method should be called
     * as a source for an asynchronous request.
     *
     * The name of the method must
     * begin with the prefix "async"
     *
     * @var string
     */
    protected $asyncMethod = 'asyncDateStats';

    /**
     * @return Layout[]
     */
    protected function layouts(): array
    {
        return [
            Layout::rows([
                Input::make('number_of_days')
                    ->title('Количество дней истории пользователей для показа')
                    ->type('number'),
            ]),
            'history' => UserLineLayout::class,
        ];
    }
}
