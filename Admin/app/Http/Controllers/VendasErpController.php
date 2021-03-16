<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Filters\VendasErpFilter;
use App\Filters\VendasErpSubFilter;
use App\Exports\VendasErpExport;
use App\ClienteModel;
use App\VendasErpModel;
use App\MeioCaptura;
use App\JustificativaModel;
use App\StatusConciliacaoModel;
use App\StatusFinanceiroModel;
use App\GruposClientesModel;
use App\AdquirentesModel;
use App\ClienteOperadoraModel;

class VendasErpController extends Controller {
  public function index(){
    $erp = ClienteModel::select(
      [
        'erp.ERP',
        'erp.TITULO_CAMPO_ADICIONAL1 as TITULO_CAMPO1',
        'erp.TITULO_CAMPO_ADICIONAL2 as TITULO_CAMPO2',
        'erp.TITULO_CAMPO_ADICIONAL3 as TITULO_CAMPO3'
      ])
      ->leftJoin('erp', 'clientes.COD_ERP', 'erp.CODIGO')
      ->where('clientes.CODIGO', session('codigologin'))
      ->first();

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

    return view('vendas.vendas-erp')
      ->with([
        'status_conciliacao' => $status_conciliacao,
        'status_financeiro' => $status_financeiro,
        'empresas' => $empresas,
        'adquirentes' => $adquirentes,
        'bandeiras' => $bandeiras,
        'modalidades' => $modalidades,
        'erp' => $erp,
      ]);
  }

  public function search(Request $request) {
    $allowedPerPage = [10, 20, 50, 100, 200];
    $perPage = $request->input('por_pagina', 10);
    $perPage = in_array($perPage, $allowedPerPage) ? $perPage : 10;
    $filters = $request->all();
    $filters['cliente_id'] = session('codigologin');

    try {
      $query = VendasErpFilter::filter($filters)
          ->getQuery();

      $sales = (clone $query)->paginate($perPage);
      $totals = [
          'TOTAL_BRUTO' => (clone $query)->sum(DB::raw('coalesce(`vendas_erp`.`VALOR_VENDA_PARCELA`, `vendas_erp`.`TOTAL_VENDA`)')),
          'TOTAL_LIQUIDO' => (clone $query)->sum('VALOR_LIQUIDO_PARCELA'),
      ];
      $totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];

      return response()->json([
          'vendas' => $sales,
          'totais' => $totals,
      ]);
    } catch(Exception $e) {
      return response()->json([
          'message' => 'Não foi possível realizar a consulta em Vendas ERP.',
      ], 500);
    }
  }

  public function filter(Request $request) {
    $allowedPerPage = [10, 20, 50, 100, 200];
    $perPage = $request->input('por_pagina', 10);
    $perPage = in_array($perPage, $allowedPerPage) ? $perPage : 10;
    $filters= $request->input('filters');
    $filters['cliente_id'] = session('codigologin');
    $subfilters = $request->input('subfilters');

    try {
        $query = VendasErpSubFilter::subfilter($filters, $subfilters)
            ->getQuery();

        $sales = (clone $query)->paginate($perPage);
        $totals = [
          'TOTAL_BRUTO' => (clone $query)->sum(DB::raw('coalesce(`VALOR_VENDA_PARCELA`, `TOTAL_VENDA`)')),
          'TOTAL_LIQUIDO' => (clone $query)->sum('VALOR_LIQUIDO_PARCELA'),
        ];
        $totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];

        return response()->json([
            'vendas' => $sales,
            'totais' => $totals,
        ]);
    } catch(Exception $e) {
        return response()->json([
            'message' => 'Não foi possível realizar a consulta em Vendas ERP.',
        ], 500);
    }
  }

  public function justify(Request $request) {
    $ids = $request->input('id') ?? [];
    $idJustificativa = $request->input('justificativa') ?? null;

    $justificativa = JustificativaModel::where('CODIGO', $idJustificativa)
      ->where('COD_CLIENTE', session('codigologin'))
      ->first();

    if(is_null($justificativa)) {
      return response()->json([
          'status' => 'erro',
          'mensagem' => 'A justificativa deve ser informada.'
      ], 400);
    }

    $statusNaoConciliado = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;
    $statusJustificado = StatusConciliacaoModel::justificada()->first();
    $now = new DateTime("now", new DateTimeZone('America/Sao_Paulo'));

    $validIds = VendasErpModel::whereIn('CODIGO', $ids)
      ->where('COD_CLIENTE', session('codigologin'))
      ->where('COD_STATUS_CONCILIACAO', $statusNaoConciliado)
      ->pluck('CODIGO')
      ->toArray();

    VendasErpModel::whereIn('CODIGO', $validIds)
      ->update([
        'JUSTIFICATIVA' => $justificativa->JUSTIFICATIVA,
        'COD_STATUS_CONCILIACAO' => $statusJustificado->CODIGO,
        'DATA_CONCILIACAO' => $now->format('Y-m-d'),
        'HORA_CONCILIACAO' => $now->format('H:i:s')
      ]);

    $sales = VendasErpFilter::filter([
      'id_erp' => $validIds,
      'cliente_id' => session('codigologin'),
      'status_conciliacao' => [$statusJustificado->CODIGO],
    ])->getQuery()->get();
    $totals = [
      'TOTAL_BRUTO' => $sales->sum('VALOR_VENDA'),
      'TOTAL_LIQUIDO' => $sales->sum('VALOR_LIQUIDO_PARCELA'),
    ];
    $totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];

    return response()->json([
      'status' => 'sucesso',
      'mensagem' => 'As vendas foram justificadas com sucesso.',
      'vendas' => $sales,
      'totais' => $totals,
    ], 200);
  }

  public function unjustify(Request $request) {
    $ids = $request->input('id') ?? [];

    $statusJustificado = StatusConciliacaoModel::justificada()->first()->CODIGO;
    $statusNaoConciliado = StatusConciliacaoModel::naoConciliada()->first();

    $validIds = VendasErpModel::whereIn('CODIGO', $ids)
      ->where('COD_CLIENTE', session('codigologin'))
      ->where('COD_STATUS_CONCILIACAO', $statusJustificado)
      ->pluck('CODIGO')
      ->toArray();

    VendasErpModel::whereIn('CODIGO', $validIds)
      ->update([
        'JUSTIFICATIVA' => null,
        'COD_STATUS_CONCILIACAO' => $statusNaoConciliado->CODIGO,
        'DATA_CONCILIACAO' => null,
        'HORA_CONCILIACAO' => null
      ]);

    $sales = VendasErpFilter::filter([
      'id_erp' => $validIds,
      'cliente_id' => session('codigologin'),
      'status_conciliacao' => [$statusNaoConciliado->CODIGO],
    ])->getQuery()->get();
    $totals = [
      'TOTAL_BRUTO' => $sales->sum('VALOR_VENDA'),
      'TOTAL_LIQUIDO' => $sales->sum('VALOR_LIQUIDO_PARCELA'),
    ];
    $totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];

    return response()->json([
      'status' => 'sucesso',
      'mensagem' => 'As vendas foram desjustificadas com sucesso.',
      'vendas' => $sales,
      'totais' => $totals
    ], 200);
  }

  public function export(Request $request) {
    set_time_limit(300);

    $headers = ClienteModel::select(
      [
        'erp.TITULO_CAMPO_ADICIONAL1 as TITULO_CAMPO1',
        'erp.TITULO_CAMPO_ADICIONAL2 as TITULO_CAMPO2',
        'erp.TITULO_CAMPO_ADICIONAL3 as TITULO_CAMPO3'
      ])
      ->leftJoin('erp', 'clientes.COD_ERP', 'erp.CODIGO')
      ->where('clientes.CODIGO', session('codigologin'))
      ->first();

    $sort = [
      'column' => $request->input('sort_column', 'DATA_VENDA'),
      'direction' => $request->input('sort_direction', 'asc')
    ];
    $filters = $request->except(['_token', 'sort_column', 'sort_direction']);
    $filters['sort'] = $sort;
    $subfilters = $request->except(['_token']);
    Arr::set($filters, 'cliente_id', session('codigologin'));
    return (new VendasErpExport($filters, $subfilters, $headers))->download('vendas_erp_'.time().'.xlsx');
  }
}
