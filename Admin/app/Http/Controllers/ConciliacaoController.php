<?php

namespace App\Http\Controllers;

use Request;
use App\ConciliacaoBancariaModel;
use App\ClienteModel;
use Auth;

class ConciliacaoController extends Controller{

  public function conciliacaoBancaria(){
    $dados_cliente = ClienteModel::where('CODIGO', '=', session('codigologin'))->first();

    $arquivos = Request::file('extratos');

    $qtde_arquivos = count($arquivos);

    for($i=0; $i<$qtde_arquivos; $i++){

      $extrato = new ConciliacaoBancariaModel();

      $file = $arquivos[$i];

      $nome_arquivo = $file->getClientOriginalName();
      // $extensao_arquivo = $file->getClientOriginalExtension();

      $file->storeAs('extratos-bancarios', $nome_arquivo, 'archive');

      $contents = fopen(public_path("extratos-bancarios/".$nome_arquivo), "r");

      $conteudo = "";
      while(!feof($contents)) {
        $conteudo .= fgets($contents);
      }

      //tira os /n
      $conteudo_arq = preg_replace( "/\r|\n/", "", $conteudo);

      fclose($contents);

      date_default_timezone_set('America/Sao_Paulo');
      $date = date('Y-m-d');
      $date_historico = date('d-m-Y');

      $hora_envio = date('H:i:s');

      //salva os dados
      $extrato->COD_CLIENTE = session('codigologin');
      $extrato->CNPJ = $dados_cliente->CPF_CNPJ;
      $extrato->DATA = $date;
      $extrato->DATA_ENVIO = $date;
      $extrato->HORA_ENVIO = $hora_envio;
      $extrato->HISTORICO = Auth::user()->USUARIO . " fez a conciliação em " . $date_historico . " às " . $hora_envio;


      $extrato->COD_STATUS_BANCARIO = 1;
      $extrato->ARQUIVO = $conteudo_arq;
      $extrato->save();
    }

    return response()->json(200);
  }

  public function atualizarConciliacoesProcessadas(){

    $conciliacoes = ConciliacaoBancariaModel::where('COD_CLIENTE', '=', session('codigologin'))
    ->where('COD_STATUS_BANCARIO', '=', 1)
    ->get();

    return $conciliacoes;

  }
}
