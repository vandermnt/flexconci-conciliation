<?php

namespace App\Http\Controllers;

use Request;
use DB;
use App\StatusConciliacaoModel;
use App\GruposClientesModel;
use App\AdquirentesModel;

class VendasErpController extends Controller{

  public function vendaserp(){
    $adquirentes = DB::table('cliente_operadora')
    ->join('adquirentes', 'cliente_operadora.COD_ADQUIRENTE', 'adquirentes.CODIGO')
    ->select('adquirentes.*')
    ->where('cliente_operadora.COD_CLIENTE', '=', session('codigologin'))
    ->distinct('COD_ADQUIRENTE')
    ->get();

    $meio_captura = DB::table('meio_captura')->get();
    $status_conciliacao = StatusConciliacaoModel::where('CODIGO', '!=', 4)->orderBy('STATUS_CONCILIACAO', 'ASC')->get();
    $grupos_clientes = GruposClientesModel::where('COD_CLIENTE', '=', session('codigologin'))->get();

    return view('vendas.vendaserp')->with('adquirentes', $adquirentes)
    ->with('grupos_clientes', $grupos_clientes)
    ->with('meio_captura', $meio_captura)
    ->with('status_conciliacao', $status_conciliacao);
  }

  public function buscarVendasErp(){
    $data_final = Request::input('data_final');
    $data_inicial = Request::input('data_inicial');
    $adquirente = Request::input('arrayAdquirentes');
    $conciliacao = Request::input('status_conciliacao');

    $vendas = DB::table('vendas_erp')
    ->join('modalidade', 'vendas_erp.COD_MODALIDADE', '=', 'modalidade.CODIGO')
    ->leftJoin('produto_web', 'vendas_erp.COD_PRODUTO', '=', 'produto_web.CODIGO')
    ->leftJoin('meio_captura', 'vendas_erp.COD_MEIO_CAPTURA', '=', 'meio_captura.CODIGO')
    ->select('vendas_erp.*', 'vendas_erp.CODIGO as COD', 'modalidade.*', 'produto_web.*', 'meio_captura.DESCRICAO as MEIOCAPTURA')
    ->where('vendas_erp.COD_CLIENTE', '=', session('codigologin'))
    // ->where(function($query) {
    //   if(Request::only('array') != null){
    //     $empresas = Request::only('array');
    //     foreach ($empresas['array'] as $cnpj) {
    //       $query->orWhere('CNPJ', '=', $cnpj);
    //     }
    //   }
    // })
    ->where(function($query) {
      if(Request::only('arrayStatusConciliacao') != null){
        $status_conciliacao = Request::only('arrayStatusConciliacao');
        foreach($status_conciliacao['arrayStatusConciliacao'] as $status_conciliacaoo) {
          $query->orWhere('COD_STATUS_CONCILIACAO', '=', $status_conciliacaoo);
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
      if(Request::only('arrayMeioCaptura') != null){
        $meiocaptura = Request::only('arrayMeioCaptura');
        foreach ($meiocaptura['arrayMeioCaptura'] as $mcaptura) {
          $query->orWhereNull('COD_MEIO_CAPTURA')->orWhere('COD_MEIO_CAPTURA', '=', $mcaptura);
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

    // $val_bruto = DB::table('vendas_erp')
    // ->join('modalidade', 'vendas_erp.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
    // ->selectRaw('sum(VALOR_BRUTO) as val_bruto')
    // ->where('vendas_erp.COD_CLIENTE', '=', session('codigologin'))
    // // ->where(function($query) {
    // //   if(Request::only('array') != null){
    // //     $empresas = Request::only('array');
    // //     foreach ($empresas['array'] as $cnpj) {
    // //       $query->orWhere('CNPJ', '=', $cnpj);
    // //     }
    // //   }
    // // })
    // ->where(function($query) {
    //   if(Request::only('arrayStatusConciliacao') != null){
    //     $status_conciliacao = Request::only('arrayStatusConciliacao');
    //     foreach($status_conciliacao['arrayStatusConciliacao'] as $status_conciliacaoo) {
    //       $query->orWhere('COD_STATUS_CONCILIACAO', '=', $status_conciliacaoo);
    //     }
    //   }
    // })
    // ->where(function($query) {
    //   if(Request::only('arrayAdquirentes') != null){
    //     $adquirentes = Request::only('arrayAdquirentes');
    //     foreach ($adquirentes['arrayAdquirentes'] as $adquirente) {
    //       $query->orWhere('COD_OPERADORA', '=', $adquirente);
    //     }
    //   }
    // })
    // ->where(function($query) {
    //   if(Request::only('data_inicial') != null){
    //     $data_inicial = Request::only('data_inicial');
    //     $data_final = Request::only('data_final');
    //     $query->whereBetween('DATA_VENDA', [$data_inicial['data_inicial'], $data_final['data_final']]);      }
    // })
    // ->first();
    //
    // $val_liquido = DB::table('vendas_erp')
    // ->join('modalidade', 'vendas_erp.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
    // ->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
    // ->where('vendas_erp.COD_CLIENTE', '=', session('codigologin'))
    // // ->where(function($query) {
    // //   if(Request::only('array') != null){
    // //     $empresas = Request::only('array');
    // //     foreach ($empresas['array'] as $cnpj) {
    // //       $query->orWhere('CNPJ', '=', $cnpj);
    // //     }
    // //   }
    // // })
    // ->where(function($query) {
    //   if(Request::only('arrayStatusConciliacao') != null){
    //     $status_conciliacao = Request::only('arrayStatusConciliacao');
    //     foreach($status_conciliacao['arrayStatusConciliacao'] as $status_conciliacaoo) {
    //       $query->orWhere('COD_STATUS_CONCILIACAO', '=', $status_conciliacaoo);
    //     }
    //   }
    // })
    // ->where(function($query) {
    //   if(Request::only('arrayAdquirentes') != null){
    //     $adquirentes = Request::only('arrayAdquirentes');
    //     foreach ($adquirentes['arrayAdquirentes'] as $adquirente) {
    //       $query->orWhere('COD_OPERADORA', '=', $adquirente);
    //     }
    //   }
    // })
    // ->where(function($query) {
    //   if(Request::only('data_inicial') != null){
    //     $data_inicial = Request::only('data_inicial');
    //     $data_final = Request::only('data_final');
    //     $query->whereBetween('DATA_VENDA', [$data_inicial['data_inicial'], $data_final['data_final']]);      }
    // })
    // ->first();
    //
    // $val_taxa_soma = DB::table('vendas_erp')
    // ->join('modalidade', 'vendas_erp.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
    // ->selectRaw('sum(VALOR_TAXA) as val_taxa')
    // ->where('vendas_erp.COD_CLIENTE', '=', session('codigologin'))
    // // ->where(function($query) {
    // //   if(Request::only('array') != null){
    // //     $empresas = Request::only('array');
    // //     foreach ($empresas['array'] as $cnpj) {
    // //       $query->orWhere('CNPJ', '=', $cnpj);
    // //     }
    // //   }
    // // })
    // ->where(function($query) {
    //   if(Request::only('arrayStatusConciliacao') != null){
    //     $status_conciliacao = Request::only('arrayStatusConciliacao');
    //     foreach($status_conciliacao['arrayStatusConciliacao'] as $status_conciliacaoo) {
    //       $query->orWhere('COD_STATUS_CONCILIACAO', '=', $status_conciliacaoo);
    //     }
    //   }
    // })
    // ->where(function($query) {
    //   if(Request::only('arrayAdquirentes') != null){
    //     $adquirentes = Request::only('arrayAdquirentes');
    //     foreach ($adquirentes['arrayAdquirentes'] as $adquirente) {
    //       $query->orWhere('COD_OPERADORA', '=', $adquirente);
    //     }
    //   }
    // })
    // ->where(function($query) {
    //   if(Request::only('data_inicial') != null){
    //     $data_inicial = Request::only('data_inicial');
    //     $data_final = Request::only('data_final');
    //     $query->whereBetween('DATA_VENDA', [$data_inicial['data_inicial'], $data_final['data_final']]);      }
    // })
    // ->first();
    //
    // $val_taxa_percent = DB::table('vendas_erp')
    // ->join('modalidade', 'vendas_erp.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
    // ->selectRaw('sum(OUTRAS_DESPESAS) as val_taxa_percent')
    // ->where('vendas_erp.COD_CLIENTE', '=', session('codigologin'))
    // // ->where(function($query) {
    // //   if(Request::only('array') != null){
    // //     $empresas = Request::only('array');
    // //     foreach ($empresas['array'] as $cnpj) {
    // //       $query->orWhere('CNPJ', '=', $cnpj);
    // //     }
    // //   }
    // // })
    // ->where(function($query) {
    //   if(Request::only('arrayStatusConciliacao') != null){
    //     $status_conciliacao = Request::only('arrayStatusConciliacao');
    //     foreach($status_conciliacao['arrayStatusConciliacao'] as $status_conciliacaoo) {
    //       $query->orWhere('COD_STATUS_CONCILIACAO', '=', $status_conciliacaoo);
    //     }
    //   }
    // })
    // ->where(function($query) {
    //   if(Request::only('arrayAdquirentes') != null){
    //     $adquirentes = Request::only('arrayAdquirentes');
    //     foreach ($adquirentes['arrayAdquirentes'] as $adquirente) {
    //       $query->orWhere('COD_OPERADORA', '=', $adquirente);
    //     }
    //   }
    // })
    // ->where(function($query) {
    //   if(Request::only('data_inicial') != null){
    //     $data_inicial = Request::only('data_inicial');
    //     $data_final = Request::only('data_final');
    //     $query->whereBetween('DATA_VENDA', [$data_inicial['data_inicial'], $data_final['data_final']]);      }
    // })
    // ->first();

    $qtde_registros = $vendas->count();

    // $val_taxas = $val_bruto->val_bruto - $val_liquido->val_liquido;
    session()->put('vendas_erp', $vendas);

    // $modalidades = ModalidadesModel::orderBy('DESCRICAO', 'ASC')->get();
    $adquirentes = AdquirentesModel::orderBy('ADQUIRENTE', 'ASC')->get();
    $status_conciliacao = StatusConciliacaoModel::where('CODIGO', '!=', 4)->orderBy('STATUS_CONCILIACAO', 'ASC')->get();
    $grupos_clientes = GruposClientesModel::where('COD_CLIENTE', '=', session('codigologin'))->get();
    // $val_liquido = number_format($val_liquido->val_liquido, 2,",",".");
    // $val_bruto = number_format($val_bruto->val_bruto, 2,",",".");
    // $val_taxa_soma = number_format($val_taxa_soma->val_taxa, 2,",",".");
    // $val_taxa_percent = number_format($val_taxa_percent->val_taxa_percent, 2, '.', '');
    // $val_taxas = number_format($val_taxas, 2,",",".");

    $vendas = json_encode([$vendas]);

    return $vendas;
  }

  public function downloadTable(){
    $vendas = session()->get('vendas_erp');
    set_time_limit(600);

    $pdf = \PDF::loadView('vendas.tabela_vendas', compact('vendas'));
    return $pdf->setPaper('A4', 'landscape')
    ->download('prev_pag.pdf');
  }
}
