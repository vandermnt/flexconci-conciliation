<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeioCaptura extends Model
{
    protected $table = 'meio_captura';
    protected $primaryKey = 'CODIGO';
    public $timestamps = false;
}
