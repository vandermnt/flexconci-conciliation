<?php

namespace App\Filters;

use Illuminate\Support\Facades\DB;
use App\Filters\Interfaces\BaseSubFilterInterface;
use App\Filters\BaseFilter;

abstract class BaseSubFilter implements BaseSubFilterInterface {
  protected $filter;
  protected $query = null;
  protected $whiteList = [];
  protected $numericFilters = [];

  public function __construct(BaseFilter $filter) {
    $this->filter = $filter;
  }

  public static function subfilter($filters, $subfilters) {}

  public function apply($filters, $subfilters) {}

  protected function buildWhereClause($subfilter, $value) {
    if(in_array($subfilter, $this->numericFilters)) {
      $this->query->whereRaw('(select round('.$subfilter.', 2) = '.$value.' )');
      return $this->query;
    }

    $this->query->where($subfilter, 'like', '%'.$value.'%');
    return $this->query;
  }

  protected function getFilterQuery($filters) {
    return $this->filter
      ->apply($filters)
      ->getQuery();
  }

  public function getQuery() {
    return $this->query;
  }
}