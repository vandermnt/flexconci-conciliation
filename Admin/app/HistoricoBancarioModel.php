<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoricoBancarioModel extends Model{
    protected $table = 'historico_banco';
    protected $primaryKey = "CODIGO";
    public $timestamps = false;
}
