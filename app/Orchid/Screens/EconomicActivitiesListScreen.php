<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Orchid\Layouts\EconomicActivitiesListLayout;
use App\Models\EconomicActivities;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class EconomicActivitiesListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список ОКВЭД';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Все ОКВЭД классы';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'EconomicActivities' => EconomicActivities::with('section_economic_activity')->filters()->defaultSort('id')->paginate(100)
        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Создать новый ОКВЭД')
                ->icon('pencil')
                ->route('platform.economicActivities.create')
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
            EconomicActivitiesListLayout::class
        ];
    }
}
