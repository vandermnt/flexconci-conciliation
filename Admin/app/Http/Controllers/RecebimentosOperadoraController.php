<?php

namespace App\Http\Controllers;

use DB;
use Request;
use App\ModalidadesModel;
use App\GruposClientesModel;
use App\BandeiraModel;
use App\AdquirentesModel;

class RecebimentosOperadoraController extends Controller{

  public function recebimentosOperadora(){
    $modalidades = ModalidadesModel::orderBy('DESCRICAO', 'ASC')->get();
    $adquirentes = DB::table('cliente_operadora')
    ->join('adquirentes', 'cliente_operadora.COD_ADQUIRENTE', 'adquirentes.CODIGO')
    ->select('adquirentes.*')
    ->where('cliente_operadora.COD_CLIENTE', '=', session('codigologin'))
    ->distinct('COD_ADQUIRENTE')
    ->get();

    $bancos = DB::table('domicilio_cliente')
    ->where('COD_CLIENTE', '=', session('codigologin'))
    ->get();

    $bandeiras = DB::table('clientes_bandeiras')
    ->join('bandeira', 'clientes_bandeiras.COD_BANDEIRA', 'bandeira.CODIGO')
    ->select('bandeira.*')
    ->where('clientes_bandeiras.COD_CLIENTE', '=', session('codigologin'))
    ->get();

    $grupos_clientes = GruposClientesModel::where('COD_CLIENTE', '=', session('codigologin'))->get();

    return view('recebimentos.recebimentos-operadora')->with('adquirentes', $adquirentes)
    ->with('modalidades', $modalidades)
    ->with('bandeiras', $bandeiras)
    ->with('bancos', $bancos)
    ->with('grupos_clientes', $grupos_clientes);
  }

  public function consultarRecebimentosOperadoras(){
    $vendas = DB::table('pagamentos_operadoras')
    ->leftJoin('bandeira', 'pagamentos_operadoras.COD_BANDEIRA', '=', 'bandeira.CODIGO')
    ->leftJoin('grupos_clientes', 'pagamentos_operadoras.COD_GRUPO_CLIENTE', '=', 'grupos_clientes.CODIGO')
    ->leftJoin('adquirentes', 'pagamentos_operadoras.COD_ADQUIRENTE', '=', 'adquirentes.CODIGO')
    ->leftJoin('lista_bancos', 'pagamentos_operadoras.COD_BANCO', '=', 'lista_bancos.CODIGO', 'banco.BANCO')
    ->leftJoin('meio_captura', 'pagamentos_operadoras.COD_MEIO_CAPTURA', '=', 'meio_captura.CODIGO')
    ->select('pagamentos_operadoras.*', 'pagamentos_operadoras.CODIGO as COD', 'bandeira.*', 'adquirentes.*', 'grupos_clientes.NOME_EMPRESA', 'lista_bancos.BANCO', 'adquirentes.IMAGEM as IMAGEMAD', 'bandeira.IMAGEM as IMAGEMBAD')
    ->where('pagamentos_operadoras.COD_CLIENTE', '=', session('codigologin'))
    ->where(function($query) {
      if(Request::only('array') != null){
        $empresas = Request::only('array');
        foreach ($empresas['array'] as $id_loja) {
          $query->orWhere('ID_LOJA', '=', $id_loja);
        }
      }
    })
    // ->where(function($query) {
    //   if(Request::only('arrayModalidade') != null){
    //     foreach(Request::only('arrayModalidade') as $modalidade) {
    //       $query->orWhere('CODIGO_MODALIDADE', '=', $modalidade);
    //     }
    //   }
    // })
    ->where(function($query) {
      if(Request::only('arrayAdquirentes') != null){
        $adquirentes = Request::only('arrayAdquirentes');
        foreach ($adquirentes['arrayAdquirentes'] as $adquirente) {
          $query->orWhere('COD_ADQUIRENTE', '=', $adquirente);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('arrayBandeira') != null){
        $bandeiras = Request::only('arrayAdquirentes');
        foreach($bandeiras['arrayBandeira'] as $bandeira) {
          $query->orWhere('COD_BANDEIRA', '=', $bandeira);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('data_inicial') != null){
        $data_inicial = Request::only('data_inicial');
        $data_final = Request::only('data_final');
        $query->whereBetween('DATA_PAGAMENTO', [$data_inicial['data_inicial'], $data_final['data_final']]);
      }
    })
    ->orderBy('DATA_PAGAMENTO')
    ->get();

    $val_bruto = DB::table('pagamentos_operadoras')
    ->selectRaw('sum(VALOR_BRUTO) as val_bruto')
    ->where('pagamentos_operadoras.COD_CLIENTE', '=', session('codigologin'))
    ->where(function($query) {
      if(Request::only('array') != null){
        $empresas = Request::only('array');
        foreach ($empresas['array'] as $id_loja) {
          $query->orWhere('ID_LOJA', '=', $id_loja);
        }
      }
    })
    // ->where(function($query) {
    //   if(Request::only('arrayModalidade') != null){
    //     foreach(Request::only('arrayModalidade') as $modalidade) {
    //       $query->orWhere('CODIGO_MODALIDADE', '=', $modalidade);
    //     }
    //   }
    // })
    ->where(function($query) {
      if(Request::only('arrayAdquirentes') != null){
        $adquirentes = Request::only('arrayAdquirentes');
        foreach ($adquirentes['arrayAdquirentes'] as $adquirente) {
          $query->orWhere('COD_ADQUIRENTE', '=', $adquirente);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('arrayBandeira') != null){
        $bandeiras = Request::only('arrayAdquirentes');
        foreach($bandeiras['arrayBandeira'] as $bandeira) {
          $query->orWhere('COD_BANDEIRA', '=', $bandeira);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('data_inicial') != null){
        $data_inicial = Request::only('data_inicial');
        $data_final = Request::only('data_final');
        $query->whereBetween('DATA_PROCESSAMENTO', [$data_inicial['data_inicial'], $data_final['data_final']]);
      }
    })
    ->first();

    $val_liquido = DB::table('pagamentos_operadoras')
    ->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
    ->where('pagamentos_operadoras.COD_CLIENTE', '=', session('codigologin'))
    ->where(function($query) {
      if(Request::only('array') != null){
        $empresas = Request::only('array');
        foreach ($empresas['array'] as $id_loja) {
          $query->orWhere('ID_LOJA', '=', $id_loja);
        }
      }
    })
    // ->where(function($query) {
    //   if(Request::only('arrayModalidade') != null){
    //     foreach(Request::only('arrayModalidade') as $modalidade) {
    //       $query->orWhere('CODIGO_MODALIDADE', '=', $modalidade);
    //     }
    //   }
    // })
    ->where(function($query) {
      if(Request::only('arrayAdquirentes') != null){
        $adquirentes = Request::only('arrayAdquirentes');
        foreach ($adquirentes['arrayAdquirentes'] as $adquirente) {
          $query->orWhere('COD_ADQUIRENTE', '=', $adquirente);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('arrayBandeira') != null){
        $bandeiras = Request::only('arrayAdquirentes');
        foreach($bandeiras['arrayBandeira'] as $bandeira) {
          $query->orWhere('COD_BANDEIRA', '=', $bandeira);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('data_inicial') != null){
        $data_inicial = Request::only('data_inicial');
        $data_final = Request::only('data_final');
        $query->whereBetween('DATA_PROCESSAMENTO', [$data_inicial['data_inicial'], $data_final['data_final']]);
      }
    })
    ->first();

    $val_taxa_soma = DB::table('pagamentos_operadoras')
    ->selectRaw('sum(VALOR_TAXA) as val_taxa')
    ->where('pagamentos_operadoras.COD_CLIENTE', '=', session('codigologin'))
    ->where(function($query) {
      if(Request::only('array') != null){
        $empresas = Request::only('array');
        foreach ($empresas['array'] as $id_loja) {
          $query->orWhere('ID_LOJA', '=', $id_loja);
        }
      }
    })
    // ->where(function($query) {
    //   if(Request::only('arrayModalidade') != null){
    //     foreach(Request::only('arrayModalidade') as $modalidade) {
    //       $query->orWhere('CODIGO_MODALIDADE', '=', $modalidade);
    //     }
    //   }
    // })
    ->where(function($query) {
      if(Request::only('arrayAdquirentes') != null){
        $adquirentes = Request::only('arrayAdquirentes');
        foreach ($adquirentes['arrayAdquirentes'] as $adquirente) {
          $query->orWhere('COD_ADQUIRENTE', '=', $adquirente);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('arrayBandeira') != null){
        $bandeiras = Request::only('arrayAdquirentes');
        foreach($bandeiras['arrayBandeira'] as $bandeira) {
          $query->orWhere('COD_BANDEIRA', '=', $bandeira);
        }
      }
    })
    ->where(function($query) {
      if(Request::only('data_inicial') != null){
        $data_inicial = Request::only('data_inicial');
        $data_final = Request::only('data_final');
        $query->whereBetween('DATA_PROCESSAMENTO', [$data_inicial['data_inicial'], $data_final['data_final']]);
      }
    })
    ->first();

    $qtde_registros = $vendas->count();

    $val_taxas = $val_bruto->val_bruto - $val_liquido->val_liquido;
    session()->put('prev_pg_download', $vendas);

    // $modalidades = ModalidadesModel::orderBy('DESCRICAO', 'ASC')->get();
    // $adquirentes = AdquirentesModel::orderBy('ADQUIRENTE', 'ASC')->get();
    // $bandeiras = BandeiraModel::orderBy('BANDEIRA', 'ASC')->get();
    // $grupos_clientes = GruposClientesModel::where('COD_CLIENTE', '=', session('codigologin'))->get();

    $val_liquido = number_format($val_liquido->val_liquido, 2,",",".");
    $val_bruto = number_format($val_bruto->val_bruto, 2,",",".");
    $val_taxa_soma = number_format($val_taxa_soma->val_taxa, 2,",",".");
    $val_taxas = number_format($val_taxas, 2,",",".");

    $vendas = json_encode([$vendas, $val_liquido, $val_bruto, $qtde_registros, $val_taxas, $val_taxa_soma, Request::only('array')]);
    return $vendas;
  }

  public function downloadTable(){
    $vendas = session()->get('prev_pg_download');
    // dd($vendas);
    set_time_limit(600);
    // dd($vendas);
    $pdf = \PDF::loadView('recebimentos.tabela_recebimentos_operadora', compact('vendas'));
    return $pdf->setPaper('A4', 'landscape')
    ->download('prev_pag.pdf');
  }
}
