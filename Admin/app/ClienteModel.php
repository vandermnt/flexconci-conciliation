<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteModel extends Model{
    protected $table = 'clientes';
    protected $primaryKey = 'CODIGO';
    public $timestamps = false;
}
