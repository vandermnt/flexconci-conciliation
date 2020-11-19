<?php

namespace App\Http\Controllers;

use Request;
use DB;
use App\MeioCaptura;
use App\StatusConciliacaoModel;
use App\GruposClientesModel;
use App\AdquirentesModel;

class VendasErpController extends Controller{

  public function vendaserp(){
    $adquirentes = DB::table('cliente_operadora')
      ->join('adquirentes', 'cliente_operadora.COD_ADQUIRENTE', 'adquirentes.CODIGO')
      ->select('adquirentes.*')
      ->where('cliente_operadora.COD_CLIENTE', '=', session('codigologin'))
      ->distinct('COD_ADQUIRENTE')
      ->get();

    $meio_captura = MeioCaptura::all();
    $status_conciliacao = StatusConciliacaoModel::where('CODIGO', '!=', 4)
      ->orderBy('STATUS_CONCILIACAO', 'ASC')
      ->get();
    $grupos_clientes = GruposClientesModel::where('COD_CLIENTE', '=', session('codigologin'))
      ->get();

    return view('vendas.vendaserp')
      ->with('adquirentes', $adquirentes)
      ->with('grupos_clientes', $grupos_clientes)
      ->with('meio_captura', $meio_captura)
      ->with('status_conciliacao', $status_conciliacao);
  }

  public function buscarVendasErp() {
    $quantidadesPermitidas = [10, 20, 50, 100, 200];

    $data_final = Request::input('data_final');
    $data_inicial = Request::input('data_inicial');
    $adquirente = Request::input('arrayAdquirentes');
    $conciliacao = Request::input('status_conciliacao');
    $quantidadePorPagina = Request::input('por_pagina', 10);
    $quantidadePorPagina = in_array($quantidadePorPagina, $quantidadesPermitidas) ? $quantidadePorPagina : 10;
    
    $query = DB::table('vendas_erp')
      ->join('modalidade', 'vendas_erp.COD_MODALIDADE', '=', 'modalidade.CODIGO')
      ->leftJoin('produto_web', 'vendas_erp.COD_PRODUTO', '=', 'produto_web.CODIGO')
      ->leftJoin('meio_captura', 'vendas_erp.COD_MEIO_CAPTURA', '=', 'meio_captura.CODIGO')
      ->leftJoin('status_conciliacao', 'vendas_erp.COD_STATUS_CONCILIACAO', '=', 'status_conciliacao.CODIGO')
      ->select('vendas_erp.*', 'vendas_erp.CODIGO as COD', 'modalidade.*', 'produto_web.*', 'meio_captura.DESCRICAO as MEIOCAPTURA', 'status_conciliacao.STATUS_CONCILIACAO')
      ->where('vendas_erp.COD_CLIENTE', '=', session('codigologin'))
      ->where(function($query) {
        if(Request::only('arrayStatusConciliacao') != null){
          $status_conciliacao = Request::only('arrayStatusConciliacao');
          foreach($status_conciliacao['arrayStatusConciliacao'] as $status_conciliacaoo) {
            $query->orWhere('COD_STATUS_CONCILIACAO', '=', $status_conciliacaoo);
          }
        }
      })
      ->where(function($query) {
        if(Request::only('arrayAdquirentes') != null){
          $adquirentes = Request::only('arrayAdquirentes');
          foreach ($adquirentes['arrayAdquirentes'] as $adquirente) {
            $query->orWhere('COD_OPERADORA', '=', $adquirente);
          }
        }
      })
      ->where(function($query) {
        if(Request::only('arrayMeioCaptura') != null){
          $meiocaptura = Request::only('arrayMeioCaptura');
          foreach ($meiocaptura['arrayMeioCaptura'] as $mcaptura) {
            $query->orWhereNull('COD_MEIO_CAPTURA')->orWhere('COD_MEIO_CAPTURA', '=', $mcaptura);
          }
        }
      })
      ->where(function($query) {
        if(Request::only('data_inicial') != null){
          $data_inicial = Request::only('data_inicial');
          $data_final = Request::only('data_final');
          $query->whereBetween('DATA_VENDA', [$data_inicial['data_inicial'], $data_final['data_final']]);
        }
      })
      ->orderBy('DATA_VENDA');

    $liquidezTotalPacela = $query->get()->sum('VALOR_LIQUIDO_PARCELA');
    $totalVendas = $query->get()->sum('TOTAL_VENDA');
    $paginaVendas = $query->paginate($quantidadePorPagina);

    $dados = [
        'vendas' => $paginaVendas,
        'totais' => [
          'TOTAL_VENDAS' => $totalVendas,
          'LIQUIDEZ_TOTAL_PARCELA' => $liquidezTotalPacela,
        ]
    ];

    
    $adquirentes = AdquirentesModel::orderBy('ADQUIRENTE', 'ASC')->get();
    $status_conciliacao = StatusConciliacaoModel::where('CODIGO', '!=', 4)->orderBy('STATUS_CONCILIACAO', 'ASC')->get();
    $grupos_clientes = GruposClientesModel::where('COD_CLIENTE', '=', session('codigologin'))->get();
    
    session()->put('vendas_erp', $paginaVendas);
    $dados = json_encode($dados);

    return $dados;
  }

  public function downloadTable(){
    $vendas = session()->get('vendas_erp');
    set_time_limit(600);

    $pdf = \PDF::loadView('vendas.tabela_vendas', compact('vendas'));
    return $pdf->setPaper('A4', 'landscape')
              ->download('prev_pag.pdf');
  }
}
