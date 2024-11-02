<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\SubActivity;
use App\Models\EconomicActivities;
use App\Orchid\Layouts\SubActivitiesListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class SubActivitiesListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список групп ОКВЭД';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Все группы ОКВЭД класса';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(EconomicActivities $EconomicActivities): array
    {

        return [
            'SubActivity' => SubActivity::filters()->defaultSort('id')->where('economic_activity_id', $EconomicActivities->id)->paginate(100)
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
            SubActivitiesListLayout::class
        ];
    }
}
