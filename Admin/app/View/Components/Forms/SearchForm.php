<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class SearchForm extends Component
{
    public $hiddenFields;
    public $formData;
    public $urls;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($hiddenFields = [], $formData = [], $urls = [])
    {
        $this->hiddenFields = $hiddenFields;
        $this->formData = $formData;
        $this->urls = $urls;
    }

    public function isFieldVisible($fieldName) {
        return !in_array($fieldName, $this->hiddenFields);
    }

    public function getData($dataKey) {
        return $this->formData[$dataKey] ?? null;
    }

    public function renderUrls() {
        $dataUrlsAttributes = array_reduce($this->urls, function ($urlsString, $url) {
            return $urlsString."data-url-".key($url)."=".$url[key($url)]."\n";
        }, "");

        return $dataUrlsAttributes;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.forms.search-form');
    }
}
