<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdquirentesModel extends Model{
  protected $table = 'adquirentes';
  protected $primaryKey = 'CODIGO';
  public $timestamps = false;
}
