<?php

namespace App\Filters\Interfaces;

use Illuminate\Http\Request;

interface BaseFilterInterface {
  public static function filter(Request $filters);
  
  public function apply(Request $filters);

  public function getQuery();
}