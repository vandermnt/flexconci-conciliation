<?php

namespace App\View\Components\Tables;

use Illuminate\View\Component;

class Table extends Component
{
    public $hiddenColumns;
    public $headers;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($hiddenColumns = [], $headers = [])
    {
        $this->hiddenColumns = $hiddenColumns;
        $this->headers = $headers;
    }

    public function isColumnVisible($columnName = '') {
        return !in_array($columnName, $this->hiddenColumns);
    }

    public function getHeader($headerName = '') {
        return $this->headers[$headerName] ?? null;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render() {}
}
