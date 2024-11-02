<?php

namespace App\View\Components;

use Illuminate\View\Component;
use phpDocumentor\Reflection\Types\Collection;

class Tags extends Component
{
    /**
     * @param $enumeration Collection
     */
    public $tags;

    /**
     * Create a new component instance.
     *
     * @param $tags
     */
    public function __construct($tags)
    {
        $this->tags = $tags;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.tags');
    }
}
