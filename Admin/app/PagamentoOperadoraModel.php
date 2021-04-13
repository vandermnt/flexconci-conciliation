<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagamentoOperadoraModel extends Model {
    protected $table = 'pagamentos_operadoras';
    protected $primaryKey = "CODIGO";
    public $timestamps = false;
}
