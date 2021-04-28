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

    public function getHeader($headerName = null, $defaultValue = null) {
      $header = $this->headers[$headerName];
      if(!$header) {
        return $defaultValue;
      }

      $formattedHeader = ucwords(mb_strtolower($header, 'utf-8'));
      return $formattedHeader;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render() {}
}
