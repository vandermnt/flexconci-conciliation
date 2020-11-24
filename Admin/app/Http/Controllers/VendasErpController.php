<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use DB;

use App\VendasErpModel;
use App\MeioCaptura;
use App\StatusConciliacaoModel;
use App\GruposClientesModel;
use App\AdquirentesModel;

class VendasErpController extends Controller {

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

  public function buscarVendasErp(Request $request) {
    $quantidadesPermitidas = [10, 20, 50, 100, 200, '*'];
    $inputs = [
      'arrayMeioCaptura',
      'arrayAdquirentes',
      'status_conciliacao',
      'identificador_pagamento',
      'cod_autorizacao',
      'nsu'
    ];
    $dicionarioInput = [
      'arrayMeioCaptura' => 'meio_captura.CODIGO',
      'arrayAdquirentes' => 'vendas_erp.COD_OPERADORA',
      'status_conciliacao' => 'status_conciliacao.CODIGO',
      'identificador_pagamento' => 'vendas_erp.IDENTIFICADOR_PAGAMENTO',
      'cod_autorizacao' => 'vendas_erp.CODIGO_AUTORIZACAO',
      'nsu' => 'vendas_erp.NSU'
    ];

    $dados = array_filter($request->only($inputs));
    $datas = [
      $request->input('data_inicial') ?? '2020-11-01',
      $request->input('data_final') ?? date('Y-m-d')
    ];
    $quantidadePorPagina = $request->input('por_pagina', 10);
    $quantidadePorPagina = in_array($quantidadePorPagina, $quantidadesPermitidas) ? $quantidadePorPagina : 10;


    $query = VendasErpModel::select(
        [
          'vendas_erp.CODIGO AS COD',
          'vendas_erp.DATA_VENDA',
          'vendas_erp.DATA_VENCIMENTO',
          'vendas_erp.NSU',
          'vendas_erp.TOTAL_VENDA',
          'vendas_erp.NSU',
          'vendas_erp.PARCELA',
          'vendas_erp.TOTAL_PARCELAS',
          'vendas_erp.VALOR_LIQUIDO_PARCELA',
          'vendas_erp.DESCRICAO_TIPO_PRODUTO',
          'vendas_erp.CODIGO_AUTORIZACAO',
          'vendas_erp.IDENTIFICADOR_PAGAMENTO',
          'vendas_erp.JUSTIFICATIVA',
          'meio_captura.DESCRICAO AS MEIOCAPTURA',
          'status_conciliacao.STATUS_CONCILIACAO',
        ]
      )
      ->leftJoin('meio_captura', 'vendas_erp.COD_MEIO_CAPTURA', '=', 'meio_captura.CODIGO')
      ->leftJoin('status_conciliacao', 'vendas_erp.COD_STATUS_CONCILIACAO', '=', 'status_conciliacao.CODIGO')
      ->where('vendas_erp.COD_CLIENTE', session('codigologin'))
      ->whereBetween('vendas_erp.DATA_VENDA', $datas)
      ->orderBy('vendas_erp.DATA_VENDA');

    foreach($dados as $chave => $valor) {
      if(is_array($valor)) {
        $query->whereIn($dicionarioInput[$chave], $valor);
      } else {
        $query->where($dicionarioInput[$chave], $valor);
      }
    }

    $vendas = $query->get();
    $liquidezTotalPacela = $vendas->sum('VALOR_LIQUIDO_PARCELA');
    $totalVendas = $vendas->sum('TOTAL_VENDA');
    
    $quantidadePorPagina = $quantidadePorPagina === '*' ? $vendas->count() : $quantidadePorPagina;
    $paginacaoVendas = $query->paginate($quantidadePorPagina);
    $vendas = $paginacaoVendas->getCollection(); 
    $paginacao = $paginacaoVendas->toArray();
    unset($paginacao['data']);
    
    $json = [];
    $json['vendas'] = $vendas;
    $json['paginacao'] = $paginacao;
    $json['totais'] = [
      'TOTAL_VENDAS' => $totalVendas,
      'LIQUIDEZ_TOTAL_PARCELA' => $liquidezTotalPacela,
    ];

    session()->put('vendas_erp', $vendas);

    return response()->json($json);
  }

  public function downloadTable(){
    $vendas = session()->get('vendas_erp');
    set_time_limit(600);

    $pdf = \PDF::loadView('vendas.tabela_vendas', compact('vendas'));
    return $pdf->setPaper('A4', 'landscape')
              ->download('prev_pag.pdf');
  }
}
