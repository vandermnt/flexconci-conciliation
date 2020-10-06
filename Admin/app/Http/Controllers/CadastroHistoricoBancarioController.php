<?php

namespace App\Http\Controllers;

use Request;
use DB;
use App\AdquirentesModel;
use App\BancoModel;
use App\HistoricoBancarioModel;

class CadastroHistoricoBancarioController extends Controller{

    public function cadastroHistoricoBancario(){

      $adquirentes = AdquirentesModel::whereNotNull('CODIGO')
      ->orderBy('ADQUIRENTE')
      ->get();

      $bancos = BancoModel::whereNotNull('CODIGO')
      ->orderBy('CODIGO')
      ->get();

      $historicos = DB::table('historico_banco')
      ->leftJoin('adquirentes', 'historico_banco.COD_ADQUIRENTE', 'adquirentes.CODIGO')
      ->leftJoin('lista_bancos', 'historico_banco.COD_BANCO', 'lista_bancos.CODIGO')
      ->select("historico_banco.*", "lista_bancos.BANCO", "adquirentes.ADQUIRENTE")
      ->get();

      return view('cadastro.historico-bancario')->with('historicos', $historicos)->with('adquirentes', $adquirentes)->with('bancos', $bancos);

    }

    public function newCadastroHistoricoBancario(){

      $adquirente = Request::only('adquirente');
      $historico_banco = Request::only('historico_banco');
      $forma_pesquisa = Request::only('forma_pesquisa');

      $historico_bancario = new HistoricoBancarioModel();

      date_default_timezone_set('America/Sao_Paulo');
      $date = date('Y-m-d');

      $historico_bancario->DATA_CADASTRO = $date;
      $historico_bancario->COD_FUNCIONARIO_RESP = 0;
      $historico_bancario->PARTE1_COMPLETO2 = $forma_pesquisa['forma_pesquisa'];
      $historico_bancario->COD_ADQUIRENTE = $adquirente['adquirente'];
      $historico_bancario->HISTORICO_BANCO = $historico_banco['historico_banco'];
      // $historico_bancario->COD_BANCO = $banco['banco'];
      //
      $historico_bancario->save();
      // dd($historico_bancario);

      return json_encode($historico_banco);

    }

    public function loadHistoricoBancario(){

      $historicos = DB::table('historico_banco')
      ->leftJoin('adquirentes', 'historico_banco.COD_ADQUIRENTE', 'adquirentes.CODIGO')
      ->leftJoin('lista_bancos', 'historico_banco.COD_BANCO', 'lista_bancos.CODIGO')
      ->select('historico_banco.*', 'adquirentes.ADQUIRENTE', 'lista_bancos.BANCO')
      ->get();

      return $historicos;
    }

    public function deleteHistoricoBancario($codigo_historico){

      $historico = HistoricoBancarioModel::where("CODIGO", "=", $codigo_historico)->first();
      $historico->delete();

      return response()->json(200);
    }
}
