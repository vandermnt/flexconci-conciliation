<?php

namespace App\Http\Controllers;
use App\AdquirentesModel;
use App\ClienteModel;
use App\VendasErpModel;
use App\PagamentoOperadoraModel;
use App\VendasModel;

use Illuminate\Http\Request;

class AdmDesconciliacaoController extends Controller {

  public function index() {
    $clientes = ClienteModel::orderBy('NOME_FANTASIA', 'asc')->get();
    $operadoras = AdquirentesModel::orderBy('ADQUIRENTE', 'asc')->get();

    return view('administrativo.desconciliacao')
    ->with('clientes', $clientes)
    ->with('operadoras', $operadoras);
  }

  public function desconciliar(Request $request){
    $empresa = $request->input('empresa');
    $data_inicial = $request->input('data_inicial');
    $data_final = $request->input('data_final');
    $operadora = $request->input('operadora');

    try {

      $vendas_erp = VendasErpModel::where('COD_STATUS_CONCILIACAO', '!=', 2)
      ->where('COD_CLIENTE', $empresa)
      ->where('COD_OPERADORA', $operadora)
      ->whereBetween('DATA_VENDA', [$data_inicial, $data_final])
      ->get();

      $count_vendas_erp = count($vendas_erp);

      foreach ($vendas_erp as $venda_erp) {
        $codigo_venda_operadora = $venda_erp->COD_VENDAS_OPERADORAS;
        $identificador_pagamento = $venda_erp->IDENTIFICADOR_PAGAMENTO;

        $venda_operadora = VendasModel::find($codigo_venda_operadora);
        $venda_operadora->COD_STATUS_CONCILIACAO = 2;
        $venda_operadora->DIVERGENCIA = null;
        $venda_operadora->ID_VENDAS_ERP = null;
        $venda_operadora->ID_PAGAMENTO = null;
        $venda_operadora->COD_PAGAMENTO = null;
        $venda_operadora->COD_REGRA_CONCILIACAO = null;
        $venda_operadora->save();

        $venda_erp->DATA_CONCILIACAO = null;
        $venda_erp->COD_STATUS_CONCILIACAO = 2;
        $venda_erp->HORA_CONCILIACAO = null;
        $venda_erp->DIVERGENCIA = null;
        $venda_erp->COD_VENDAS_OPERADORAS = null;
        $venda_erp->COD_REGRA_CONCILIACAO = null;
        $venda_erp->DIFERENCA_LIQUIDO = null;
        $venda_erp->TAXA_DIFERENCA = null;
        $venda_erp->VALOR_LIQUIDO_OPERADORA = null;
        $venda_erp->TAXA_OPERADORA = null;
        $venda_erp->save();

        $pagamento_operadora = PagamentoOperadoraModel::where('ID_VENDA_ERP', $identificador_pagamento)
        ->where('COD_CLIENTE', session('codigologin'))
        ->first();
        $pagamento_operadora->COD_STATUS_FINANCEIRO = 1;
        $pagamento_operadora->COD_VENDA = null;
        $pagamento_operadora->ID_VENDA_ERP = null;
        $pagamento_operadora->save();
      }

      return response()->json([
        "success" => "Vendas desconciliadas com sucesso!",
        "total_desconciliacao" => $count_vendas_erp
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        "error" => $e
      ], 500);
    }
  }
}
