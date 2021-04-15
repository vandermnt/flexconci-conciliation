<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxaModel extends Model {
  protected $table = 'controle_taxa_cliente';
  protected $primaryKey = 'CODIGO';
  public $timestamps = false;
}
