<?php

namespace App\Http\Controllers;

use Request;
use DB;
use App\VendasModel;
use App\StatusConciliacaoModel;
use App\GruposClientesModel;
use App\VendasErpModel;

class ConciliacaoAutomaticaVendasController extends Controller{

  public function conciliacaoAutomatica(){
    $grupos_clientes = GruposClientesModel::where('COD_CLIENTE', '=', session('codigologin'))->get();

    $justificativas = DB::table("justificativa")
    ->where('COD_CLIENTE', '=', session('codigologin'))->get();

    $cliente = DB::table('clientes')
    ->leftJoin('erp', 'clientes.COD_ERP', '=', 'erp.CODIGO')
    ->select('erp.ERP')
    ->where('clientes.CODIGO', '=', session('codigologin'))
    ->first();

    $erp = $cliente->ERP;

    $status_conciliacao = StatusConciliacaoModel::where('CODIGO', '!=', 4)->orderBy('STATUS_CONCILIACAO', 'ASC')->get();

    return view('conciliacao.conciliacao-automatica-vendas')->with('adquirentes', $adquirentes)
    ->with('grupos_clientes', $grupos_clientes)
    ->with('justificativas', $justificativas)
    ->with('erp', $erp)
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
    ->select('vendas.*', 'vendas.CODIGO as COD', 'modalidade.DESCRICAO', 'produto_web.PRODUTO_WEB', 'lista_bancos.BANCO', 'meio_captura.DESCRICAO as MEIOCAPTURA', 'adquirentes.IMAGEM as IMAGEMAD', 'bandeira.IMAGEM as IMAGEMBAD')
    ->where('vendas.COD_CLIENTE', '=', session('codigologin'))
    ->where(function($query) {
      if(Request::only('array') != null){
        $empresas = Request::only('array');
        foreach ($empresas['array'] as $cnpj) {
          $query->orWhere('CNPJ', '=', $cnpj);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('arrayStatusConciliacao') != null){
        $status_conciliacao = Request::only('arrayStatusConciliacao');
        foreach($status_conciliacao['arrayStatusConciliacao'] as $status_conciliacaoo) {
          $query->orWhere('COD_STATUS_CONCILIACAO', '=', $status_conciliacaoo);
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
    ->leftJoin('status_conciliacao', 'vendas_erp.COD_STATUS_CONCILIACAO', '=', 'status_conciliacao.CODIGO')
    ->leftJoin('meio_captura', 'vendas_erp.COD_MEIO_CAPTURA', '=', 'meio_captura.CODIGO')
    ->select('vendas_erp.*', 'vendas_erp.CODIGO as COD', 'status_conciliacao.STATUS_CONCILIACAO', 'modalidade.DESCRICAO', 'produto_web.PRODUTO_WEB', 'meio_captura.DESCRICAO as MEIOCAPTURA')
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
      if(Request::only('array') != null){
        $empresas = Request::only('array');
        foreach ($empresas['array'] as $cnpj) {
          $query->orWhere('CNPJ', '=', $cnpj);
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

    $count_vendaserp = $vendaserp->count();
    $vendaserpp = $vendaserp->get();

    return json_encode([$vendas, $vendaserpp, $count_vendaserp]);
  }

  public function conciliacaoJustificadaVenda(){
    $cod_venda = Request::only('cod_venda');

    $venda = VendasModel::where('CODIGO', '=',$cod_venda)
    ->where('COD_CLIENTE', '=', session('codigologin'))
    ->first();

    $venda->COD_STATUS_CONCILIACAO = 3;

    $venda->save();

    return json_encode("CERTO");
  }

  public function conciliacaoJustificadaVendaErp(){
    $cod_venda = Request::only('cod_venda_erp');

    $venda = VendasErpModel::where('CODIGO', '=', $cod_venda['cod_venda_erp'])
    ->where('COD_CLIENTE', '=', session('codigologin'))
    ->first();

    $venda->COD_STATUS_CONCILIACAO = 3;

    $venda->save();

    return json_encode("CERTO");
  }

  public function saveConciliacao(){
    $codigo_venda = Request::only('codigo_venda');
    $codigo_vendaerp = Request::only('codigo_vendaerp');

    $venda = VendasModel::where('CODIGO', '=', $codigo_venda['codigo_venda'])
    ->where('COD_CLIENTE', '=', session('codigologin'))
    ->update(['COD_STATUS_CONCILIACAO' => 6]);

    $vendaerp = VendasErpModel::where('CODIGO', '=', $codigo_vendaerp['codigo_vendaerp'])
    ->where('COD_CLIENTE', '=', session('codigologin'))
    ->update(['COD_STATUS_CONCILIACAO' => 6]);
    //
    // $venda->COD_STATUS_CONCILIACAO = 6;
    // $vendaerp->COD_STATUS_CONCILIACAO = 6;
    //
    // $venda->save();
    // $vendaerp->save();

    // return response()->json(200);
    return json_encode($venda);
  }

}
