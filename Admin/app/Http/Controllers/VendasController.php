<?php

namespace App\Http\Controllers;

use Request;
use DB;
use PDF;
use App\ModalidadesModel;
use App\AdquirentesModel;
use App\VendasModel;
use App\BandeiraModel;
use App\ClienteOperadoraModel;
use App\StatusConciliacaoModel;
use App\GruposClientesModel;
use App\Http\Controllers\DOMPDF;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Pagination\BootstrapThreePresenter;

class VendasController extends Controller{

  public function vendas(){
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

    $grupos_clientes = GruposClientesModel::where('COD_CLIENTE', '=', session('codigologin'))->get();
    $status_conciliacao = StatusConciliacaoModel::where('CODIGO', '!=', 4)->orderBy('STATUS_CONCILIACAO', 'ASC')->get();

    return view('vendas.vendas')->with('adquirentes', $adquirentes)
    ->with('modalidades', $modalidades)
    ->with('bandeiras', $bandeiras)
    ->with('grupos_clientes', $grupos_clientes)
    ->with('status_conciliacao', $status_conciliacao);
  }

  public function buscarVendasFiltro(){
    $data_final = Request::input('data_final');
    $data_inicial = Request::input('data_inicial');
    $empresa = Request::input('array');
    $adquirente = Request::input('arrayAdquirentes');
    $bandeira = Request::input('arrayBandeira');
    $modalidade = Request::input('arrayModalidade');
    $conciliacao = Request::input('status_conciliacao');
    $status_financeiro = Request::input('status_financeiro');

    $vendas = DB::table('vendas')
    ->join('modalidade', 'vendas.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
    ->leftJoin('bandeira', 'vendas.COD_BANDEIRA', '=', 'bandeira.CODIGO')
    ->leftJoin('lista_bancos', 'vendas.BANCO', '=', 'lista_bancos.CODIGO')
    ->leftJoin('produto_web', 'vendas.COD_PRODUTO', '=', 'produto_web.CODIGO')
    ->select('vendas.*', 'vendas.CODIGO as COD', 'modalidade.*', 'produto_web.*', 'lista_bancos.BANCO')
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
      if(Request::only('arrayStatusFinanceiro') != null){
        $status_financeiros = Request::only('arrayStatusFinanceiro');
        foreach ($status_financeiros['arrayStatusFinanceiro'] as $status_financeiro) {
          $query->orWhereNull('COD_STATUS_FINANCEIRO')->orWhere('COD_STATUS_FINANCEIRO', '=', $status_financeiro);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('data_inicial') != null){
        $data_inicial = Request::only('data_inicial');
        $data_final = Request::only('data_final');
        $query->whereBetween('DATA_VENDA', [$data_inicial['data_inicial'], $data_final['data_final']]);

        // $query->where('DATA_VENDA', '>=', '2020-08-01')->where('DATA_VENDA', '<=', '2020-09-05');
      }
    })
    ->orderBy('DATA_VENDA')
    ->get();

    $val_bruto = DB::table('vendas')
    ->join('modalidade', 'vendas.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
    ->selectRaw('sum(VALOR_BRUTO) as val_bruto')
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
      if(Request::only('arrayStatusFinanceiro') != null){
        $status_financeiros = Request::only('arrayStatusFinanceiro');
        foreach ($status_financeiros['arrayStatusFinanceiro'] as $status_financeiro) {
          $query->orWhereNull('COD_STATUS_FINANCEIRO')->orWhere('COD_STATUS_FINANCEIRO', '=', $status_financeiro);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('data_inicial') != null){
        $data_inicial = Request::only('data_inicial');
        $data_final = Request::only('data_final');
        $query->whereBetween('DATA_VENDA', [$data_inicial['data_inicial'], $data_final['data_final']]);      }
    })
    ->first();

    $val_liquido = DB::table('vendas')
    ->join('modalidade', 'vendas.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
    ->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
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
      if(Request::only('arrayStatusFinanceiro') != null){
        $status_financeiros = Request::only('arrayStatusFinanceiro');
        foreach ($status_financeiros['arrayStatusFinanceiro'] as $status_financeiro) {
          $query->orWhereNull('COD_STATUS_FINANCEIRO')->orWhere('COD_STATUS_FINANCEIRO', '=', $status_financeiro);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('data_inicial') != null){
        $data_inicial = Request::only('data_inicial');
        $data_final = Request::only('data_final');
        $query->whereBetween('DATA_VENDA', [$data_inicial['data_inicial'], $data_final['data_final']]);      }
    })
    ->first();

    $val_taxa_soma = DB::table('vendas')
    ->join('modalidade', 'vendas.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
    ->selectRaw('sum(VALOR_TAXA) as val_taxa')
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
      if(Request::only('arrayStatusFinanceiro') != null){
        $status_financeiros = Request::only('arrayStatusFinanceiro');
        foreach ($status_financeiros['arrayStatusFinanceiro'] as $status_financeiro) {
          $query->orWhereNull('COD_STATUS_FINANCEIRO')->orWhere('COD_STATUS_FINANCEIRO', '=', $status_financeiro);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('data_inicial') != null){
        $data_inicial = Request::only('data_inicial');
        $data_final = Request::only('data_final');
        $query->whereBetween('DATA_VENDA', [$data_inicial['data_inicial'], $data_final['data_final']]);      }
    })
    ->first();

    $val_taxa_percent = DB::table('vendas')
    ->join('modalidade', 'vendas.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
    ->selectRaw('sum(OUTRAS_DESPESAS) as val_taxa_percent')
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
      if(Request::only('arrayStatusFinanceiro') != null){
        $status_financeiro = Request::only('arrayStatusFinanceiro');
        foreach($status_financeiro['arrayStatusFinanceiro'] as $status_financeiroo) {
          $query->orWhere('COD_STATUS_FINANCEIRO', '=', $status_financeiroo);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('data_inicial') != null){
        $data_inicial = Request::only('data_inicial');
        $data_final = Request::only('data_final');
        $query->whereBetween('DATA_VENDA', [$data_inicial['data_inicial'], $data_final['data_final']]);      }
    })
    ->first();

    $qtde_registros = $vendas->count();

    $val_taxas = $val_bruto->val_bruto - $val_liquido->val_liquido;
    session()->put('prev_pg_download', $vendas);

    $modalidades = ModalidadesModel::orderBy('DESCRICAO', 'ASC')->get();
    $adquirentes = AdquirentesModel::orderBy('ADQUIRENTE', 'ASC')->get();
    $bandeiras = BandeiraModel::orderBy('BANDEIRA', 'ASC')->get();
    $status_conciliacao = StatusConciliacaoModel::where('CODIGO', '!=', 4)->orderBy('STATUS_CONCILIACAO', 'ASC')->get();
    $grupos_clientes = GruposClientesModel::where('COD_CLIENTE', '=', session('codigologin'))->get();
    $s = Request::only('arrayStatusFinanceiro');
    $ss =  Request::only('data_inicial');
    $val_liquido = number_format($val_liquido->val_liquido, 2,",",".");
    $val_bruto = number_format($val_bruto->val_bruto, 2,",",".");
    $val_taxa_soma = number_format($val_taxa_soma->val_taxa, 2,",",".");
    $val_taxa_percent = number_format($val_taxa_percent->val_taxa_percent, 2, '.', '');
    $val_taxas = number_format($val_taxas, 2,",",".");

    $vendas = json_encode([$vendas, $val_liquido, $val_bruto, $qtde_registros, $val_taxas, $val_taxa_soma, $val_taxa_percent, $ss]);

    return $vendas;

    $flag_scroll = true;

    // return view('vendas')
    // ->with('result', $vendas)
    // ->with('todas_vendas', $todas_vendas)
    // ->with('adquirentes', $adquirentes)
    // ->with('modalidades', $modalidades)
    // ->with('bandeiras', $bandeiras)
    // ->with('status_conciliacao', $status_conciliacao)
    // ->with('status_financeiro', $status_financeiro)
    //
    // ->with('grupos_clientes', $grupos_clientes)
    // ->with('data_inicial', $data_inicial)
    // ->with('data_final', $data_final)
    // ->with('val_liquido', $val_liquido)
    // ->with('val_bruto', $val_bruto)
    // ->with('qtde_registros', $qtde_registros)
    // ->with('flag_scroll', $flag_scroll)
    //
    // ->with('filtro_data_inicial', $data_inicial)
    // ->with('filtro_data_final', $data_final)
    // ->with('filtro_conciliacao', $conciliacao)
    // ->with('empresa', $empresa)
    // ->with('filtro_adquirente', $adquirente)
    // ->with('filtro_modalidade', $modalidade)
    // ->with('filtro_bandeira', $bandeira);
  }

  public function downloadTable(){
    $vendas = session()->get('prev_pg_download');
    set_time_limit(600);
    // dd($vendas);
    $pdf = \PDF::loadView('vendas.tabela_vendas', compact('vendas'));
    return $pdf->setPaper('A4', 'landscape')
    ->download('prev_pag.pdf');
  }

  public function impressaoCupom($codigo){

    $venda = DB::table('vendas')
    ->join('modalidade', 'vendas.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
    ->join('bandeira', 'vendas.COD_BANDEIRA', '=', 'bandeira.CODIGO')
    ->join('lista_bancos', 'vendas.BANCO', '=', 'lista_bancos.CODIGO')
    ->leftJoin('produto_web', 'vendas.COD_PRODUTO', '=', 'produto_web.CODIGO')
    ->select('vendas.*', 'vendas.CODIGO as COD', 'modalidade.*', 'produto_web.*', 'lista_bancos.BANCO')
    ->where('vendas.COD_CLIENTE', '=', session('codigologin'))
    ->where('vendas.CODIGO', '=', $codigo)
    ->first();

    $customPaper = array(0, 0, 240.53, 210.28);
    return \PDF::loadView('vendas.vendas-impressao', compact('venda'))
               // Se quiser que fique no formato a4 retrato: ->setPaper('a4', 'landscape')
               ->setPaper($customPaper, 'landscape')
               ->stream('cupom.pdf');


    // return view('vendas.vendas-impressao')->with('venda', $venda);
  }
}
