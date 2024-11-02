<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Orchid\Layouts\HistoryListLayout;
use App\Models\History;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class HistoryListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Журнал истории действий пользователей';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Здесь отображены все действия пользователей в сервисе';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'history' => History::filters()->orderBy('id', 'desc')->paginate(30)
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            HistoryListLayout::class
        ];
    }
}
