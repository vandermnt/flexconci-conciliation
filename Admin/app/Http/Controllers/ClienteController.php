<?php

namespace App\Http\Controllers;

use Request;
use App\ClienteModel;
use App\VendasModel;


class ClienteController extends Controller{

  public function dadosCliente(){
    echo "dkwdopwakdopwkwdpodkadopakdapd";
    $codigo = Request::only('codigo');

    $dados_venda = VendasModel::where('CODIGO', '=', $codigo)->first();

    $dados_cliente = ClienteModel::where('CODIGO', '=', $dados_venda->COD_CLIENTE)->first();

    return json_encode($dados_venda, $dados_cliente);
  }
}
