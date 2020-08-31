<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ProjetosController extends Controller{

  public function listarProjetos(){
    $sql = 'Select  projetos.*, tipo_projeto.TIPO_PROJETO, clientes.NOME, funcionarios.NOME as NOME_FUNCIONARIO  from projetos  left outer join tipo_projeto on (TIPO_PROJETO.CODIGO = projetos.COD_TIPO_PROJETO) left outer join funcionarios on (funcionarios.CODIGO = projetos.COD_FUNCIONARIO_RESP_PROJETO) left outer join clientes on (clientes.CODIGO = projetos.COD_CLIENTE) where projetos.cod_cliente ='.session('codigologin');
    $projetos = DB::select($sql);
    // DD($projetos);
    // dd($teste[0]->NOME);
    return view('projetos.todos-projetos')->with('projetos', $projetos);
  }

  public function detalhamentoProjeto($codprojeto){

    $sql_projeto = 'Select  projetos.*, tipo_projeto.TIPO_PROJETO, clientes.NOME, funcionarios.NOME as NOME_FUNCIONARIO  from projetos  left outer join tipo_projeto on (TIPO_PROJETO.CODIGO = projetos.COD_TIPO_PROJETO) left outer join funcionarios on (funcionarios.CODIGO = projetos.COD_FUNCIONARIO_RESP_PROJETO) left outer join clientes on (clientes.CODIGO = projetos.COD_CLIENTE) where projetos.CODIGO ='.$codprojeto;
    $p = DB::select($sql_projeto);

    $sql_projeto = 'Select prod_projetos.*, adquirentes.ADQUIRENTE, status_homologacao.STATUS_HOMOLOGACAO from prod_projetos left outer join adquirentes on (adquirentes.CODIGO = prod_projetos.COD_ADQUIRENTE) left outer join status_homologacao on (STATUS_HOMOLOGACAO.CODIGO = prod_projetos.COD_STATUS) where prod_projetos.COD_PROJETO ='.$codprojeto;
    $projeto = DB::select($sql_projeto);

    return view('projetos.projetos')->with('projeto', $projeto)->with('p', $p);
  }
}
