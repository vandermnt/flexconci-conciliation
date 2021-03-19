<?php

namespace App\Http\Controllers;
use App\BandeiraModel;
use Illuminate\Http\Request;

class BandeiraController extends Controller {

  public function index() {
    $bandeiras = BandeiraModel::orderBy('BANDEIRA', 'asc')->get();
    $bandeiras_count = $bandeiras->count();
    return view('cadastro.bandeira')->with('bandeiras', $bandeiras)->with('count_bandeiras', $bandeiras_count);
  }

  public function cadastrarBandeira(Request $request) {
    $bandeira = new BandeiraModel();
    $nome_bandeira = $request->input('bandeira');

    try {
      $bandeira->BANDEIRA = $nome_bandeira;

      $bandeira->save();

      return response()->json([
        'bandeira-criada' => $bandeira
      ]);
    } catch (Exception $e) {
      return response()->json([
        "error" => $e->getMessage()
      ]);
    }
  }

  public function allBandeiras(){
    try {
      $bandeiras = BandeiraModel::orderBy('BANDEIRA', 'asc')->get();
      return response()->json([
        'bandeiras' => $bandeiras
      ]);
    } catch (Exception $e) {
      return response()->json([
        "error" => $e->getMessage()
      ]);
    }
  }

  public function updateBandeira(Request $request, $codigo){
    $bandeira_nome = $request->input('bandeira');

    $bandeira = BandeiraModel::where('CODIGO', $codigo)->first();

    if(isset($bandeira)){
      $bandeira->BANDEIRA = $bandeira_nome;
      $bandeira->save();

      return response()->json(200);
    }
    return response()->json(500);
  }

  public function excluirBandeira($codigo_bandeira) {
    try {
      $bandeira = BandeiraModel::where("CODIGO", "=", $codigo_bandeira)->first();
      $bandeira->delete();

      return response()->json(200);

    } catch (Exception $e) {
      return response()->json([
        "error" => $e->getMessage()
      ]);
    }
  }
}
