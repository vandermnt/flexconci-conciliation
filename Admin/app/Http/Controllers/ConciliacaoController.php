<?php

namespace App\Http\Controllers;

use Request;
use App\ConciliacaoBancariaModel;
use App\ClienteModel;
use App\DadosArquivoConciliacaoBancariaModel;
use Auth;

class ConciliacaoController extends Controller{

  public function conciliacaoBancaria(){
    $dados_cliente = ClienteModel::where('CODIGO', '=', session('codigologin'))->first();
    $lines = array();

    $dados_formatados = [];
    $array_dados = [];

    $arquivos = Request::file('extratos');

    for($i=0; $i<count($arquivos); $i++){
      $extrato = new ConciliacaoBancariaModel();
      $dados_arquivo_extratos = new DadosArquivoConciliacaoBancariaModel();

      $file = $arquivos[$i];

      // lê todo o arquivo em formato de string
      $file_read = file_get_contents($file);
      //tira os quebra linhas
      $array_dados = explode("\n", $file_read);

      // iteração pra remover os espaços em brancos do array
      for($i=0; $i<count($array_dados); $i++) {
        array_push($dados_formatados, trim($array_dados[$i]));
      }

      $nome_arquivo = $file->getClientOriginalName();
      // // $extensao_arquivo = $file->getClientOriginalExtension();
      //
      $file->storeAs('extratos-bancarios', $nome_arquivo, 'archive');

      $contents = fopen(public_path("extratos-bancarios/".$nome_arquivo), "r");

      date_default_timezone_set('America/Sao_Paulo');
      $date = date('Y-m-d');
      $date_historico = date('d-m-Y');
      $hora_envio = date('H:i:s');

      //salva os dados tabela extrato_bancario
      $extrato->COD_CLIENTE = session('codigologin');
      $extrato->CNPJ = $dados_cliente->CPF_CNPJ;
      $extrato->DATA = $date;
      $extrato->DATA_ENVIO = $date;
      $extrato->HORA_ENVIO = $hora_envio;
      $extrato->HISTORICO = Auth::user()->USUARIO . " fez a conciliação em " . $date_historico . " às " . $hora_envio;
      $extrato->COD_STATUS_BANCARIO = 1;
      $extrato->save();

      //salva os dados do arquivo
      for($i=0; $i<count($array_dados); $i++) {
        if (strpos($dados_formatados[$i], '<TRNTYPE>') !== false) {
          $trntype = explode("<TRNTYPE>", $dados_formatados[$i]);
          $dados_arquivo_extratos->TRNTYPE = $trntype[1];        }
        else if (strpos($dados_formatados[$i], '<DTPOSTED>') !== false) {
          $dtposted = explode("<DTPOSTED>", $dados_formatados[$i]);
          $dados_arquivo_extratos->DTPOSTED = $dtposted[1];
        }
        else if (strpos($dados_formatados[$i], '<TRNAMT>') !== false) {
          $trnamt = explode("<TRNAMT>", $dados_formatados[$i]);
          $dados_arquivo_extratos->TRNAMT = $trnamt[1];
        }
        else if (strpos($dados_formatados[$i], '<FITID>') !== false) {
          $fitid = explode("<FITID>", $dados_formatados[$i]);
          $dados_arquivo_extratos->FITID = $fitid[1];
        }
        else if (strpos($dados_formatados[$i], '<CHECKNUM>') !== false) {
          $checknum = explode("<CHECKNUM>", $dados_formatados[$i]);
          $dados_arquivo_extratos->CHECKNUM = $checknum[1];
        }
        else if (strpos($dados_formatados[$i], '<MEMO>') !== false) {
          $memo = explode("<MEMO>", $dados_formatados[$i]);
          $dados_arquivo_extratos->MEMO =  mb_convert_encoding($memo[1], 'UTF-8', 'UTF-8');
        }

        else if(strpos($dados_formatados[$i], '</STMTTRN>') !== false) {
          $dados_arquivo_extrato = new DadosArquivoConciliacaoBancariaModel();

          $dados_arquivo_extrato->CODIGO_CONCILIACAO_BANCARIA = $extrato->CODIGO;
          $dados_arquivo_extrato->TRNTYPE = $dados_arquivo_extratos->TRNTYPE;
          $dados_arquivo_extrato->DTPOSTED = $dados_arquivo_extratos->DTPOSTED;
          $dados_arquivo_extrato->TRNAMT = $dados_arquivo_extratos->TRNAMT;
          $dados_arquivo_extrato->FITID = $dados_arquivo_extratos->FITID;
          $dados_arquivo_extrato->MEMO = $dados_arquivo_extratos->MEMO;
          $dados_arquivo_extrato->save();
        }
      }
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
