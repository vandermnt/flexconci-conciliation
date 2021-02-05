<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class CheckGroup extends Component
{
    public $id;
    public $itemValueKey;
    public $itemDescriptionKey;
    public $options;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id = '', $itemValueKey = '', $itemDescriptionKey = '', $options = [])
    {
        $this->id = $id;
        $this->itemValueKey = $itemValueKey;
        $this->itemDescriptionKey = $itemDescriptionKey;
        $this->options = $options;
    }

    public function getOptions() {
        if(is_null($this->options)) {
            return [];
        }
        
        if(is_array($this->options)) {
            return $this->options;
        }

        return $this->options->toArray();
    }

    public function getItemValue($item) {
        $item = is_array($item) ? $item : ((array) $item);
        return $item[$this->itemValueKey];
    }
    
    public function getItemDescription($item) {
        $item = is_array($item) ? $item : ((array) $item);
        return $item[$this->itemDescriptionKey];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.forms.check-group');
    }
}
