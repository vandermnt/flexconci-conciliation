<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\JustificativaModel;

class CadastroJustificativaController extends Controller{

  public function justificativas(){

    $justificativas = DB::table("justificativa")->where('COD_CLIENTE', '=', session('codigologin'))->get();

    return view('cadastro.justificativas')->with('justificativas', $justificativas);
  }

  public function saveJustificativa(Request $request){

    $justificativa = new JustificativaModel();

    date_default_timezone_set('America/Sao_Paulo');
    $date = date('Y-m-d');

    $justificativa->COD_CLIENTE = session('codigologin');
    $justificativa->DATA_CADASTRO = $date;
    $justificativa->DATA_ALTERACAO = $date;
    $justificativa->JUSTIFICATIVA = mb_strtoupper($request->get('justificativa'));
    $justificativa->JUSTIFICATIVA_GLOBAL = 'N';

    $justificativa->save();

    return json_encode($justificativa);
  }

  public function loadJustificativas(){

    $justificativas = DB::table("justificativa")
    ->where('COD_CLIENTE', '=', session('codigologin'))
    ->select('justificativa.*')
    ->get();

    return $justificativas;
  }

  public function deleteJustificativa($codigo_historico){
    $justificativa = JustificativaModel::where("CODIGO", "=", $codigo_historico)->first();
    $justificativa->delete();

    return response()->json(200);
  }

    public function show($id){
        return JustificativaModel::where('CODIGO', "=", $id)->first();
    }

    public function update(Request $request, $codigo){
        $justificativa = JustificativaModel::where('CODIGO', $codigo)->first();

        if(isset($justificativa)){
            $justificativa->JUSTIFICATIVA = mb_strtoupper($request->get('justificativa'));

            $justificativa->save();
        }

        return json_encode($justificativa);
    }
}
