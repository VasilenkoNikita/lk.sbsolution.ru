<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\UserStatistics;

use Orchid\Screen\Layouts\Chart;

class UserPieLayout extends Chart
{
    /**
    * @var string
    */
    protected $title = 'Количество клиентов по форме собственности';

    /**
     * @var int
     */
    protected $height = 350;

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
    protected $target = 'TypeOfOwnershipStats';
}
