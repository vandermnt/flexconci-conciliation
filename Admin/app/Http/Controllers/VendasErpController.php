<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use DB;

use App\VendasErpModel;
use App\MeioCaptura;
use App\StatusConciliacaoModel;
use App\StatusFinanceiroModel;
use App\GruposClientesModel;
use App\AdquirentesModel;
use App\ClienteOperadoraModel;

class VendasErpController extends Controller {

  public function vendaserp(){
    $status_conciliacao = StatusConciliacaoModel::orderBy('STATUS_CONCILIACAO')
      ->get();

    $status_financeiro = StatusFinanceiroModel::orderBy('STATUS_FINANCEIRO')
      ->get();

    $empresas = GruposClientesModel::select(['CODIGO', 'NOME_EMPRESA', 'CNPJ'])
      ->where('COD_CLIENTE', session('codigologin'))
      ->orderBy('NOME_EMPRESA')
      ->get();

    $adquirentes = ClienteOperadoraModel::select([
        'adquirentes.CODIGO',
        'adquirentes.ADQUIRENTE',
        'adquirentes.IMAGEM'
      ])
      ->join('adquirentes', 'COD_ADQUIRENTE', 'adquirentes.CODIGO')
      ->where('COD_CLIENTE', '=', session('codigologin'))
      ->distinct()
      ->orderBy('ADQUIRENTE')
      ->get();

    $bandeiras = VendasErpModel::select([
        'bandeira.CODIGO',
        'bandeira.BANDEIRA',
        'bandeira.IMAGEM'
      ])
      ->leftJoin('bandeira', 'COD_BANDEIRA', 'bandeira.CODIGO')
      ->where('COD_CLIENTE', session('codigologin'))
      ->whereNotNull('bandeira.BANDEIRA')
      ->distinct()
      ->orderBy('BANDEIRA')
      ->get();

    $modalidades = VendasErpModel::select([
        'modalidade.CODIGO',
        'modalidade.DESCRICAO'
      ])
      ->leftJoin('modalidade', 'modalidade.CODIGO', 'COD_MODALIDADE')
      ->where('COD_CLIENTE', session('codigologin'))
      ->whereNotNull('modalidade.DESCRICAO')
      ->distinct()
      ->orderBy('DESCRICAO')
      ->get();

    return view('vendas.vendaserp')
      ->with([
        'status_conciliacao' => $status_conciliacao,
        'status_financeiro' => $status_financeiro,
        'empresas' => $empresas,
        'adquirentes' => $adquirentes,
        'bandeiras' => $bandeiras,
        'modalidades' => $modalidades,
      ]);
  }

  public function buscarVendasErp(Request $request) {
    $quantidadesPermitidas = [10, 20, 50, 100, 200, '*'];
    $inputs = [
      'empresas',
      'arrayAdquirentes',
      'bandeiras',
      'modalidades',
      'arrayMeioCaptura',
      'id_erp',
      'status_conciliacao',
    ];
    $dicionarioInput = [
      'empresas' => 'grupos_clientes.CODIGO',
      'arrayAdquirentes' => 'vendas_erp.COD_OPERADORA',
      'bandeiras' => 'bandeira.CODIGO',
      'modalidades' => 'modalidade.CODIGO',
      'id_erp' => 'vendas_erp.CODIGO',
      'status_conciliacao' => 'status_conciliacao.CODIGO',
      'status_financeiro' => 'status_financeiro.CODIGO',
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
          'vendas_erp.CODIGO as ID_ERP',
          'grupos_clientes.NOME_EMPRESA',
          'grupos_clientes.CNPJ',
          'vendas_erp.DATA_VENDA',
          'vendas_erp.DATA_VENCIMENTO',
          'adquirentes.ADQUIRENTE',
          'adquirentes.IMAGEM as ADQUIRENTE_IMAGEM',
          'bandeira.BANDEIRA',
          'bandeira.IMAGEM as BANDEIRA_IMAGEM',
          'modalidade.DESCRICAO as MODALIDADE',
          'vendas_erp.NSU',
          'vendas_erp.CODIGO_AUTORIZACAO',
          'vendas_erp.TID',
          DB::raw('coalesce(`vendas_erp`.`VALOR_VENDA_PARCELA`, `vendas_erp`.`TOTAL_VENDA`) as TOTAL_VENDA'),
          'vendas_erp.TAXA',
          DB::raw('
            (coalesce(`vendas_erp`.`VALOR_VENDA_PARCELA`, `vendas_erp`.`TOTAL_VENDA`) - `vendas_erp`.`VALOR_LIQUIDO_PARCELA`)
              as `VALOR_TAXA`'),
          'vendas_erp.VALOR_LIQUIDO_PARCELA',
          'vendas_erp.PARCELA',
          'vendas_erp.TOTAL_PARCELAS',
          'lista_bancos.BANCO as BANCO',
          'lista_bancos.IMAGEM_LINK as BANCO_IMAGEM',
          'vendas_erp.AGENCIA',
          'vendas_erp.CONTA_CORRENTE',
          'produto_web.PRODUTO_WEB as PRODUTO',
          'meio_captura.DESCRICAO as MEIOCAPTURA',
          'status_conciliacao.STATUS_CONCILIACAO',
          'status_financeiro.STATUS_FINANCEIRO',
          'vendas_erp.JUSTIFICATIVA',
          'vendas_erp.CAMPO_ADICIONAL1 as CAMPO1',
          'vendas_erp.CAMPO_ADICIONAL2 as CAMPO2',
          'vendas_erp.CAMPO_ADICIONAL3 as CAMPO3',
          'vendas_erp.DATA_IMPORTACAO',
          'vendas_erp.HORA_IMPORTACAO',
          'vendas_erp.DATA_CONCILIACAO',
          'vendas_erp.HORA_CONCILIACAO',
        ]
      )
      ->leftJoin('grupos_clientes', 'grupos_clientes.CODIGO', 'vendas_erp.COD_GRUPO_CLIENTE')
      ->leftJoin('adquirentes', 'adquirentes.CODIGO', 'vendas_erp.COD_OPERADORA')
      ->leftJoin('bandeira', 'bandeira.CODIGO', 'vendas_erp.COD_BANDEIRA')
      ->leftJoin('modalidade', 'modalidade.CODIGO', 'vendas_erp.COD_MODALIDADE')
      ->leftJoin('produto_web', 'produto_web.CODIGO', 'vendas_erp.COD_PRODUTO')
      ->leftJoin('meio_captura', 'vendas_erp.COD_MEIO_CAPTURA', 'meio_captura.CODIGO')
      ->leftJoin('status_conciliacao', 'vendas_erp.COD_STATUS_CONCILIACAO', 'status_conciliacao.CODIGO')
      ->leftJoin('status_financeiro', 'vendas_erp.COD_STATUS_FINANCEIRO', 'status_financeiro.CODIGO')
      ->leftJoin('lista_bancos', 'vendas_erp.COD_BANCO', 'lista_bancos.CODIGO')
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

    $liquidezTotalPacela = $query->sum('VALOR_LIQUIDO_PARCELA');
    $totalVendas = $query->sum(DB::raw('coalesce(`vendas_erp`.`VALOR_VENDA_PARCELA`, `vendas_erp`.`TOTAL_VENDA`)'));
    $totalTaxa = DB::table('vendas_erp_sub')
      ->selectRaw('VALOR_TAXA')
      ->from(DB::raw('('.$query->toSql().') as vendas_erp_sub'))
      ->mergeBindings($query->getQuery())
      ->sum('VALOR_TAXA');

    $quantidadePorPagina = $quantidadePorPagina === '*' ? $query->count() : $quantidadePorPagina;
    $paginacaoVendas = $query->paginate($quantidadePorPagina);
    $vendas = $paginacaoVendas->getCollection();
    $paginacao = $paginacaoVendas->toArray();
    unset($paginacao['data']);

    $json = [
      'vendas' => $vendas,
      'paginacao' => $paginacao,
      'totais' => [
        'TOTAL_VENDAS' => $totalVendas,
        'LIQUIDEZ_TOTAL_PARCELA' => $liquidezTotalPacela,
        'TOTAL_TAXA' => $totalTaxa,
      ]
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
