<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use Orchid\Platform\Dashboard;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;

class PlatformScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Главная';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Добро пожаловать';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Сайт компании')
                ->href('https://sbsolution.ru')
                ->icon('globe-alt'),

            Link::make('Документация')
                ->href('#')
                ->icon('docs'),

           /* Link::make('GitHub')
                ->href('https://github.com/orchidsoftware/platform')
                ->icon('social-github'),*/
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::view('platform::partials.welcome'),
        ];
    }
}
