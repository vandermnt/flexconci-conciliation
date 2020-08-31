<?php

namespace App\Http\Controllers;

use Request;
use App\PrevisaoPagamentoModel;
use Illuminate\Pagination\Paginator;

class AntecipacaoController extends Controller{

  public function antecipar(){
    $result = null;
    $count = null;
    $valor_liquido = null;
    $val_para_receber = null;
    $success = false;

    session()->put('success', $success);

    return view('antecipacao')
    ->with('result', $result)
    ->with('valor_liquido', $valor_liquido)
    ->with('val_para_receber', $val_para_receber)
    ->with('count', $count);
  }

  public function filtro(){
    $tipo_filtro = Request::input('tipo_filtro');

    if($tipo_filtro == 1){
      $date_inicial = Request::input('date_inicial');
      $date_final = Request::input('date_final');

      $count = PrevisaoPagamentoModel::where('DATA_PGTO', '>', $date_inicial)
      ->where('DATA_PGTO', '<', $date_final)
      ->count();

      $valor_liquido = PrevisaoPagamentoModel::whereDate('DATA_PGTO', '>', $date_inicial)
      ->whereDate('DATA_PGTO', '<', $date_final)
      ->sum('VALOR_LIQUIUDO');

      $mult = 2 * $valor_liquido;
      $div = $mult / 100;
      $val_para_receber = $valor_liquido - $div;

      $result = PrevisaoPagamentoModel::whereDate('DATA_PGTO', '>', $date_inicial)
      ->whereDate('DATA_PGTO', '<', $date_final)
      ->paginate(50);

      $resultsp = PrevisaoPagamentoModel::whereDate('DATA_PGTO', '>', $date_inicial)
      ->whereDate('DATA_PGTO', '<', $date_final)
      ->get();

      session()->put('result', $resultsp);
      session()->put('valor_liquido', $valor_liquido);

      return view('antecipacao')->with('result', $result)
      ->with('date_inicial', $date_inicial)
      ->with('date_final', $date_final)
      ->with('tipo_filtro', $tipo_filtro)
      ->with('count', $count)
      ->with('val_para_receber', $val_para_receber)
      ->with('valor_liquido', $valor_liquido);


    }else if($tipo_filtro == 2){
      $val_antecipacao = Request::input('val_antecipacao');
      $aux = false;
      $num_registros = 1;

      $val_antecipacao = str_replace("." , "" , $val_antecipacao ); // Primeiro tira os pontos
      // $val_antecipacao = str_replace("," , "" , $val_antecipacao); // Depois tira a vírgula

      while($aux==false){
        $soma = 0;
        $result = PrevisaoPagamentoModel::limit($num_registros)->get();

        foreach($result as $val){
          $soma = $soma + $val->VALOR_LIQUIUDO;
        }

        if($soma > $val_antecipacao){

          session()->put('result', $result);

          $aux = true;

          $mult = 2 * $soma;
          $div = $mult / 100;
          $val_para_receber = $soma - $div;

          session()->put('valor_liquido', $soma);

          return view('antecipacao')
          ->with('result', $result)
          ->with('val_antecipacao', $val_antecipacao)
          ->with('count', $num_registros)
          ->with('val_para_receber', $val_para_receber)
          ->with('valor_liquido', $soma);
        }

        $num_registros++;
      }
      return null;
    }
  }
}
