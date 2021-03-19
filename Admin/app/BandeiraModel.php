<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BandeiraModel extends Model{
  protected $table = 'bandeira';
  protected $primaryKey = 'CODIGO';
  public $timestamps = false;
}
