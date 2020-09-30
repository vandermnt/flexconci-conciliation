<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\DashboardVendasModel;
use App\ClienteModel;

class DashboardController extends Controller{

  public function dashboard(){
    $sql = 'Select  projetos.*, tipo_projeto.TIPO_PROJETO, clientes.NOME  from projetos  left outer join tipo_projeto on (TIPO_PROJETO.CODIGO = projetos.COD_TIPO_PROJETO) left outer join funcionarios on (funcionarios.CODIGO = projetos.COD_FUNCIONARIO_RESP_PROJETO) left outer join clientes on (clientes.CODIGO = projetos.COD_CLIENTE) where projetos.cod_cliente ='.session('codigologin');
    $projetos = DB::select($sql);
    $qtde_projetos = count($projetos);

    $dados_dash_vendas = DB::table('dashboard_vendas_adquirentes')
    ->join('periodo_dash', 'dashboard_vendas_adquirentes.COD_PERIODO', 'periodo_dash.CODIGO')
    ->join('adquirentes', 'dashboard_vendas_adquirentes.COD_ADQUIRENTE', 'adquirentes.CODIGO')
    // ->where('cod_cliente', '=', session('codigologin'))->get();
    ->where('cod_cliente', '=', 538)->get();

    $dados_dash_vendas_bandeira = DB::table('dashboard_vendas_bandeiras')
    ->join('periodo_dash', 'dashboard_vendas_bandeiras.COD_PERIODO', 'periodo_dash.CODIGO')
    ->join('bandeira', 'dashboard_vendas_bandeiras.COD_BANDEIRA', 'bandeira.CODIGO')
    // ->where('cod_cliente', '=', session('codigologin'))->get();
    ->where('cod_cliente', '=', 538)->get();

    $dados_dash_vendas_modalidade = DB::table('dashboard_vendas_modalidade')
    ->join('periodo_dash', 'dashboard_vendas_modalidade.COD_PERIODO', 'periodo_dash.CODIGO')
    ->join('modalidade', 'dashboard_vendas_modalidade.COD_MODALIDADE', 'modalidade.CODIGO')
    // ->where('cod_cliente', '=', session('codigologin'))->get();
    ->where('cod_cliente', '=', 538)
    ->groupBy('dashboard_vendas_modalidade.COD_MODALIDADE')
    ->get();

    $dados_calendario = DB::table('vendas')
    ->select('vendas.DATA_PREVISTA_PAGTO')
    ->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
    // ->select('vendas.*', 'sum(VALOR_LIQUIDO) as val_liquido')
    ->where('cod_cliente', '=', session('codigologin'))
    ->groupBy('vendas.DATA_PREVISTA_PAGTO')
    ->get();

    $dados_calendario_pagamento = DB::table('pagamentos_operadoras')
    ->select('pagamentos_operadoras.DATA_PAGAMENTO')
    ->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
    // ->select('vendas.*', 'sum(VALOR_LIQUIDO) as val_liquido')
    ->where('pagamentos_operadoras.COD_CLIENTE', '=', session('codigologin'))
    ->groupBy('pagamentos_operadoras.DATA_PAGAMENTO')
    ->get();

    $data = date('Y-m-d');
    // dd($data);

    // dd($dados_calendario_pagamento);

    // dd($dados_dash_vendas_bandeira);
    $dados_cliente = ClienteModel::where('CODIGO', '=', session('codigologin'))->first();

    session()->put('periodo', 2);
    session()->put('grupo', 1);

    return view('analytics.analytics-index')
    // ->with('qtde_projetos', $qtde_projetos)
    ->with('projetos', $projetos)
    ->with('dados_dash_vendas_bandeira', $dados_dash_vendas_bandeira)
    ->with('dados_dash_vendas_modalidade', $dados_dash_vendas_modalidade)
    ->with('dados_dash_vendas', $dados_dash_vendas)
    ->with('dados_cliente', $dados_cliente)
    ->with('data', $data)
    ->with('dados_calendario', $dados_calendario)
    ->with('dados_calendario_pagamento', $dados_calendario_pagamento)
    ->with('periodos', $periodos);
  }
}
