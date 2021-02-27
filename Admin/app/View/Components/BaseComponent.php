<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BaseComponent extends Component
{
    public $dataset;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($dataset = [])
    {
        $this->dataset = $dataset;
    }

    public function renderDataset() {
        $dataAttributes = array_reduce(array_keys($this->dataset), function($datasetValues, $key) {
            array_push($datasetValues, "data-${key}=\"{$this->dataset[$key]}\"");
            return $datasetValues;
        }, []);

        return implode($dataAttributes, "\n") ?? '';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.base-component');
    }
}
