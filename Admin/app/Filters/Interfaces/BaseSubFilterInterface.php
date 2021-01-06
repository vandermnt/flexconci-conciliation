<?php

namespace App\Filters\Interfaces;

interface BaseSubFilterInterface {
  public static function subfilter($filters, $subfilters);

  public function apply($filters, $subfilters);

  public function getQuery();
}