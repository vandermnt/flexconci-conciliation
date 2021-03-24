<?php

namespace App\Http\Controllers;
use App\BancoModel;
use Illuminate\Http\Request;

class BancoController extends Controller {

  public function index() {
    $bancos = BancoModel::orderBy('BANCO', 'asc')->get();
    $bancos_count = $bancos->count();
    return view('cadastro.banco')->with('bancos', $bancos)->with('count_bancos', $bancos_count);
  }

  public function cadastrarBanco(Request $request) {
    $banco = new BancoModel();
    $nome_banco = $request->input('banco');

    try {
      $banco->BANCO = $nome_banco;

      $banco->save();

      return response()->json([
        'banco-criado' => $banco
      ]);
    } catch (Exception $e) {
      return response()->json([
        "error" => $e->getMessage()
      ]);
    }
  }

  public function allBancos(){
    try {
      $bancos = BancoModel::orderBy('BANCO', 'asc')->get();
      return response()->json([
        'bancos' => $bancos
      ]);
    } catch (Exception $e) {
      return response()->json([
        "error" => $e->getMessage()
      ]);
    }
  }

  public function updateBanco(Request $request, $codigo){
    $banco_nome = $request->input('banco');

    $banco = BancoModel::where('CODIGO', $codigo)->first();

    if(isset($banco)){
      $banco->BANCO = $banco_nome;
      $banco->save();

      return response()->json(200);
    }
    return response()->json(500);
  }

  public function excluirBanco($codigo_banco) {
    try {
      $banco = BancoModel::where("CODIGO", "=", $codigo_banco)->first();
      $banco->delete();

      return response()->json(200);

    } catch (Exception $e) {
      return response()->json([
        "error" => $e->getMessage()
      ]);
    }
  }
}
