<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusConciliacaoModel extends Model{
  public $timestamps = false;
  protected $table = 'status_conciliacao';

  public function scopeConciliada($query) {
    return $query->where('STATUS_CONCILIACAO', 'Conciliada');
  }

  public function scopeNaoConciliada($query) {
    return $query->where('STATUS_CONCILIACAO', 'Não Conciliada');
  }

  public function scopeJustificada($query) {
    return $query->where('STATUS_CONCILIACAO', 'Justificada');
  }

  public function scopeDivergente($query) {
    return $query->where('STATUS_CONCILIACAO', 'Divergente');
  }

  public function scopeManual($query) {
    return $query->where('STATUS_CONCILIACAO', 'Conciliada Manualmente');
  }
}
