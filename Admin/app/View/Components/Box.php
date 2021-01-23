<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Box extends Component
{
    public $title;
    public $content;
    public $iconPath;
    public $iconDescription;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title = '', $content = '', $iconPath = '',$iconDescription = '')
    {
        $this->title = $title;
        $this->content = $content;
        $this->iconPath = $iconPath;
        $this->iconDescription = $iconDescription;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.box');
    }
}
