<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DadosArquivoConciliacaoBancariaModel extends Model {
    public $timestamps = false;
    protected $table = 'dados_arquivo_conciliacao_bancaria';
    protected $primaryKey = 'CODIGO';
}
