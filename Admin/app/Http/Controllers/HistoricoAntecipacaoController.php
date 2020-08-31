<?php

namespace App\Http\Controllers;

use DB;
use Request;
use App\HistoricoAntecipacaoModel;
use App\HistoricoAntecipacaoConciModel;

class HistoricoAntecipacaoController extends Controller{

  public function antecipation(){

    $results = session('result');
    $v_liquido = session('valor_liquido');

    $historico_antecipacao = new HistoricoAntecipacaoModel();

    // $teste = DB::connection('mysql_trava')->table('historico_antecipacao')->get();

    foreach ($results  as $result ) {

      $historico_antecipacao->DATA_CADASTRO = date("Y/m/d");
      $historico_antecipacao->COD_STATUS = 1;

      $historico_antecipacao->ADQUIRENTE = $result->ADQUIRENTE;
      $historico_antecipacao->NSU = $result->NSU;
      $historico_antecipacao->VALOR_BRUTO = $result->VALOR_BRUTO;
      $historico_antecipacao->VALOR_LIQUIDO = $result->VALOR_LIQUIUDO;
      $historico_antecipacao->TAXA = 2;

      $resultado1 = 2 * $result->VALOR_LIQUIUDO;
      $resultado2 = $resultado1 / 100;
      $valor_com_desc_taxa = $result->VALOR_LIQUIUDO - $resultado2;

      $valor_com_desc_taxa = number_format($valor_com_desc_taxa, 2, '.', '');

      $historico_antecipacao->VALOR_COM_DESCONTO_TAXA = $valor_com_desc_taxa;
      $historico_antecipacao->DATA_PREV_PGTO_INICIAL = $result->DATA_PGTO;
      $historico_antecipacao->CNPJ_CLIENTE = "001472861000135";
      $historico_antecipacao->CNPJ_CONCILIADORA = "22162885000168";

      $historico_antecipacao->save();

      $historico_antecipacao = new HistoricoAntecipacaoModel();
    }

    $historico_antecipacao_conci = new HistoricoAntecipacaoConciModel();
    $historico_antecipacao_conci->DATA_EMISSAO = date("Y/m/d");

    $v_liquido = number_format($v_liquido, 2, '.', '');

    $historico_antecipacao_conci->VALOR_SOLICITADO = $v_liquido;
    $historico_antecipacao_conci->TAXA_UTILIZADO = 2;

    $com_desconto1 = 2 * $v_liquido;
    $com_desconto2 = $com_desconto1 / 100;
    $com_desconto3 = $v_liquido - $com_desconto2;

    $com_desconto3 = number_format($com_desconto3, 2, '.', '');

    $historico_antecipacao_conci->VALOR_LIQUIDO_ESPERADO = $com_desconto3;
    $historico_antecipacao_conci->CNPJ_CLIENTE = "001472861000135";

    $historico_antecipacao_conci->save();

    $codigo = $historico_antecipacao_conci->id;

    $historico_antecipacao_conci = new HistoricoAntecipacaoConciModel();

    $result = null;
    $count = null;
    $valor_liquido = null;
    $val_para_receber = null;
    $success = true;

    session()->put('success', $success);
    session()->put('id_processo', $codigo);

    return view('antecipacao')
    ->with('result', $result)
    ->with('valor_liquido', $valor_liquido)
    ->with('val_para_receber', $val_para_receber)
    ->with('count', $count);
  }
}
