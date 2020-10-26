<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JustificativaModel extends Model{
  public $timestamps = false;
  protected $primaryKey = "CODIGO";
  protected $table = 'justificativa';
}
