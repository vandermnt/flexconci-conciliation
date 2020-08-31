<?php

namespace App\Http\Controllers;

use Request;
use DB;
use App\StatusConciliacaoModel;
use App\GruposClientesModel;

class VendasErpController extends Controller{

  public function vendaserp(){
    $adquirentes = DB::table('cliente_operadora')
    ->join('adquirentes', 'cliente_operadora.COD_ADQUIRENTE', 'adquirentes.CODIGO')
    ->select('adquirentes.*')
    ->where('cliente_operadora.COD_CLIENTE', '=', session('codigologin'))
    ->distinct('COD_ADQUIRENTE')
    ->get();

    $status_conciliacao = StatusConciliacaoModel::where('CODIGO', '!=', 4)->orderBy('STATUS_CONCILIACAO', 'ASC')->get();
    $grupos_clientes = GruposClientesModel::where('COD_CLIENTE', '=', session('codigologin'))->get();

    return view('vendaserp')->with('adquirentes', $adquirentes)
    ->with('grupos_clientes', $grupos_clientes)
    ->with('status_conciliacao', $status_conciliacao);
  }
}
