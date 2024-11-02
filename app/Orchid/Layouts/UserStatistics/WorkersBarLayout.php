<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\UserStatistics;

use Orchid\Screen\Layouts\Chart;

class WorkersBarLayout extends Chart
{
    /**
     * @var string
     */
    protected $title = 'Клиенты по количеству сотрудников';

    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'bar';

    /**
     * @var string
     */
    protected $target = 'workersStats';
}
