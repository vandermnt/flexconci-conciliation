<?php

namespace App\Http\Controllers;

use Request;
use DB;
use Mail;
use App\DashboardVendasModel;
use App\ClienteModel;
use App\Http\Controllers\DOMPDF;


class DashboardController extends Controller
{

	public function dashboard()
	{
		$data_atual = date('Y/m/d');

		$clientes = ClienteModel::orderBy('NOME', 'asc')->get();
		session()->put('clientes', $clientes);

		// $pagamento_normal = DB::table('pagamentos_operadoras')
		// 	->select('pagamentos_operadoras.*')
		// 	->selectRaw('sum(VALOR_BRUTO) as tipo_pgto_normal')
		// 	->where('pagamentos_operadoras.COD_TIPO_PAGAMENTO', 1)
		// 	->where('pagamentos_operadoras.DATA_PAGAMENTO', $data_atual)
		// 	->where('pagamentos_operadoras.COD_CLIENTE', '=', session('codigologin'));
		//
		// $pagamento_normal_operadora = $pagamento_normal->groupBy('pagamentos_operadoras.COD_ADQUIRENTE')->get();
		// $pagamento_normal_banco = $pagamento_normal->groupBy('pagamentos_operadoras.COD_BANCO')->get();
		//
		// $pagamento_antecipado = DB::table('pagamentos_operadoras')
		// 	->select('pagamentos_operadoras.*')
		// 	->selectRaw('sum(VALOR_BRUTO) as tipo_pgto_antecipado')
		// 	->where('pagamentos_operadoras.COD_TIPO_PAGAMENTO', 2)
		// 	->where('pagamentos_operadoras.DATA_PAGAMENTO', $data_atual)
		// 	->where('pagamentos_operadoras.COD_CLIENTE', '=', session('codigologin'));
		//
		// $pagamento_antecipado_operadora = $pagamento_antecipado->groupBy('pagamentos_operadoras.COD_ADQUIRENTE')->get();
		// $pagamento_antecipado_banco = $pagamento_antecipado->groupBy('pagamentos_operadoras.COD_BANCO')->get();

		$dados_dash_vendas = DB::table('dashboard_vendas_adquirentes')
			->leftJoin('periodo_dash', 'dashboard_vendas_adquirentes.COD_PERIODO', 'periodo_dash.CODIGO')
			->leftJoin('adquirentes', 'dashboard_vendas_adquirentes.COD_ADQUIRENTE', 'adquirentes.CODIGO')
			->where('cod_cliente', '=', session('codigologin'))->get();

		$dados_dash_vendas_bandeira = DB::table('dashboard_vendas_bandeiras')
			->join('periodo_dash', 'dashboard_vendas_bandeiras.COD_PERIODO', 'periodo_dash.CODIGO')
			->join('bandeira', 'dashboard_vendas_bandeiras.COD_BANDEIRA', 'bandeira.CODIGO')
			->where('cod_cliente', '=', session('codigologin'))->get();

		$dados_dash_vendas_modalidade = DB::table('dashboard_vendas_modalidade')
			->join('periodo_dash', 'dashboard_vendas_modalidade.COD_PERIODO', 'periodo_dash.CODIGO')
			->join('modalidade', 'dashboard_vendas_modalidade.COD_MODALIDADE', 'modalidade.CODIGO')
			->where('cod_cliente', '=', session('codigologin'))
			->get();

		$dados_dash_vendas_produto = DB::table('dashboard_vendas_produtos')
			->join('periodo_dash', 'dashboard_vendas_produtos.COD_PERIODO', 'periodo_dash.CODIGO')
			->join('produto_web', 'dashboard_vendas_produtos.COD_PRODUTO', 'produto_web.CODIGO')
			->where('cod_cliente', '=', session('codigologin'))
			->get();

		$dados_calendario_previsao = DB::table('vendas')
			->select('vendas.DATA_PREVISTA_PAGTO')    // dd($pagamento_antecipado_operadora);
			->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
			->where('vendas.DATA_PREVISTA_PAGTO', '>', $data_atual)
			->where('cod_cliente', '=', session('codigologin'))
			->groupBy('vendas.DATA_PREVISTA_PAGTO')
			->get();

		$total_mes = DB::table('vendas')
			->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
			->where('vendas.DATA_PREVISTA_PAGTO', '=', $data_atual)
			->where('vendas.COD_CLIENTE', '=', session('codigologin'))
			->first();

		$total_futuro = DB::table('vendas')
			->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
			->where('vendas.DATA_PREVISTA_PAGTO', '>', $data_atual)
			->where('vendas.COD_CLIENTE', '=', session('codigologin'))
			->first();

		$departamento_chamado = DB::table('departamento_chamado')->orderBy('DEPARTAMENTO_CHAMADO')->get();
		$categoria_chamado = DB::table('categoria_chamado')->orderBy('CATEGORIA_CHAMADO')->get();
		session()->put('departamento_chamado', $departamento_chamado);
		session()->put('categoria_chamado', $categoria_chamado);

		$dados_bancos = DB::table('vendas')
			->leftJoin('lista_bancos', 'vendas.BANCO', 'lista_bancos.CODIGO')
			->leftJoin('adquirentes', 'vendas.ADQID', 'adquirentes.CODIGO')
			->select('vendas.*', 'vendas.DATA_PAGAMENTO', 'lista_bancos.IMAGEM_LINK as IMAGEM', 'adquirentes.IMAGEM as IMAGEMAD', 'lista_bancos.NOME_WEB as BANCO_NOME', 'adquirentes.ADQUIRENTE as NOME_AD')
			->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
			->selectRaw('sum(VALOR_BRUTO) as val_bruto')
			->selectRaw('sum(VALOR_TAXA) as val_taxa')
			->where('vendas.DATA_PREVISTA_PAGTO', '=', $data_atual)
			->where('vendas.COD_CLIENTE', '=', session('codigologin'));

		$dados_operadora = $dados_bancos->groupBy('vendas.ADQID')
			->get();

		$dados_bancos = $dados_bancos->groupBy('vendas.BANCO')
			->get();

		$total_banco = 0;
		foreach ($dados_bancos as $bancos) {
			$total_banco += $bancos->val_liquido;
		}

		$data = date('Y-m-d');



		$dados_cliente = ClienteModel::where('CODIGO', '=', session('codigologin'))->first();
		session()->put('nome_fantasia', $dados_cliente->NOME_FANTASIA);
		session()->put('periodo', 2);
		session()->put('grupo', 1);

		$erp_cliente = DB::table('erp')->where('CODIGO', $dados_cliente->COD_ERP)->first();
		session()->put('erp_cliente', $erp_cliente->ERP);

		return view('analytics.analytics-index')
			->with('dados_bancos', $dados_bancos)
			->with('dados_operadora', $dados_operadora)
			->with('total_mes', $total_mes)
			->with('total_futuro', $total_futuro)
			->with('total_banco', $total_banco)
			->with('dados_dash_vendas_bandeira', $dados_dash_vendas_bandeira)
			->with('dados_dash_vendas_modalidade', $dados_dash_vendas_modalidade)
			->with('dados_dash_vendas', $dados_dash_vendas)
			->with('dados_dash_vendas_produto', $dados_dash_vendas_produto)
			->with('departamento_chamado', $departamento_chamado)
			->with('dados_cliente', $dados_cliente)
			// ->with('pgto_normal_operadora', $pagamento_normal_operadora)
			// ->with('pgto_normal_banco', $pagamento_normal_banco)
			// ->with('pgto_antecipado_operadora', $pagamento_antecipado_operadora)
			// ->with('pgto_antecipado_banco', $pagamento_antecipado_banco)
			->with('data', $data)
			->with('dados_calendario_previsao', $dados_calendario_previsao);
	}

	public function dadosCalendario() {
		$data_atual = date('Y/m/d');
		$um_mes_atras = date('Y/m/d', strtotime('-2 month', strtotime($data_atual)));
		$data_formatada_calendario = date("Y-m-d", strtotime($um_mes_atras));

		$dados_calendario_previsao = DB::table('vendas')
			->select('vendas.DATA_PREVISTA_PAGTO')
			->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
			->where('vendas.DATA_PREVISTA_PAGTO', '>=', date('Y/m/d'))
			->where('cod_cliente', '=', session('codigologin'))
			->groupBy('vendas.DATA_PREVISTA_PAGTO')
			->get();

		$dados_calendario_pagamento = DB::table('pagamentos_operadoras')
			->select('pagamentos_operadoras.*', 'pagamentos_operadoras.DATA_PAGAMENTO')
			->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
			->whereIn('COD_TIPO_PAGAMENTO', [1, 2])
			->where('pagamentos_operadoras.DATA_PAGAMENTO', '>=', $um_mes_atras)
			->where('pagamentos_operadoras.COD_CLIENTE', '=', session('codigologin'))
			->groupBy('pagamentos_operadoras.DATA_PAGAMENTO')
			->get();

		return response()->json(['previstos' => $dados_calendario_previsao, 'pagos' => $dados_calendario_pagamento, 'um_mes_atras' => $data_formatada_calendario]);
	}

	public function exportarPdfVendasOperadoras($codigo_periodo)
	{

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
			$total['total_qtde'] += $vendas->QUANTIDADE_REAL;
			$total['total_bruto'] += $vendas->TOTAL_BRUTO;
			$total['total_taxa'] += $vendas->TOTAL_TAXA;
			$total['total_liquido'] += $vendas->TOTAL_LIQUIDO;
			$total['total_ticket'] += $vendas->TICKET_MEDIO;
		}

		$pdf = \PDF::loadView('analytics.table-vendas-operadora', compact('dados_vendas', 'total'));
		return $pdf->setPaper('A4', 'landscape')
			->download('dados-vendas-por-operadora.pdf');
	}

	public function exportarPdfVendasBandeiras($codigo_periodo)
	{

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
			$total['total_qtde'] += $vendas->QUANTIDADE_REAL;
			$total['total_bruto'] += $vendas->TOTAL_BRUTO;
			$total['total_taxa'] += $vendas->TOTAL_TAXA;
			$total['total_liquido'] += $vendas->TOTAL_LIQUIDO;
			$total['total_ticket'] += $vendas->TOTAL_BRUTO / $vendas->QUANTIDADE_REAL;
		}

		$pdf = \PDF::loadView('analytics.table-vendas-bandeira', compact('dados_vendas', 'total'));
		return $pdf->setPaper('A4', 'landscape')
			->download('dados-vendas-por-bandeira.pdf');
	}

	public function exportarPdfVendasModalidade($codigo_periodo)
	{

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
			$total['total_qtde'] += $vendas->QUANTIDADE_REAL;
			$total['total_bruto'] += $vendas->TOTAL_BRUTO;
			$total['total_taxa'] += $vendas->TOTAL_TAXA;
			$total['total_liquido'] += $vendas->TOTAL_LIQUIDO;
			$total['total_ticket'] += $vendas->TICKET_MEDIO;
		}

		$pdf = \PDF::loadView('analytics.table-vendas-modalidade', compact('dados_vendas', 'total'));
		return $pdf->setPaper('A4', 'landscape')
			->download('dados-vendas-por-modalidade.pdf');
	}

	public function exportarPdfVendasProduto($codigo_periodo)
	{

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
			$total['total_qtde'] += $vendas->QUANTIDADE_REAL;
			$total['total_bruto'] += $vendas->TOTAL_BRUTO;
			$total['total_taxa'] += $vendas->TOTAL_TAXA;
			$total['total_liquido'] += $vendas->TOTAL_LIQUIDO;
			$total['total_ticket'] += $vendas->TICKET_MEDIO;
		}

		$pdf = \PDF::loadView('analytics.table-vendas-produto', compact('dados_vendas', 'total'));
		return $pdf->setPaper('A4', 'landscape')
			->download('dados-vendas-por-produto.pdf');
	}

	public function detalheCalendario($data)
	{

		$bancos = DB::table('pagamentos_operadoras')
			->leftJoin('lista_bancos', 'pagamentos_operadoras.COD_BANCO', 'lista_bancos.CODIGO')
			->select('pagamentos_operadoras.*', 'pagamentos_operadoras.DATA_PAGAMENTO', 'lista_bancos.IMAGEM_LINK as IMAGEM', 'lista_bancos.NOME_WEB as BANCO_NOME')
			->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
			->selectRaw('sum(VALOR_BRUTO) as val_bruto')
			->selectRaw('sum(VALOR_TAXA) as val_taxa')
			->where('pagamentos_operadoras.DATA_PAGAMENTO', $data)
			->where('pagamentos_operadoras.COD_CLIENTE', '=', session('codigologin'))
			->groupBy('pagamentos_operadoras.COD_BANCO')
			->groupBy('pagamentos_operadoras.CONTA')
			->groupBy('pagamentos_operadoras.AGENCIA')
			->get();

		$operadoras = DB::table('pagamentos_operadoras')
			->leftJoin('adquirentes', 'pagamentos_operadoras.COD_ADQUIRENTE', 'adquirentes.CODIGO')
			->select('pagamentos_operadoras.*', 'pagamentos_operadoras.DATA_PAGAMENTO', 'adquirentes.IMAGEM as IMAGEMAD', 'adquirentes.ADQUIRENTE as NOME_AD')
			->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
			->selectRaw('sum(VALOR_BRUTO) as val_bruto')
			->selectRaw('sum(VALOR_TAXA) as val_taxa')
			->where('pagamentos_operadoras.DATA_PAGAMENTO', $data)
			->where('pagamentos_operadoras.COD_CLIENTE', '=', session('codigologin'))
			->groupBy('pagamentos_operadoras.COD_ADQUIRENTE')
			->get();


		$pagamento_normal = DB::table('pagamentos_operadoras')
			->select('pagamentos_operadoras.*')
			->selectRaw('sum(VALOR_BRUTO) as tipo_pgto_normal')
			->where('pagamentos_operadoras.COD_TIPO_PAGAMENTO', 1)
			->where('pagamentos_operadoras.DATA_PAGAMENTO', $data)
			->where('pagamentos_operadoras.COD_CLIENTE', '=', session('codigologin'));

		$pagamento_normal_operadora = $pagamento_normal->groupBy('pagamentos_operadoras.COD_ADQUIRENTE')->get();
		$pagamento_normal_banco = $pagamento_normal->groupBy('pagamentos_operadoras.COD_BANCO')->get();

		$pagamento_antecipado = DB::table('pagamentos_operadoras')
			->select('pagamentos_operadoras.*')
			->selectRaw('sum(VALOR_BRUTO) as tipo_pgto_antecipado')
			->where('pagamentos_operadoras.COD_TIPO_PAGAMENTO', 2)
			->where('pagamentos_operadoras.DATA_PAGAMENTO', $data)
			->where('pagamentos_operadoras.COD_CLIENTE', '=', session('codigologin'));

		$pagamento_antecipado_operadora = $pagamento_antecipado->groupBy('pagamentos_operadoras.COD_ADQUIRENTE')->get();
		$pagamento_antecipado_banco = $pagamento_antecipado->groupBy('pagamentos_operadoras.COD_BANCO')->get();

		return json_encode([
			$bancos,
			$operadoras,
			$pagamento_normal_operadora,
			$pagamento_antecipado_operadora,
			$pagamento_normal_banco,
			$pagamento_antecipado_banco
		]);
	}

	public function detalheCalendarioPrevisaoPagamento($data)
	{
		$bancos = DB::table('vendas')
			->leftJoin('lista_bancos', 'vendas.BANCO', 'lista_bancos.CODIGO')
			->select('vendas.CODIGO', 'vendas.DATA_PREVISTA_PAGTO', 'lista_bancos.IMAGEM_LINK as IMAGEM', 'lista_bancos.NOME_WEB as BANCO_NOME', 'vendas.CONTA', 'vendas.AGENCIA')
			->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
			->selectRaw('sum(VALOR_BRUTO) as val_bruto')
			->selectRaw('sum(VALOR_TAXA) as val_taxa')
			->where('vendas.DATA_PREVISTA_PAGTO', $data)
			->where('vendas.COD_CLIENTE', '=', session('codigologin'))
			->groupBy('vendas.BANCO')
			->groupBy('pagamentos_operadoras.CONTA')
			->groupBy('pagamentos_operadoras.AGENCIA')
			->get();

		$operadoras = DB::table('vendas')
			->leftJoin('adquirentes', 'vendas.ADQID', 'adquirentes.CODIGO')
			->select('vendas.CODIGO', 'vendas.DATA_PREVISTA_PAGTO', 'adquirentes.IMAGEM as IMAGEMAD', 'adquirentes.ADQUIRENTE as NOMEAD', 'vendas.CONTA', 'vendas.AGENCIA')
			->selectRaw('sum(VALOR_LIQUIDO) as val_liquido')
			->selectRaw('sum(VALOR_TAXA) as val_taxa')
			->selectRaw('sum(VALOR_BRUTO) as val_bruto')
			->where('vendas.DATA_PREVISTA_PAGTO', $data)
			->where('vendas.COD_CLIENTE', '=', session('codigologin'))
			->groupBy('vendas.ADQID')
			->get();

		return json_encode([
			$bancos,
			$operadoras,
			null,
			null,
			null,
			null
		]);
	}

	public function enviaEmail()
	{
		$departamento_chamado = Request::input('departamento');
		$categoria_chamado = Request::input('categoria');
		$mensagem = Request::input('mensagem');

		$cod_erp = ClienteModel::find(session('codigologin'));
		$nome_erp = DB::table('erp')->where('CODIGO', $cod_erp->COD_ERP)->first();

		$data = ['mensagem' => $mensagem, 'cod_erp' => $nome_erp->ERP];

		Mail::send('emails.chamado', $data, function ($message) {
			$assunto = "Chamado " . session('nome_fantasia') . " | " . Request::input('categoria');
			$message->from('chamados@conciflex.com.br');
			$message->subject($assunto);
			$message->to(Request::input('departamento'));
		});

		return response()->json(200);
	}
}
