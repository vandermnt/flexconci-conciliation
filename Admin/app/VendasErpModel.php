<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendasErpModel extends Model{
  public $timestamps = false;
  protected $table = 'vendas_erp';
  protected $primaryKey = "CODIGO";

}
