<?php

namespace App\Orchid\Screens;

use App\Models\Manual;
use App\Orchid\Layouts\ManualListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ManualListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Руководства сервиса';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Здесь отображены все руководства для сервиса';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'manuals' => Manual::filters()->orderBy('id', 'desc')->paginate(30)
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Создать новое руководство')
                ->icon('pencil')
                ->route('platform.manuals.create'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            ManualListLayout::class
        ];
    }
}
