<?php

namespace App\Filters;

use App\Filters\Interfaces\BaseFilterInterface;

abstract class BaseFilter implements BaseFilterInterface {
  protected $query = null;
  protected $whiteList = [];

  public static function filter($filters) {}

  public function apply($filters) {}

  public function getQuery() {
    return $this->query;
  }
}