<?php

namespace App\Http\Controllers;
use App\TaxaModel;
use App\ModalidadesModel;
use App\BandeiraModel;
use App\AdquirentesModel;
use App\ClienteModel;
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
    'controle_taxa_cliente.DATA_VIGENCIA',
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
    $taxa = new TaxaModel();
    $valor_taxa = $request->input('taxa');
    $bandeira = $request->input('bandeira');
    $operadora = $request->input('operadora');
    $forma_pagamento = $request->input('forma_pagamento');
    $data_vigencia = $request->input('data_vigencia');
    $cliente = $request ->input('cliente');

    try {
      $valor_formatado = str_replace(",",".", $valor_taxa);

      $taxa->TAXA = $valor_formatado;
      $taxa->COD_BANDEIRA = $bandeira;
      $taxa->COD_MODALIDADE = $forma_pagamento;
      $taxa->COD_CLIENTE = $cliente;
      $taxa->COD_OPERADORA = $operadora;
      $taxa->DATA_VIGENCIA = $data_vigencia;

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
      'controle_taxa_cliente.DATA_VIGENCIA',
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
      $taxa->TAXA = $taxa_nome;
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
    // $empresa = $request->input('empresa');
    // $operadora_estabelecimento = $request->input('operadora_estabelecimento');
    try {
      $taxas = TaxaModel::where('COD_CLIENTE', $empresa)
      ->where('COD_OPERADORA', $operadora)
      ->leftJoin('bandeira', 'controle_taxa_cliente.COD_BANDEIRA', 'bandeira.codigo')
      ->leftJoin('clientes', 'controle_taxa_cliente.COD_CLIENTE', 'clientes.CODIGO')
      ->select('bandeira.BANDEIRA',
      'clientes.NOME_FANTASIA',
      'controle_taxa_cliente.CODIGO',
      'controle_taxa_cliente.DATA_VIGENCIA',
      'controle_taxa_cliente.TAXA'
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
