<?php

namespace App\Filters\Interfaces;

interface BaseFilterInterface {
  public static function filter($filters);
  
  public function apply($filters);

  public function getQuery();
}