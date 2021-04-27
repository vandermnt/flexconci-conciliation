<?php

namespace App\Exports;

class BaseExport
{
  protected $filters;
  protected $subfilters;
  protected $keys = [];
  protected $hidden;
  protected $dynamicHeaders;

  public function __construct($filters, $subfilters, $hidden = [], $dynamicHeaders = []) {
    $this->filters = $filters;
    $this->subfilters = $subfilters;
    $this->hidden = $hidden;
    $this->dynamicHeaders = $dynamicHeaders;
  }

  public function getHeaders() {
    $keys = collect($this->keys);
    $allowed = $keys->keys();

    $mergedHeaders = collect($this->dynamicHeaders)
      ->only($allowed)
      ->keys()
      ->reduce(function($merged, $dynamicHeaderKey) use ($keys) {
        $newHeader = $this->dynamicHeaders[$dynamicHeaderKey];
        if(!$newHeader) return $merged;

        $newHeader = ucwords(mb_strtolower($this->dynamicHeaders[$dynamicHeaderKey]));
        $type = $keys->get($dynamicHeaderKey)['type'];

        $keys->put($dynamicHeaderKey, ['header' => $newHeader, 'type' => $type]);
        return $keys;
      }, $keys);

    return $mergedHeaders->except($this->hidden)
      ->pluck('header')
      ->toArray();
  }

  public function serializeItem($item) {
    $keys = collect($this->keys)->keys();
    return collect($item)->only($keys)->except($this->hidden);
  }

  public function getValues($item) {
    $serializedItem = $this->serializeItem($item);
    $values = $this->serializeItem($serializedItem)
      ->keys()
      ->reduce(function ($values, $key) use ($serializedItem) {
        $value = $serializedItem->get($key);
        $type = $this->keys[$key]['type'];

        array_push($values, $this->format($value, $type));
        return $values;
      }, []);

    return $values;
  }

  public function _formatString($value = '', $type = 'string') {
    if($type === 'forceToString') return $value." ";

    return $value ?? '';
  }

  public function _formatNumber($value = null, $type = 'numeric') {
    return round(($value ?? 0), 2);
  }

  public function _formatDate($value = null, $type = 'date') {
    if(is_null($value)) return $value;

    return date_format(date_create($value), 'd/m/Y');
  }

  public function format($value = null, $type = 'string') {
    $formatFunctions = [
      'string' => '_formatString',
      'forceToString' => '_formatString',
      'numeric' => '_formatNumber',
      'date' => '_formatDate',
    ];

    $formatter = $formatFunctions[$type];

    if(is_null($formatFunctions)) return $value;

    return $this->$formatter($value, $type);
  }
}
