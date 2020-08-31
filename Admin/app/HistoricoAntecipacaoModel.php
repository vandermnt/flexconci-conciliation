<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoricoAntecipacaoModel extends Model{
  protected $connection = 'mysql_trava';
  protected $table = 'historico_antecipacao';
  public $timestamps = false;
}
