<?php

namespace App\View\Components;

use Illuminate\View\Component;

class EnumerationScreenTaskManager extends Component
{
    /**
     * @param Enumeration $enumeration
     */
    public $enumeration;

    /**
     * @param Enumerationcats $enumerationcats
     */
    public $enumerationcats;

    /**
     * Create a new component instance.
     *
     * @param Enumeration $enumeration
     * @param Enumerationcats $enumerationcats
     *
     */
    public function __construct($enumeration, $enumerationcats)
    {
        $this->enumerationcats = $enumerationcats;
        $this->enumeration = $enumeration;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.enumeration-screen-task-manager');
    }
}
