<?php

namespace App\Orchid\Screens\Examples;

use App\Orchid\Layouts\Examples\ChartBarExample;
use App\Orchid\Layouts\Examples\ChartLineExample;
use App\Orchid\Layouts\Examples\ChartPercentageExample;
use App\Orchid\Layouts\Examples\ChartPieExample;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ExampleChartsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Charts';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'charts' => [
                [
                    'name'   => 'Some Data',
                    'values' => [25, 40, 30, 35, 8, 52, 17],
                    'labels' => ['30.06.2021', '01.07.2021', '02.07.2021', '03.07.2021', '04.07.2021', '05.07.2021', '06.07.2021'],
                ],
                [
                    'name'   => 'Another Set',
                    'values' => [25, 50, 0, 15, 18, 32, 27],
                    'labels' => ['30.06.2021', '01.07.2021', '02.07.2021', '03.07.2021', '04.07.2021', '05.07.2021', '06.07.2021'],
                ],
                [
                    'name'   => 'Yet Another',
                    'values' => [15, 20, 0, 0, 58, 12, 17],
                    'labels' => ['30.06.2021', '01.07.2021', '02.07.2021', '03.07.2021', '04.07.2021', '05.07.2021', '06.07.2021'],
                ],
                [
                    'name'   => 'And Last',
                    'values' => [10, 33, 8, 3, 70, 20, 34],
                    'labels' => ['30.06.2021', '01.07.2021', '02.07.2021', '03.07.2021', '04.07.2021', '05.07.2021', '06.07.2021'],
                ],
            ],
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
     * @throws \Throwable
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        return [
            ChartLineExample::class,
            ChartBarExample::class,
            ChartPercentageExample::class,
            ChartPieExample::class,
        ];
    }
}
