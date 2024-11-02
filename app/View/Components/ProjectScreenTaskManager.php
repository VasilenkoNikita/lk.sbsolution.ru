<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProjectScreenTaskManager extends Component
{
    /**
     * @param Project $project
     */
    public $project;

    /**
     * @param Board $board
     */
    public $board;

    /**
     * @param Messages $messages
     */
    public $messages;

    /**
     * Create a new component instance.
     *
     * @param Messages $messages
     * @param Project $project
     * @param Board $board
     */
    public function __construct($project, $board, $messages)
    {
        $this->project = $project;
        $this->board = $board;
        $this->messages = $messages;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.project-screen-task-manager');
    }
}
