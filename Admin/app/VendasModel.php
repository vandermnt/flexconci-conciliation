<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendasModel extends Model{
    public $timestamps = false;
    protected $table = 'vendas';
    protected $primaryKey = "CODIGO";
}
