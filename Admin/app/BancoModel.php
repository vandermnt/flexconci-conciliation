<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BancoModel extends Model{
    protected $table = 'lista_bancos';
    protected $primaryKey = 'CODIGO';
    public $timestamps = false;
}
