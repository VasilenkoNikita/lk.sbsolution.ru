<?php

namespace App\Orchid\Layouts;

use App\Models\History;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class HistoryListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'history';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {


        return [
            TD::make('reference_table', 'Модуль')
                ->filter(TD::FILTER_TEXT)
                ->sort()
                ->width('125px'),

            TD::make('reference_id', 'ID')
                ->sort()
                ->width('50px'),

            TD::make('user_id', 'Пользователь')
                ->filter(TD::FILTER_TEXT)
                ->sort()
                ->render(function (History $history) {
                    $user = User::where('id', $history->user_id)->get();
                    return Link::make($user[0]->name)
                        ->href('system/users/'.$history->user_id.'/edit');
                })
                ->width('100px'),

            TD::make('change_type', 'Тип изменения')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->width('100px'),

            TD::make('body', 'Изменение')
                ->sort()
                ->render(function ($history) {
                    return $history->body;
                })
                ->width('500px'),

            TD::make('updated_at', 'Дата изменения')
                ->sort()
                ->render(function (History $history) {
                    return $history->updated_at->toDateTimeString();
                })
                ->width('150px'),

        ];
    }
}
