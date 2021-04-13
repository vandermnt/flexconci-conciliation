<?php

namespace App\Http\Controllers;
use App\TaxaModel;
use App\ModalidadesModel;
use App\BandeiraModel;
use App\AdquirentesModel;
use App\ClienteModel;
use App\ClienteOperadoraModel;
use DB;
use Illuminate\Http\Request;

class TaxaController extends Controller {

  public function index() {
    $taxas = DB::table('controle_taxa_cliente')
    ->leftJoin('bandeira', 'controle_taxa_cliente.COD_BANDEIRA', 'bandeira.codigo')
    ->leftJoin('modalidade', 'controle_taxa_cliente.COD_MODALIDADE', 'modalidade.CODIGO')
    ->leftJoin('adquirentes', 'controle_taxa_cliente.COD_OPERADORA', 'adquirentes.CODIGO')
    ->leftJoin('clientes', 'controle_taxa_cliente.COD_CLIENTE', 'clientes.CODIGO')
    ->select('bandeira.BANDEIRA',
    'modalidade.DESCRICAO',
    'adquirentes.ADQUIRENTE',
    'clientes.NOME_FANTASIA',
    'controle_taxa_cliente.CODIGO',
    // 'controle_taxa_cliente.DATA_VIGENCIA',
    'controle_taxa_cliente.TAXA'
    )
    ->orderBy('controle_taxa_cliente.TAXA', 'asc')
    ->get();

    $formas_pagamento = ModalidadesModel::orderBy('DESCRICAO', 'asc')->get();
    $bandeiras = BandeiraModel::orderBy('BANDEIRA', 'asc')->get();
    $clientes = ClienteModel::orderBy('NOME_FANTASIA', 'asc')->get();
    $operadoras = AdquirentesModel::orderBy('ADQUIRENTE', 'asc')->get();
    $clientes_operadora = DB::table('cliente_operadora')
    ->leftJoin('clientes', 'cliente_operadora.COD_CLIENTE', 'clientes.CODIGO')
    ->leftJoin('adquirentes', 'cliente_operadora.COD_ADQUIRENTE', 'adquirentes.CODIGO')
    ->select('cliente_operadora.CODIGO',
    'clientes.NOME_FANTASIA',
    'cliente_operadora.CODIGO_ESTABELECIMENTO',
    'cliente_operadora.COD_CLIENTE',
    'clientes.CPF_CNPJ',
    'adquirentes.ADQUIRENTE'
    )
    ->get();

    $taxas_count = $taxas->count();

    return view('cadastro.taxa')
    ->with('taxas', $taxas)
    ->with('clientes_operadora', $clientes_operadora)
    ->with('count_taxas', $taxas_count)
    ->with('formas_pagamento', $formas_pagamento)
    ->with('clientes', $clientes)
    ->with('bandeiras', $bandeiras)
    ->with('operadoras', $operadoras);
  }

  public function cadastrarTaxa(Request $request) {
    $empresa = $request->input("empresa");
    $operadora_estabelecimento = $request->input('operadora_estabelecimento');
    $new_taxa = $request->input('taxa');
    $bandeira = $request->input('bandeira');
    $tarifa_minima = $request->input('tarifa_minima');
    $taxa_ant_aut = $request->input('taxa_ant_aut');
    $total_parcelas = $request->input('total_parcelas');
    $data_vigencia_inicial = $request->input('data_vigencia_inicial');
    $data_vigencia_final = $request->input('data_vigencia_final');
    $forma_pagamento = $request->input('forma_pagamento');

    try {
      $cod_operadora = ClienteOperadoraModel::find($operadora_estabelecimento);

      $taxa = new TaxaModel();
      $taxa->COD_CLIENTE = $empresa;
      $taxa->TAXA = str_replace(",",".", $new_taxa);
      $taxa->COD_BANDEIRA = $bandeira;
      $taxa->COD_OPERADORA = $cod_operadora->COD_ADQUIRENTE;
      $taxa->TAXA_ANT_AUT = str_replace(",",".", $taxa_ant_aut);
      $taxa->TARIFA_MINIMA = str_replace(",",".", $tarifa_minima);
      $taxa->TOTAL_PARCELAS = $total_parcelas;
      $taxa->COD_MODALIDADE = $forma_pagamento;
      $taxa->DATA_VIGENCIA_INICIAL = $data_vigencia_inicial;
      $taxa->DATA_VIGENCIA_FINAL = $data_vigencia_final;

      $taxa->save();

      return response()->json([
        'taxa-criada' => $taxa
      ]);
    } catch (Exception $e) {
      return response()->json([
        "error" => $e->getMessage()
      ]);
    }
  }

  public function allTaxas(){
    try {
      $taxas = TaxaModel::leftJoin('bandeira', 'controle_taxa_cliente.COD_BANDEIRA', 'bandeira.codigo')
      ->leftJoin('clientes', 'controle_taxa_cliente.COD_CLIENTE', 'clientes.CODIGO')
      ->select('bandeira.BANDEIRA',
      'clientes.NOME_FANTASIA',
      'controle_taxa_cliente.CODIGO',
      // 'controle_taxa_cliente.DATA_VIGENCIA',
      'controle_taxa_cliente.TAXA'
      )
      ->orderBy('controle_taxa_cliente.TAXA', 'asc')
      ->get();

      return response()->json([
        'taxas' => $taxas
      ]);
    } catch (Exception $e) {
      return response()->json([
        "error" => $e->getMessage()
      ]);
    }
  }

  public function updateTaxa(Request $request, $codigo){
    $taxa_nome = $request->input('taxa');

    $taxa = TaxaModel::where('CODIGO', $codigo)->first();

    if(isset($taxa)){
      $taxa->TAXA = str_replace(",",".", $taxa_nome);
      $taxa->save();

      return response()->json(200);
    }
    return response()->json(500);
  }

  public function excluirTaxa($codigo_taxa) {
    try {
      $taxa = TaxaModel::where("CODIGO", "=", $codigo_taxa)->first();
      $taxa->delete();

      return response()->json(200);

    } catch (Exception $e) {
      return response()->json([
        "error" => $e->getMessage()
      ]);
    }
  }

  public function searchTaxas($empresa, $operadora) {
    $cod_operadora = ClienteOperadoraModel::find($operadora);

    try {
      $taxas = TaxaModel::where('COD_CLIENTE', $empresa)
      ->where('COD_OPERADORA', $cod_operadora->COD_ADQUIRENTE)
      ->leftJoin('bandeira', 'controle_taxa_cliente.COD_BANDEIRA', 'bandeira.codigo')
      ->leftJoin('clientes', 'controle_taxa_cliente.COD_CLIENTE', 'clientes.CODIGO')
      ->leftJoin('modalidade', 'controle_taxa_cliente.COD_MODALIDADE', 'modalidade.CODIGO')
      ->leftJoin('adquirentes', 'controle_taxa_cliente.COD_OPERADORA', 'adquirentes.CODIGO')
      ->select('bandeira.BANDEIRA',
      'clientes.NOME_FANTASIA',
      'modalidade.DESCRICAO',
      'adquirentes.ADQUIRENTE',
      'controle_taxa_cliente.CODIGO',
      'controle_taxa_cliente.DATA_VIGENCIA_INICIAL',
      'controle_taxa_cliente.DATA_VIGENCIA_FINAL',
      'controle_taxa_cliente.TARIFA_MINIMA',
      'controle_taxa_cliente.TOTAL_PARCELAS',
      'controle_taxa_cliente.TAXA',
      'controle_taxa_cliente.TAXA_ANT_AUT'

      )
      ->get();

      return response()->json([
        "taxas" =>$taxas
      ]);
    } catch (\Exception $e) {
      return response()->json([
        "error" => $e->getMessage()
      ]);
    }
  }
}
