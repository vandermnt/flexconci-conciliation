<?php

namespace App\Filters;

use App\Filters\Interfaces\BaseFilterInterface;
use Illuminate\Support\Arr;

abstract class BaseFilter implements BaseFilterInterface {
  protected $query = null;
  protected $whiteList = [];
  protected $sortKeys = ['orderBy' => 'column', 'order' => 'direction'];

  public function getSortKeys() {
    return [$this->sortKeys['orderBy'], $this->sortKeys['order']];
  }
  
  public function getAllowedKeys() {
    return Arr::collapse([$this->whiteList, ['sort']]);
  }

  public function buildOrderClause($sortValues) {
    if(!$sortValues || !Arr::has($sortValues, $this->getSortKeys())) {
      return $this->query;
    }

    $column = $sortValues[$this->sortKeys['orderBy']] ?? null;
    $direction = $sortValues[$this->sortKeys['order']] === 'asc' ? 'asc' : 'desc';

    if($column) {
      $this->query->orderBy($column, $direction);
    }

    return $this->query;
  }

  public static function filter($filters) {}

  public function apply($filters) {}

  public function getQuery() {
    return $this->query;
  }
}