<?php

namespace App\Filters;

use App\Filters\Interfaces\BaseSubFilterInterface;
use App\Filters\BaseFilter;

abstract class BaseSubFilter implements BaseSubFilterInterface {
  protected $filter;
  protected $query = null;
  protected $whiteList = [];

  public function __construct(BaseFilter $filter) {
    $this->filter = $filter;
  }

  public static function subfilter($filters, $subfilters) {}

  public function apply($filters, $subfilters) {}

  protected function getFilterQuery($filters) {
    return $this->filter
      ->apply($filters)
      ->getQuery();
  }

  public function getQuery() {
    return $this->query;
  }
}