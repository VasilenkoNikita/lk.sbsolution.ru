<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\UserStatistics;

use Orchid\Screen\Layouts\Chart;

class CnoPieLayout extends Chart
{
    /**
    * @var string
    */
    protected $title = 'Количество клиентов по системе налогооблажения';

    /**
     * @var int
     */
    protected $height = 350;

    /**
     * Limiting the slices.
     *
     * When there are too many data values to show visually,
     * it makes sense to bundle up the least of the values as a cumulated data point,
     * rather than showing tiny slices.
     *
     * @var int
     */
    protected $maxSlices = 12;


    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'pie';

    /**
     * @var string
     */
    protected $target = 'cnoStats';
}
