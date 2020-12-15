<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\Interfaces\BaseFilterInterface;

abstract class BaseFilter implements BaseFilterInterface {
  protected $query = null;
  protected $whiteList = [];

  public static function filter(Request $filters) {}

  public function apply(Request $filters) {}

  public function getQuery() {
    return $this->query;
  }
}