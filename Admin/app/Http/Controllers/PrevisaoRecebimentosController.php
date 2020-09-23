<?php

namespace App\Http\Controllers;

use DB;
use Request;
use App\ModalidadesModel;
use App\BandeiraModel;
use App\AdquirentesModel;
class PrevisaoRecebimentosController extends Controller{

    public function previsaoRecebimentos(){
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


      return view('previsao-recebimentos.previsao-recebimentos')->with('adquirentes', $adquirentes)
      ->with('modalidades', $modalidades)
      ->with('bandeiras', $bandeiras);
    }

    public function loadPrevisaoRecebimentos(){
      $hoje = date('Y/m/d');

      $vendas = DB::table('vendas')
      ->join('modalidade', 'vendas.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
      ->join('bandeira', 'vendas.COD_BANDEIRA', '=', 'bandeira.CODIGO')
      ->join('lista_bancos', 'vendas.BANCO', '=', 'lista_bancos.CODIGO')
      ->leftJoin('produto_web', 'vendas.COD_PRODUTO', '=', 'produto_web.CODIGO')
      ->select('vendas.*', 'vendas.CODIGO as COD', 'modalidade.*', 'produto_web.*', 'lista_bancos.BANCO')
      ->where('vendas.COD_CLIENTE', '=', session('codigologin'))
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
            $query->orWhere('ADQUIRENTE', '=', $adquirente);
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
        if(Request::only('data_inicial') != null){
          $data_inicial = Request::only('data_inicial');
          $data_final = Request::only('data_final');
          $query->whereBetween('DATA_PREVISTA_PAGTO', [$data_inicial['data_inicial'], $data_final['data_final']]);
          // $query->where('DATA_PREVISTA_PAGTO', '>=', Request::only('data_inicial'));
        }
      })
      ->orderBy('DATA_PREVISTA_PAGTO')
      ->get();

      $val_bruto = DB::table('vendas')
      ->join('modalidade', 'vendas.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
      ->selectRaw('sum(VALOR_BRUTO) as val_bruto')
      ->where('vendas.COD_CLIENTE', '=', session('codigologin'))
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
            $query->orWhere('ADQUIRENTE', '=', $adquirente);
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
        if(Request::only('data_inicial') != null){
          $data_inicial = Request::only('data_inicial');
          $data_final = Request::only('data_final');
          $query->whereBetween('DATA_PREVISTA_PAGTO', [$data_inicial['data_inicial'], $data_final['data_final']]);    }
      })
      ->first();

      $val_liquido = DB::table('vendas')
      ->join('modalidade', 'vendas.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
      ->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
      ->where('vendas.COD_CLIENTE', '=', session('codigologin'))
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
            $query->orWhere('ADQUIRENTE', '=', $adquirente);
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
        if(Request::only('data_inicial') != null){
          $data_inicial = Request::only('data_inicial');
          $data_final = Request::only('data_final');
          $query->whereBetween('DATA_PREVISTA_PAGTO', [$data_inicial['data_inicial'], $data_final['data_final']]);    }
      })
      ->first();

      $val_taxa_soma = DB::table('vendas')
      ->join('modalidade', 'vendas.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
      ->selectRaw('sum(VALOR_TAXA) as val_taxa')
      ->where('vendas.COD_CLIENTE', '=', session('codigologin'))
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
            $query->orWhere('ADQUIRENTE', '=', $adquirente);
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
        if(Request::only('data_inicial') != null){
          $data_inicial = Request::only('data_inicial');
          $data_final = Request::only('data_final');
          $query->whereBetween('DATA_PREVISTA_PAGTO', [$data_inicial['data_inicial'], $data_final['data_final']]);    }
      })
      ->first();

      $val_taxa_percent = DB::table('vendas')
      ->join('modalidade', 'vendas.CODIGO_MODALIDADE', '=', 'modalidade.CODIGO')
      ->selectRaw('sum(OUTRAS_DESPESAS) as val_taxa_percent')
      ->where('vendas.COD_CLIENTE', '=', session('codigologin'))
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
            $query->orWhere('ADQUIRENTE', '=', $adquirente);
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
        if(Request::only('data_inicial') != null){
          $data_inicial = Request::only('data_inicial');
          $data_final = Request::only('data_final');
          $query->whereBetween('DATA_PREVISTA_PAGTO', [$data_inicial['data_inicial'], $data_final['data_final']]);
        }
      })
      ->first();

      $qtde_registros = $vendas->count();

      $val_taxas = $val_bruto->val_bruto - $val_liquido->val_liquido;
      session()->put('prev_pg_download', $vendas);

      $modalidades = ModalidadesModel::orderBy('DESCRICAO', 'ASC')->get();
      $adquirentes = AdquirentesModel::orderBy('ADQUIRENTE', 'ASC')->get();
      $bandeiras = BandeiraModel::orderBy('BANDEIRA', 'ASC')->get();

      $val_liquido = number_format($val_liquido->val_liquido, 2,",",".");
      $val_bruto = number_format($val_bruto->val_bruto, 2,",",".");
      $val_taxa_soma = number_format($val_taxa_soma->val_taxa, 2,",",".");
      $val_taxa_percent = number_format($val_taxa_percent->val_taxa_percent, 2, '.', '');
      $val_taxas = number_format($val_taxas, 2,",",".");

      $vendas = json_encode([$vendas, $val_liquido, $val_bruto, $qtde_registros, $val_taxas, $val_taxa_soma, $val_taxa_percent, $hoje]);

      return $vendas;
    }
}
