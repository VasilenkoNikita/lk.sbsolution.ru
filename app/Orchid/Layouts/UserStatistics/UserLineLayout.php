<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\UserStatistics;

use Orchid\Screen\Layouts\Chart;

class UserLineLayout extends Chart
{
    /**
     * @var string
     */
    protected $title = 'Статистика по действиям пользователей за последние 7 дней';

    /**
     * Limiting the slices.
     *
     * When there are too many data values to show visually,
     * it makes sense to bundle up the least of the values as a cumulated data point,
     * rather than showing tiny slices.
     *
     * @var int
     */
    protected $maxSlices = 30;


    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'line';



    /**
     * @var string
     */
    protected $target = 'UserChangesActivity';
}
