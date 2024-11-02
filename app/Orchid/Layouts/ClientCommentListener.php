<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layout;
use Orchid\Screen\Layouts\Listener;

class ClientCommentListener extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [
        'client.comment'
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
    protected $asyncMethod = 'asyncComment';

    /**
     * @return Layout[]
     */
    protected function layouts(): array
    {
        return [
            TextArea::make('client.comment')
                    ->rows(3),
        ];
    }
}
