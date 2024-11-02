<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Orchid\Layouts\SessionListLayout;
use App\Models\Session;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class SessionListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Сессии пользователей';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Здесь отображены активные пользователи сайта';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'session' => Session::filters()->orderBy('id', 'desc')->where('user_id', '!=', NULL)->paginate(30)
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
            SessionListLayout::class
        ];
    }
}
