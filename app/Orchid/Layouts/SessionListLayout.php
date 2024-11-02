<?php

namespace App\Orchid\Layouts;

use App\Models\Session;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class SessionListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'session';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('user_id', 'Пользователь')
                ->filter(TD::FILTER_TEXT)
                ->sort()
                ->render(function (Session $session) {
                    return Link::make(Auth::user()->name)
                        ->href('system/users/'.$session->user_id.'/edit');
                }),

            TD::make('ip_address', 'ID адрес'),

            TD::make('last_activity', 'Последняя активность')
                ->sort()
                ->render(function (Session $session) {
                    return date('d-m-Y H:i:s', $session->last_activity);
                }),
        ];
    }
}
