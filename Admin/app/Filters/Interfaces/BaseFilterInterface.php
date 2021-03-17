<?php

namespace App\Filters\Interfaces;

interface BaseFilterInterface {
  public function getSortKeys();

  public function getAllowedKeys();

  public function buildOrderClause($sortValues);

  public static function filter($filters);
  
  public function apply($filters);

  public function getQuery();
}