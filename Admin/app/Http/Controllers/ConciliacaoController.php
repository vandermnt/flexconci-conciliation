<?php

namespace App\Http\Controllers;

use Request;
use DateTime;
use DB;
use App\ConciliacaoBancariaModel;
use App\ClienteModel;
use App\DadosArquivoConciliacaoBancariaModel;
use App\PagamentoOperadoraModel;
use Auth;

class ConciliacaoController extends Controller{

  public function conciliacaoBancaria(){
    date_default_timezone_set('America/Sao_Paulo');

    $lines = array();
    $movimentacoes = [];
    $dados_formatados = [];
    $array_dados = [];
    $conta = null;
    $banco = null;
    $date_start = null;
    $date_end = null;
    $existsTransacao = false;

    $pagamentos_operadoras = $this->buscaPagamentosOperadoras();
    $dados_cliente = $this->getDadosCliente();

    $arquivos = Request::file('extratos');
    $transacoes = DadosArquivoConciliacaoBancariaModel::where('CODIGO_CLIENTE', session('codigologin'))->get();

    try {
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

        $file->storeAs('extratos-bancarios', $nome_arquivo, 'archive');

        $contents = fopen(public_path("extratos-bancarios/".$nome_arquivo), "r");

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
            $dados_arquivo_extratos->TRNTYPE = $trntype[1];
          }
          else if (strpos($dados_formatados[$i], '<BANKID>') !== false) {
            $bankid = explode("<BANKID>", $dados_formatados[$i]);
            $banco = $bankid[1];
          }
          else if (strpos($dados_formatados[$i], '<ACCTID>') !== false) {
            $acctid = explode("<ACCTID>", $dados_formatados[$i]);
            $conta = $acctid[1];
          }
          else if (strpos($dados_formatados[$i], '<DTSTART>') !== false) {
            $dt_start = explode("<DTSTART>", $dados_formatados[$i]);
            $formata_data_start = DateTime::createFromFormat('YmdHis', $dt_start[1]);
            $date_start = $formata_data_start->format('Y-m-d');
          }
          else if (strpos($dados_formatados[$i], '<DTEND>') !== false) {
            $dt_end = explode("<DTEND>", $dados_formatados[$i]);
            $formata_data_end = DateTime::createFromFormat('YmdHis', $dt_end[1]);
            $date_end = $formata_data_end->format('Y-m-d');
          }
          else if (strpos($dados_formatados[$i], '<DTPOSTED>') !== false) {
            $dtposted = explode("<DTPOSTED>", $dados_formatados[$i]);
            $dados_arquivo_extratos->DTPOSTED = $dtposted[1];

            $formata_dtposted = DateTime::createFromFormat('YmdHis', $dtposted[1]);
            $dataPosted = $formata_dtposted->format('Y-m-d');
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
            $tiraEspacoMemo = explode(" ", $dados_arquivo_extratos->MEMO);
            $tiraEspacoTrnamt = explode(",", $dados_arquivo_extratos->TRNAMT);

            $memo = implode("", $tiraEspacoMemo);
            $trnamt = implode("", $tiraEspacoTrnamt);

            $chave = $dados_arquivo_extratos->TRNTYPE . $dados_arquivo_extratos->DTPOSTED . $trnamt . $dados_arquivo_extratos->FITID .
            $memo . $banco . $conta;

            foreach ($transacoes as $transacao) {
              if ($transacao->CHAVE === $chave) {
                $existsTransacao = true;
                break;
              }
            }

            if (!$existsTransacao) {
              $data_envio = date('Y-m-d');
              $hora_envio = date('h:i:s');
              $email_responsavel = null;

              $TRNAMTsemVirgula = str_replace(",",".", $dados_arquivo_extratos->TRNAMT);
              $dados_arquivo_extrato = new DadosArquivoConciliacaoBancariaModel();
              $dados_arquivo_extrato->CODIGO_CONCILIACAO_BANCARIA = $extrato->CODIGO;
              $dados_arquivo_extrato->TRNTYPE = $dados_arquivo_extratos->TRNTYPE;
              $dados_arquivo_extrato->DTPOSTED = $dataPosted;
              $dados_arquivo_extrato->TRNAMT = $TRNAMTsemVirgula;
              $dados_arquivo_extrato->FITID = $dados_arquivo_extratos->FITID;
              $dados_arquivo_extrato->MEMO = $dados_arquivo_extratos->MEMO;
              $dados_arquivo_extrato->CODIGO_BANCO = $banco;
              $dados_arquivo_extrato->NUMERO_CONTA = $conta;
              $dados_arquivo_extrato->DT_START = $date_start;
              $dados_arquivo_extrato->DT_END = $date_end;
              $dados_arquivo_extrato->CODIGO_CLIENTE = session('codigologin');
              $dados_arquivo_extrato->CHAVE = $chave;
              $dados_arquivo_extrato->DATA_ENVIO = $data_envio;
              $dados_arquivo_extrato->HORA_ENVIO = $hora_envio;
              $dados_arquivo_extrato->EMAIL_RESPONSAVEL = session('emailuserlogado');
              $existsTransacao = false;

              $dados_arquivo_extrato->save();
            }
          }
        }

        $this->checkHistoricoBancario($pagamentos_operadoras);

      }
      return response()->json(200);
    } catch (\Exception $e) {
      return response()->json([
        "Error" => $e,
      ], 500);
    }
  }

  public function atualizarConciliacoesProcessadas(){
    $conciliacoes = ConciliacaoBancariaModel::where('COD_CLIENTE', '=', session('codigologin'))
    ->where('COD_STATUS_BANCARIO', '=', 1)
    ->get();

    return $conciliacoes;
  }

  public function buscaPagamentosOperadoras() {
    $pagamentos_operadoras = DB::table('pagamentos_operadoras')
    ->leftJoin('adquirentes', 'pagamentos_operadoras.COD_ADQUIRENTE', 'adquirentes.CODIGO')
    ->select('pagamentos_operadoras.*', 'adquirentes.ADQUIRENTE')
    ->where('COD_CLIENTE', session('codigologin'))
    ->whereNull('CHAVE_EXTRATO_BANCARIO')
    ->groupBy('COD_ADQUIRENTE')
    ->get();

    return $pagamentos_operadoras;
  }

  public function somaPagamentosOperadorasPorData($dataPagamento) {
    $somaPagamentosOperadoras = DB::table('pagamentos_operadoras')
    ->where('COD_CLIENTE', session('codigologin'))
    ->where('DATA_PAGAMENTO', $dataPagamento)
    ->whereNull('CHAVE_EXTRATO_BANCARIO')
    ->sum('pagamentos_operadoras.VALOR_LIQUIDO');

    return $somaPagamentosOperadoras;
  }

  public function getDadosCliente() {
    $cliente = ClienteModel::where('CODIGO', '=', session('codigologin'))->first();
    return $cliente;
  }

  public function checkHistoricoBancario($pagamentos_operadoras) {
    if ($pagamentos_operadoras) {
      foreach ($pagamentos_operadoras as $pagamento) {
        $adquirente = strtoupper($pagamento->ADQUIRENTE);

        $extratoBancario = DB::table('historico_banco')->where('COD_ADQUIRENTE', $pagamento->COD_ADQUIRENTE)->get();

        $listaMemo = $this->mountListMemo($extratoBancario);

        $movimentacoes = $this->checkExtratoBancario($listaMemo);

        $this->checkPagamentosOperadoras($movimentacoes, $pagamento->COD_ADQUIRENTE);

        return true;
      }
    }

    return false;
  }

  public function checkExtratoBancario($listMemos) {

    $movimentacoes =  DadosArquivoConciliacaoBancariaModel::select('dados_arquivo_conciliacao_bancaria.*')
    ->selectRaw('sum(TRNAMT) as SUM_VALOR_LIQUIDO')
    ->where('CODIGO_CLIENTE', session('codigologin'))
    ->where(function($query) use($listMemos) {
      for ($i = 0; $i < count($listMemos); $i++){
        $query->orWhere('MEMO', 'LIKE', "%{$listMemos[$i]}%");
      }
    })
    ->where('EMAIL_RESPONSAVEL', session('emailuserlogado'))
    ->whereNull('COD_STATUS_CONCILIACAO')
    ->groupBy('DTPOSTED')
    ->get();

    return $movimentacoes;
  }

  public function mountListMemo($extratoBancario) {
    $listMemo = [];
    if ($extratoBancario) {
      foreach ($extratoBancario as $memo) {
        array_push($listMemo, $memo->HISTORICO_BANCO);
      }
      return $listMemo;
    }
    return;
  }

  public function checkPagamentosOperadoras($movimentacoes, $codAdquirente) {

    if ($movimentacoes && $codAdquirente) {

      foreach ($movimentacoes as $movimentacao) {
        $sumPagamentos = $this->somaPagamentosOperadorasPorData($movimentacao->DTPOSTED);

        $sumPagamentosOperadoras = (float)number_format(floor(($sumPagamentos*100))/100, 2, '.', '');
        $sumExtratoBancario = (float)number_format(floor(($movimentacao->SUM_VALOR_LIQUIDO*100))/100, 2, '.', '');

        if ((string)($sumPagamentosOperadoras + 0.01) == (string)$sumExtratoBancario || (string)$sumPagamentosOperadoras == (string)($sumExtratoBancario + 0.01)) {
          $this->updateMovimentacoesExtrato($movimentacao->DTPOSTED, $movimentacao->CODIGO_BANCO, $movimentacao->NUMERO_CONTA, 1);
        } elseif ((string)$sumPagamentosOperadoras == (string)$sumExtratoBancario) {
          $this->updateMovimentacoesExtrato($movimentacao->DTPOSTED, $movimentacao->CODIGO_BANCO, $movimentacao->NUMERO_CONTA, 3);
        }
      }
    }
  }

  public function updateMovimentacoesExtrato($dtposted, $codigoBanco, $numeroConta, $statusConciliacao) {
    if ($dtposted) {
      $movimetacoesPorData = $this->getMovimentacoesPorData($dtposted);

      $dataChave = date('Y-m-d');
      $horaChave = date('h:i:s');

      if ($movimetacoesPorData) {
        foreach ($movimetacoesPorData as $movimentacaoPorData) {
          $movimentacaoPorData->COD_STATUS_CONCILIACAO = $statusConciliacao;
          $movimentacaoPorData->CONSIDERA_CONCILIACAO = 'S';
          $movimentacaoPorData->CHAVE_CONCILIACAO = session('codigologin') . $movimentacaoPorData->CODIGO_BANCO .
          $movimentacaoPorData->NUMERO_CONTA . $dataChave . $horaChave;

          session()->put('chave_movimentacao', $movimentacaoPorData->CHAVE_CONCILIACAO);

          $movimentacaoPorData->save();
        }

        $this->updatePagamentosOperadoras($dtposted, $codigoBanco, $numeroConta);
      }
    }
  }

  public function updatePagamentosOperadoras($dataPagamento, $banco, $conta) {
    if ($dataPagamento) {
      $listaPagamentosOperadoras = $this->getPagamentosOperadoraPorData($dataPagamento, $banco, $conta);

      if ($listaPagamentosOperadoras) {
        foreach ($listaPagamentosOperadoras as $pagamentoOperadora) {
          $pagamentoOperadora->CHAVE_EXTRATO_BANCARIO = session('chave_movimentacao');
          $pagamentoOperadora->save();
        }
      }
    }
  }

  public function getMovimentacoesPorData($dtposted) {
    $movimentacoes =  DadosArquivoConciliacaoBancariaModel::where('CODIGO_CLIENTE', session('codigologin'))
    ->where('DTPOSTED', $dtposted)
    ->where('EMAIL_RESPONSAVEL', session('emailuserlogado'))
    ->whereNull('COD_STATUS_CONCILIACAO')
    ->whereNull('CONSIDERA_CONCILIACAO')
    ->get();

    return $movimentacoes;
  }

  public function getPagamentosOperadoraPorData($dataPagamento, $banco, $conta) {
    $pagamentosOperadoras = PagamentoOperadoraModel::where('COD_CLIENTE', session('codigologin'))
    ->where('DATA_PAGAMENTO', $dataPagamento)
    ->where('COD_BANCO', $banco)
    ->where('CONTA', 'LIKE', "%{$conta}%")
    ->whereNull('CHAVE_EXTRATO_BANCARIO')
    ->get();

    return $pagamentosOperadoras;
  }
}
