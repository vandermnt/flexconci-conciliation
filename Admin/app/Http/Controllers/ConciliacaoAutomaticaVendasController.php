<?php

namespace App\Http\Controllers;

use Request;
use DB;
use App\ModalidadesModel;
use App\AdquirentesModel;
use App\BandeiraModel;
use App\StatusConciliacaoModel;

class ConciliacaoAutomaticaVendasController extends Controller{

  public function conciliacaoAutomatica(){
    $modalidades = ModalidadesModel::orderBy('DESCRICAO', 'ASC')->get();
    $adquirentes = DB::table('cliente_operadora')
    ->join('adquirentes', 'cliente_operadora.COD_ADQUIRENTE', 'adquirentes.CODIGO')
    ->select('adquirentes.*')
    ->where('cliente_operadora.COD_CLIENTE', '=', session('codigologin'))
    ->distinct('COD_ADQUIRENTE')
    ->get();

    $bandeiras = DB::table('clientes_bandeiras')
    ->join('bandeira', 'clientes_bandeiras.COD_BANDEIRA', 'bandeira.CODIGO')
    ->select('bandeira.*')
    ->where('clientes_bandeiras.COD_CLIENTE', '=', session('codigologin'))
    ->get();

    $status_conciliacao = StatusConciliacaoModel::where('CODIGO', '!=', 4)->orderBy('STATUS_CONCILIACAO', 'ASC')->get();

    return view('conciliacao.conciliacao-automatica-vendas')->with('adquirentes', $adquirentes)
    ->with('modalidades', $modalidades)
    ->with('bandeiras', $bandeiras)
    ->with('status_conciliacao', $status_conciliacao);
  }

  public function conciliarManualmente(){
    $vendas = DB::table('vendas')
    ->join('modalidade', 'vendas.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
    ->leftJoin('bandeira', 'vendas.COD_BANDEIRA', '=', 'bandeira.CODIGO')
    ->leftJoin('adquirentes', 'vendas.ADQID', '=', 'adquirentes.CODIGO')
    ->leftJoin('lista_bancos', 'vendas.BANCO', '=', 'lista_bancos.CODIGO')
    ->leftJoin('produto_web', 'vendas.COD_PRODUTO', '=', 'produto_web.CODIGO')
    ->leftJoin('meio_captura', 'vendas.COD_MEIO_CAPTURA', '=', 'meio_captura.CODIGO')
    ->select('vendas.*', 'vendas.CODIGO as COD', 'modalidade.*', 'produto_web.*', 'lista_bancos.BANCO', 'meio_captura.DESCRICAO as MEIOCAPTURA', 'adquirentes.IMAGEM as IMAGEMAD', 'bandeira.IMAGEM as IMAGEMBAD')
    ->where('vendas.COD_CLIENTE', '=', session('codigologin'))
    ->where(function($query) {
      if(Request::only('arrayStatusConciliacao') != null){
        $status_conciliacao = Request::only('arrayStatusConciliacao');
        foreach($status_conciliacao['arrayStatusConciliacao'] as $status_conciliacaoo) {
          $query->orWhere('COD_STATUS_CONCILIACAO', '=', $status_conciliacaoo);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('arrayModalidade') != null){
        $modalidades = Request::only('arrayModalidade');
        foreach($modalidades['arrayModalidade'] as $modalidade) {
          $query->orWhere('CODIGO_MODALIDADE', '=', $modalidade);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('arrayAdquirentes') != null){
        $adquirentes = Request::only('arrayAdquirentes');
        foreach ($adquirentes['arrayAdquirentes'] as $adquirente) {
          $query->orWhere('ADQID', '=', $adquirente);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('arrayBandeira') != null){
        $bandeiras = Request::only('arrayBandeira');
        foreach ($bandeiras['arrayBandeira'] as $bandeira) {
          $query->orWhere('COD_BANDEIRA', '=', $bandeira);
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
    ->orderBy('DATA_VENDA')
    ->get();
    
    $vendaserp = DB::table('vendas_erp')
    ->join('modalidade', 'vendas_erp.COD_MODALIDADE', '=', 'modalidade.CODIGO')
    ->leftJoin('produto_web', 'vendas_erp.COD_PRODUTO', '=', 'produto_web.CODIGO')
    ->leftJoin('meio_captura', 'vendas_erp.COD_MEIO_CAPTURA', '=', 'meio_captura.CODIGO')
    ->select('vendas_erp.*', 'vendas_erp.CODIGO as COD', 'modalidade.*', 'produto_web.*', 'meio_captura.DESCRICAO as MEIOCAPTURA')
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
      if(Request::only('arrayBandeira') != null){
        $bandeiras = Request::only('arrayBandeira');
        foreach ($bandeiras['arrayBandeira'] as $bandeira) {
          $query->orWhere('COD_BANDEIRA', '=', $bandeira);
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
      if(Request::only('data_inicial') != null){
        $data_inicial = Request::only('data_inicial');
        $data_final = Request::only('data_final');
        $query->whereBetween('DATA_VENDA', [$data_inicial['data_inicial'], $data_final['data_final']]);
      }
    })
    ->orderBy('DATA_VENDA')
    ->get();

    return json_encode([$vendas]);
  }
}
