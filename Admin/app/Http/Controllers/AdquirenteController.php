<?php

namespace App\Http\Controllers;
use App\AdquirentesModel;
use Illuminate\Http\Request;

class AdquirenteController extends Controller {

  public function index() {
    $adquirentes = AdquirentesModel::all();
    $adquirentes_count = $adquirentes->count();
    return view('cadastro.adquirente')->with('adquirentes', $adquirentes)->with('count_adquirentes', $adquirentes_count);
  }

  public function cadastrarAdquirente(Request $request) {
    $adquirente = new AdquirentesModel();
    $nome_adquirente = $request->input('adquirente');

    try {
      $adquirente->ADQUIRENTE = $nome_adquirente;

      $adquirente->save();

      return response()->json([
        'adquirente-criado' => $adquirente
      ]);
    } catch (Exception $e) {
      return response()->json([
        "error" => $e->getMessage()
      ]);
    }
  }

  public function allAdquirentes(){
    try {
      $adquirentes = AdquirentesModel::all();
      return response()->json([
        'adquirentes' => $adquirentes
      ]);
    } catch (Exception $e) {
      return response()->json([
        "error" => $e->getMessage()
      ]);
    }
  }

  public function updateAdquirente(Request $request, $codigo){
    $adquirente_nome = $request->input('adquirente');

    $adquirente = AdquirentesModel::where('CODIGO', $codigo)->first();

    if(isset($adquirente)){
      // $adquirente->ADQUIRENTE = mb_strtoupper($adquirente_nome);
      $adquirente->ADQUIRENTE = $adquirente_nome;
      $adquirente->save();

      return response()->json(200);
    }

    return response()->json(500);
  }

  public function excluirAdquirente($codigo_adquirente) {
    $adquirente = AdquirentesModel::where("CODIGO", "=", $codigo_adquirente)->first();
    $adquirente->delete();

    return response()->json(200);
  }

}
