<?php

namespace App\Http\Controllers;

use Request;
use DB;
use App\ClienteModel;
use App\CredenciamentoCieloModel;

class AutorizacaoAcessoControllerTeste extends Controller{

  public function autorizarAcesso(){

    $cliente = ClienteModel::where('CODIGO', '=', session('codigologin'))->first();

    $acess_token = Request::only('acess_token');
    $refresh_token = Request::only('refresh_token');

    $cliente->ACCESS_TOKEN = $acess_token['acess_token'];
    $cliente->REFRESH_TOKEN = $refresh_token['refresh_token'];

    $cliente->save();

    return json_encode(true);
  }

  public function credenciarEdi(){
    // $register_id = Request::only('registerID');
    // $email = Request::only('email');
    //
    // $access_token = Request::only('acess_token');
    // $refresh_token = Request::only('refresh_token');
    // // $code = Request::only('codigo');
    // $mainMerchantId = Request::only('mainMerchantId');

    // $credenciamento_cielo = new CredenciamentoCieloModel();
    //
    // $credenciamento_cielo->ACCESS_TOKEN = $acess_token['acess_token'];
    // $credenciamento_cielo->REFRESH_TOKEN = $refresh_token['refresh_token'];
    // $credenciamento_cielo->REGISTER_ID = $register_id['registerID'];
    // $credenciamento_cielo->MAIN_MERCHANT_ID = $mainMerchantId['mainMerchantId'];
    //
    // $credenciamento_cielo->save();

    // echo $code;
    // $cliente->EMAIL = $email['email'];
    // $cliente->REGISTER_ID = $register_id['registerID'];
    //
    // $cliente->save();

    return json_encode("teste");
  }
}
