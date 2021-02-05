<?php

namespace App\Http\Controllers;

use Request;
use DB;
use Mail;
use App\DashboardVendasModel;
use App\ClienteModel;
use App\Http\Controllers\DOMPDF;


class DashboardController extends Controller{

  public function dashboard(){
    $hoje1 = date('Y/m/d');
    $hoje2 = date('Y/m/30');
    // $sql = 'Select  projetos.*, tipo_projeto.TIPO_PROJETO, clientes.NOME  from projetos  left outer join tipo_projeto on (TIPO_PROJETO.CODIGO = projetos.COD_TIPO_PROJETO) left outer join funcionarios on (funcionarios.CODIGO = projetos.COD_FUNCIONARIO_RESP_PROJETO) left outer join clientes on (clientes.CODIGO = projetos.COD_CLIENTE) where projetos.cod_cliente ='.session('codigologin');
    // $projetos = DB::select($sql);
    // $qtde_projetos = count($projetos);
    $qtde_projetos = null;

    $frase = DB::table('config_cliente')
    ->select('AVISO_GERAL', 'ALERTA_GLOBAL')
    ->whereNotNull('AVISO_GERAL')
    ->first();

    // dd($frase);

    $dados_dash_vendas = DB::table('dashboard_vendas_adquirentes')
    ->leftJoin('periodo_dash', 'dashboard_vendas_adquirentes.COD_PERIODO', 'periodo_dash.CODIGO')
    ->leftJoin('adquirentes', 'dashboard_vendas_adquirentes.COD_ADQUIRENTE', 'adquirentes.CODIGO')
    ->where('cod_cliente', '=', session('codigologin'))->get();
    // ->where('cod_cliente', '=', 538)->get();

    $dados_dash_vendas_bandeira = DB::table('dashboard_vendas_bandeiras')
    ->join('periodo_dash', 'dashboard_vendas_bandeiras.COD_PERIODO', 'periodo_dash.CODIGO')
    ->join('bandeira', 'dashboard_vendas_bandeiras.COD_BANDEIRA', 'bandeira.CODIGO')
    ->where('cod_cliente', '=', session('codigologin'))->get();
    // ->where('cod_cliente', '=', 538)->get();

    // dd($dados_dash_vendas_bandeira);


    $dados_dash_vendas_modalidade = DB::table('dashboard_vendas_modalidade')
    ->join('periodo_dash', 'dashboard_vendas_modalidade.COD_PERIODO', 'periodo_dash.CODIGO')
    ->join('modalidade', 'dashboard_vendas_modalidade.COD_MODALIDADE', 'modalidade.CODIGO')
    ->where('cod_cliente', '=', session('codigologin'))
    // ->groupBy('dashboard_vendas_modalidade.AUTORIZACAO')
    ->get();

    $dados_dash_vendas_produto = DB::table('dashboard_vendas_produtos')
    ->join('periodo_dash', 'dashboard_vendas_produtos.COD_PERIODO', 'periodo_dash.CODIGO')
    ->join('produto_web', 'dashboard_vendas_produtos.COD_PRODUTO', 'produto_web.CODIGO')
    ->where('cod_cliente', '=', session('codigologin'))
    // ->groupBy('dashboard_vendas_produtos.COD_PRODUTO')
    ->get();

    // dd($dados_dash_vendas_produto);

    $dados_calendario = DB::table('vendas')
    ->select('vendas.DATA_PREVISTA_PAGTO')
    ->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
    // ->select('vendas.*', 'sum(VALOR_LIQUIDO) as val_liquido')
    ->where('cod_cliente', '=', session('codigologin'))
    ->groupBy('vendas.DATA_PREVISTA_PAGTO')
    ->get();

    $dados_calendario_pagamento = DB::table('pagamentos_operadoras')
    ->select('pagamentos_operadoras.*', 'pagamentos_operadoras.DATA_PAGAMENTO')
    ->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
    // ->select('vendas.*', 'sum(VALOR_LIQUIDO) as val_liquido')
    ->where('pagamentos_operadoras.COD_CLIENTE', '=', session('codigologin'))
    ->groupBy('pagamentos_operadoras.DATA_PAGAMENTO')
    ->get();

    $total_mes = DB::table('vendas')
    ->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
    ->where('vendas.DATA_PREVISTA_PAGTO', '=', $hoje1)
    ->where('vendas.COD_CLIENTE', '=', session('codigologin'))
    ->first();

    $total_futuro = DB::table('vendas')
    ->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
    ->where('vendas.DATA_PREVISTA_PAGTO', '>', $hoje1)
    ->where('vendas.COD_CLIENTE', '=', session('codigologin'))
    ->first();

    $departamento_chamado = DB::table('departamento_chamado')->get();
    $categoria_chamado = DB::table('categoria_chamado')->get();

    $dados_bancos = DB::table('vendas')
    ->leftJoin('lista_bancos', 'vendas.BANCO', 'lista_bancos.CODIGO')
    ->leftJoin('adquirentes', 'vendas.ADQID', 'adquirentes.CODIGO')
    ->select('vendas.*', 'vendas.DATA_PAGAMENTO', 'lista_bancos.IMAGEM_LINK as IMAGEM', 'adquirentes.IMAGEM as IMAGEMAD')
    ->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
    ->selectRaw('sum(VALOR_BRUTO) as val_bruto')
    ->selectRaw('sum(VALOR_TAXA) as val_tx')
    ->where('vendas.DATA_PREVISTA_PAGTO', '=', $hoje1)
    ->where('vendas.COD_CLIENTE', '=', session('codigologin'));

    $dados_operadora = $dados_bancos->groupBy('vendas.ADQID')
    ->get();

    $dados_bancos = $dados_bancos->groupBy('vendas.BANCO')
    ->get();
    // dd($dados_bancos);

    $total_banco = 0;
    foreach($dados_bancos as $bancos){
      $total_banco += $bancos->val_liquido;
    }

    $data = date('Y-m-d');


    $dados_cliente = ClienteModel::where('CODIGO', '=', session('codigologin'))->first();

    session()->put('nome_fantasia', $dados_cliente->NOME_FANTASIA);
    session()->put('periodo', 2);
    session()->put('grupo', 1);

    return view('analytics.analytics-index')
    ->with('qtde_projetos', $qtde_projetos)
    ->with('projetos', $projetos)
    ->with('dados_bancos', $dados_bancos)
    ->with('dados_bancos_inicial', $dados_bancos_inicial)
    ->with('dados_operadora', $dados_operadora)
    ->with('frase', $frase)
    ->with('total_mes', $total_mes)
    ->with('total_futuro', $total_futuro)


    ->with('total_banco', $total_banco)
    ->with('dados_dash_vendas_bandeira', $dados_dash_vendas_bandeira)
    ->with('dados_dash_vendas_modalidade', $dados_dash_vendas_modalidade)
    ->with('dados_dash_vendas', $dados_dash_vendas)
    ->with('dados_dash_vendas_produto', $dados_dash_vendas_produto)
    ->with('departamento_chamado', $departamento_chamado)
    ->with('categoria_chamado', $categoria_chamado)
    ->with('dados_cliente', $dados_cliente)
    ->with('data', $data)
    ->with('dados_calendario', $dados_calendario)
    ->with('dados_calendario_pagamento', $dados_calendario_pagamento)
    ->with('periodos', $periodos);
  }

  public function exportarPdfVendasOperadoras($codigo_periodo){

    $dados_vendas = DB::table('dashboard_vendas_adquirentes')
    ->leftJoin('periodo_dash', 'dashboard_vendas_adquirentes.COD_PERIODO', 'periodo_dash.CODIGO')
    ->leftJoin('adquirentes', 'dashboard_vendas_adquirentes.COD_ADQUIRENTE', 'adquirentes.CODIGO')
    ->where('cod_cliente', '=', session('codigologin'))
    ->where('COD_PERIODO', '=', $codigo_periodo)
    ->get();

    $total = [
      'total_qtde' => null,
      'total_bruto' => null,
      'total_taxa' => null,
      'total_liquido' => null,
      'total_ticket' => null
    ];

    foreach ($dados_vendas as $vendas) {
      $total['total_qtde'] += $vendas->QUANTIDADE;
      $total['total_bruto'] += $vendas->TOTAL_BRUTO;
      $total['total_taxa'] += $vendas->TOTAL_TAXA;
      $total['total_liquido'] += $vendas->TOTAL_LIQUIDO;
      $total['total_ticket'] += $vendas->TICKET_MEDIO;
    }

    $pdf = \PDF::loadView('analytics.table-vendas-operadora', compact('dados_vendas', 'total'));
    return $pdf->setPaper('A4', 'landscape')
    ->download('dados-vendas-por-operadora.pdf');
  }

  public function exportarPdfVendasBandeiras($codigo_periodo){

    $dados_vendas = DB::table('dashboard_vendas_bandeiras')
    ->leftJoin('periodo_dash', 'dashboard_vendas_bandeiras.COD_PERIODO', 'periodo_dash.CODIGO')
    ->leftJoin('bandeira', 'dashboard_vendas_bandeiras.COD_BANDEIRA', 'bandeira.CODIGO')
    ->where('cod_cliente', '=', session('codigologin'))
    ->where('COD_PERIODO', '=', $codigo_periodo)
    ->where('dashboard_vendas_bandeiras.QUANTIDADE', '>', 0)
    ->get();

    $total = [
      'total_qtde' => null,
      'total_bruto' => null,
      'total_taxa' => null,
      'total_liquido' => null,
      'total_ticket' => null
    ];

    foreach ($dados_vendas as $vendas) {
      $total['total_qtde'] += $vendas->QUANTIDADE;
      $total['total_bruto'] += $vendas->TOTAL_BRUTO;
      $total['total_taxa'] += $vendas->TOTAL_TAXA;
      $total['total_liquido'] += $vendas->TOTAL_LIQUIDO;
      $total['total_ticket'] += $vendas->TICKET_MEDIO;
    }

    $pdf = \PDF::loadView('analytics.table-vendas-bandeira', compact('dados_vendas', 'total'));
    return $pdf->setPaper('A4', 'landscape')
    ->download('dados-vendas-por-bandeira.pdf');
  }

  public function exportarPdfVendasModalidade($codigo_periodo){

    $dados_vendas = DB::table('dashboard_vendas_modalidade')
    ->leftJoin('periodo_dash', 'dashboard_vendas_modalidade.COD_PERIODO', 'periodo_dash.CODIGO')
    ->leftJoin('modalidade', 'dashboard_vendas_modalidade.COD_MODALIDADE', 'modalidade.CODIGO')
    ->where('cod_cliente', '=', session('codigologin'))
    ->where('COD_PERIODO', '=', $codigo_periodo)
    ->where('dashboard_vendas_modalidade.QUANTIDADE', '>', 0)
    ->get();

    $total = [
      'total_qtde' => null,
      'total_bruto' => null,
      'total_taxa' => null,
      'total_liquido' => null,
      'total_ticket' => null
    ];

    foreach ($dados_vendas as $vendas) {
      $total['total_qtde'] += $vendas->QUANTIDADE;
      $total['total_bruto'] += $vendas->TOTAL_BRUTO;
      $total['total_taxa'] += $vendas->TOTAL_TAXA;
      $total['total_liquido'] += $vendas->TOTAL_LIQUIDO;
      $total['total_ticket'] += $vendas->TICKET_MEDIO;
    }

    $pdf = \PDF::loadView('analytics.table-vendas-modalidade', compact('dados_vendas', 'total'));
    return $pdf->setPaper('A4', 'landscape')
    ->download('dados-vendas-por-modalidade.pdf');
  }

  public function exportarPdfVendasProduto($codigo_periodo){

    $dados_vendas = DB::table('dashboard_vendas_produtos')
    ->leftJoin('periodo_dash', 'dashboard_vendas_produtos.COD_PERIODO', 'periodo_dash.CODIGO')
    ->join('produto_web', 'dashboard_vendas_produtos.COD_PRODUTO', 'produto_web.CODIGO')
    ->where('cod_cliente', '=', session('codigologin'))
    ->where('COD_PERIODO', '=', $codigo_periodo)
    ->where('dashboard_vendas_produtos.QUANTIDADE', '>', 0)
    ->get();

    $total = [
      'total_qtde' => null,
      'total_bruto' => null,
      'total_taxa' => null,
      'total_liquido' => null,
      'total_ticket' => null
    ];

    foreach ($dados_vendas as $vendas) {
      $total['total_qtde'] += $vendas->QUANTIDADE;
      $total['total_bruto'] += $vendas->TOTAL_BRUTO;
      $total['total_taxa'] += $vendas->TOTAL_TAXA;
      $total['total_liquido'] += $vendas->TOTAL_LIQUIDO;
      $total['total_ticket'] += $vendas->TICKET_MEDIO;
    }

    $pdf = \PDF::loadView('analytics.table-vendas-produto', compact('dados_vendas', 'total'));
    return $pdf->setPaper('A4', 'landscape')
    ->download('dados-vendas-por-produto.pdf');
  }

  public function detalheCalendario($data){

    $bancos = DB::table('pagamentos_operadoras')
    ->leftJoin('lista_bancos', 'pagamentos_operadoras.COD_BANCO', 'lista_bancos.CODIGO')
    ->select('pagamentos_operadoras.*', 'pagamentos_operadoras.DATA_PAGAMENTO', 'lista_bancos.IMAGEM_LINK as IMAGEM')
    ->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
    ->selectRaw('sum(VALOR_BRUTO) as val_bruto')
    ->selectRaw('sum(VALOR_TAXA) as val_taxa')
    ->where('pagamentos_operadoras.DATA_PAGAMENTO', $data)
    ->where('pagamentos_operadoras.COD_CLIENTE', '=', session('codigologin'))
    ->groupBy('pagamentos_operadoras.COD_BANCO')
    ->get();

    $operadoras = DB::table('pagamentos_operadoras')
    ->leftJoin('adquirentes', 'pagamentos_operadoras.COD_ADQUIRENTE', 'adquirentes.CODIGO')
    ->select('pagamentos_operadoras.*', 'pagamentos_operadoras.DATA_PAGAMENTO', 'adquirentes.IMAGEM as IMAGEMAD')
    ->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
    ->selectRaw('sum(VALOR_BRUTO) as val_bruto')
    ->selectRaw('sum(VALOR_TAXA) as val_taxa')
    ->where('pagamentos_operadoras.DATA_PAGAMENTO', $data)
    ->where('pagamentos_operadoras.COD_CLIENTE', '=', session('codigologin'))
    ->groupBy('pagamentos_operadoras.COD_ADQUIRENTE')
    ->get();

    return json_encode([$bancos, $operadoras]);
  }

  public function detalheCalendarioPrevisaoPagamento($data){
    $bancos = DB::table('vendas')
    ->leftJoin('lista_bancos', 'vendas.BANCO', 'lista_bancos.CODIGO')
    ->select('vendas.CODIGO', 'vendas.DATA_PREVISTA_PAGTO', 'lista_bancos.IMAGEM_LINK as IMAGEM', 'vendas.CONTA', 'vendas.AGENCIA')
    ->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
    ->selectRaw('sum(VALOR_BRUTO) as val_bruto')
    ->selectRaw('sum(VALOR_TAXA) as val_taxa')
    ->where('vendas.DATA_PREVISTA_PAGTO', $data)
    ->where('vendas.COD_CLIENTE', '=', session('codigologin'))
    ->groupBy('vendas.BANCO')
    ->get();

    $operadoras = DB::table('vendas')
    ->leftJoin('adquirentes', 'vendas.ADQID', 'adquirentes.CODIGO')
    ->select('vendas.CODIGO','vendas.DATA_PREVISTA_PAGTO', 'adquirentes.IMAGEM as IMAGEMAD', 'vendas.CONTA', 'vendas.AGENCIA')
    ->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
    ->selectRaw('sum(VALOR_TAXA) as val_taxa')
    ->selectRaw('sum(VALOR_BRUTO) as val_bruto')
    ->where('vendas.DATA_PREVISTA_PAGTO', $data)
    ->where('vendas.COD_CLIENTE', '=', session('codigologin'))
    ->groupBy('vendas.ADQID')
    ->get();

    return json_encode([$bancos, $operadoras]);
  }

  public function enviaEmail(){
    $departamento_chamado = Request::input('departamento');
    $categoria_chamado = Request::input('categoria');
    $mensagem = Request::input('mensagem');

    $data = ['mensagem' => $mensagem];

    Mail::send('emails.chamado', $data, function ($message) {
      $assunto = "Chamado " . session('nome_fantasia') . " | " . Request::input('categoria');
      $message->from('chamados@conciflex.com.br');
      $message->subject($assunto);
      $message->to(Request::input('departamento'));
    });

    return response()->json(200);
  }
}
