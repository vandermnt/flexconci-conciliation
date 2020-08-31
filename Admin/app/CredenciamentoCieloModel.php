<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CredenciamentoCieloModel extends Model{
    public $timestamps = false;
    protected $table = 'credenciamento_cielo';
    protected $primaryKey = 'CODIGO';
}
