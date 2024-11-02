<?php

declare(strict_types=1);

namespace App\Orchid\Layouts;

use App\Models\EconomicActivities;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class EconomicActivitiesListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'EconomicActivities';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [

            TD::make('section_economic_activity.section_code', 'Раздел'),
            TD::make('code_economic_activity', 'Код ОКВЭД')
				->sort()
				->filter(TD::FILTER_TEXT)
                ->render(function (EconomicActivities $economicActivities) {
                    return Link::make($economicActivities->code_economic_activity)
                        ->route('platform.subActivities.list', $economicActivities);
                }),

            TD::make('type_economic_activity', 'Вид деятельности')
                ->sort()
                ->render(function (EconomicActivities $economicActivities) {
                    return Link::make($economicActivities->type_economic_activity)
                        ->route('platform.subActivities.list', $economicActivities);
                }),
        ];
    }
}
