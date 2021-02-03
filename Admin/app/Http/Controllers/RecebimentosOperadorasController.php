<?php

namespace App\Http\Controllers;

use App\Filters\RecebimentosFilter;
use App\Filters\RecebimentosSubFilter;
use App\GruposClientesModel;
use App\ClienteOperadoraModel;
use App\DomicilioClienteModel;
use App\Exports\RecebimentosOperadorasExport;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RecebimentosOperadorasController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $empresas = GruposClientesModel::select([
      'CODIGO',
      'NOME_EMPRESA',
      'CNPJ'
    ])
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

    $domicilios_bancarios = DomicilioClienteModel::select([
      'domicilio_cliente.CODIGO',
      'lista_bancos.NOME_WEB as BANCO',
      'AGENCIA',
      'CONTA'
    ])
      ->leftJoin('lista_bancos', 'lista_bancos.CODIGO', 'COD_BANCO')
      ->where('COD_CLIENTE', session('codigologin'))
      ->orderBy('lista_bancos.NOME_WEB')
      ->get();
    
    return view('recebimentos.recebimentos-operadoras')->with([
      'empresas' => $empresas,
      'adquirentes' => $adquirentes,
      'domicilios_bancarios' => $domicilios_bancarios,
    ]);
  }

  public function search(Request $request) {
    $allowedPerPage = [10, 20, 50, 100, 200];
    $perPage = $request->input('por_pagina', 10);
    $perPage = in_array($perPage, $allowedPerPage) ? $perPage : 10;
    $filters = $request->all();
    $filters['cliente_id'] = session('codigologin');

    try {
      $query = RecebimentosFilter::filter($filters)
        ->getQuery()
        ->orderBy('DATA_PAGAMENTO');

      $payments = (clone $query)->paginate($perPage);
      $totals = [
        'TOTAL_BRUTO' => (clone $query)->sum('VALOR_BRUTO'),
        'TOTAL_LIQUIDO' => (clone $query)->sum('VALOR_LIQUIDO'),
        'PAG_NORMAL' => 0,
        'PAG_ANTECIPADO' => 0,
        'PAG_AVULSO' => 0,
        'TOTAL_ANTECIPACAO' => 0,
        'TOTAL_DESPESAS' => 0

      ];
      $totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];

      return response()->json([
        'recebimentos' => $payments,
        'totais' => $totals,
      ]);
    } catch(Exception $e) {
      return response()->json([
        'message' => 'Não foi possível realizar a consulta em Recebimentos Operadoras.',
      ], 500);
    }
  }

  public function filter(Request $request) {
    $allowedPerPage = [10, 20, 50, 100, 200];
    $perPage = $request->input('por_pagina', 10);
    $perPage = in_array($perPage, $allowedPerPage) ? $perPage : 10;
    $filters = $request->input('filters');
    $filters['cliente_id'] = session('codigologin');
    $subfilters = $request->input('subfilters');

    try {
      $query = RecebimentosSubFilter::subfilter($filters, $subfilters)
          ->getQuery()
          ->orderBy('DATA_PAGAMENTO');

      $payments = (clone $query)->paginate($perPage);
      $totals = [
        'TOTAL_BRUTO' => (clone $query)->sum('VALOR_BRUTO'),
        'TOTAL_LIQUIDO' => (clone $query)->sum('VALOR_LIQUIDO'),
        'PAG_NORMAL' => 0,
        'PAG_ANTECIPADO' => 0,
        'PAG_AVULSO' => 0,
        'TOTAL_ANTECIPACAO' => 0,
        'TOTAL_DESPESAS' => 0

      ];
      $totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];

      return response()->json([
        'recebimentos' => $payments,
        'totais' => $totals,
      ]);
    } catch(Exception $e) {
        return response()->json([
          'message' => 'Não foi possível realizar a consulta em Recebimentos Operadoras.',
        ], 500);
    }
  }

  public function export(Request $request) {
    set_time_limit(300);

    $filters = $request->except(['_token']);
    $subfilters = $request->except(['_token']);
    Arr::set($filters, 'cliente_id', session('codigologin'));
    return (new RecebimentosOperadorasExport($filters, $subfilters))->download('recebimentos_operadoras_'.time().'.xlsx');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
      //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
      //
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
      //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
      //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
      //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
      //
  }
}
