<?php

namespace App\Orchid\Layouts;

use Orchid\Support\Facades\Layout;
use Orchid\Screen\Layouts\Listener;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\TD;

class ReportingListener extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [
        'rangeDate[start]',
        'rangeDate[end]',

    ];

    /**
     * What screen method should be called
     * as a source for an asynchronous request.
     *
     * The name of the method must
     * begin with the prefix "async"
     *
     * @var string
     */
    protected $asyncMethod = 'asyncDateInterval';



    /**
     * @return Layout[]
     */
    protected function layouts(): array
    {
        return [

           // Layout::table('clients', $this->query->has('events')),
           // Layout::table($this->query->has('clients'), $this->query->has('events')),
        ];
    }
}
