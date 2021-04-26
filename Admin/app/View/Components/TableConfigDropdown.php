<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TableConfigDropdown extends BaseComponent
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($dataset = [])
    {
        parent::__construct($dataset);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.table-config-dropdown');
    }
}
