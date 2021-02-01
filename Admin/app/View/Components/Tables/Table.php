<?php

namespace App\View\Components\Tables;

use Illuminate\View\Component;

class Table extends Component
{
    public $hiddenColumns;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($hiddenColumns = [])
    {
        $this->hiddenColumns = $hiddenColumns;
    }

    public function isColumnVisible($columnName = '') {
        return !in_array($columnName, $this->hiddenColumns);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render() {}
}
