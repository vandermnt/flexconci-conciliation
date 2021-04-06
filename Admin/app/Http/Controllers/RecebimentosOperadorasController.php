<?php

namespace App\Http\Controllers;

use App\Filters\RecebimentosFilter;
use App\Filters\RecebimentosSubFilter;
use App\GruposClientesModel;
use App\ClienteOperadoraModel;
use App\DomicilioClienteModel;
use App\ClienteModel;
use App\VendasModel;
use App\StatusConciliacaoModel;
use App\Exports\RecebimentosOperadorasExport;
use App\Exports\CSV\RetornoRecebimentosOperadorasExport;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RecebimentosOperadorasController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$erp = ClienteModel::select(
			[
				'erp.ERP',
			]
		)
			->leftJoin('erp', 'clientes.COD_ERP', 'erp.CODIGO')
			->where('clientes.CODIGO', session('codigologin'))
			->first();

		$empresas = GruposClientesModel::select([
			'CODIGO',
			'NOME_EMPRESA',
			'CNPJ'
		])
			->where('COD_CLIENTE', session('codigologin'))
			->orderBy('NOME_EMPRESA')
			->get();

		$adquirentes = ClienteOperadoraModel::select([
			'adquirentes.CODIGO',
			'adquirentes.ADQUIRENTE',
			'adquirentes.IMAGEM'
		])
			->join('adquirentes', 'COD_ADQUIRENTE', 'adquirentes.CODIGO')
			->where('COD_CLIENTE', '=', session('codigologin'))
			->distinct()
			->orderBy('ADQUIRENTE')
			->get();

		$bandeiras = VendasModel::select([
			'bandeira.CODIGO',
			'bandeira.BANDEIRA',
			'bandeira.IMAGEM'
		])
			->leftJoin('bandeira', 'COD_BANDEIRA', 'bandeira.CODIGO')
			->where('COD_CLIENTE', session('codigologin'))
			->whereNotNull('bandeira.BANDEIRA')
			->distinct()
			->orderBy('BANDEIRA')
			->get();

		$modalidades = VendasModel::select([
			'modalidade.CODIGO',
			'modalidade.DESCRICAO'
		])
			->leftJoin('modalidade', 'modalidade.CODIGO', 'CODIGO_MODALIDADE')
			->where('COD_CLIENTE', session('codigologin'))
			->whereNotNull('modalidade.DESCRICAO')
			->distinct()
			->orderBy('DESCRICAO')
			->get();

		$estabelecimentos = ClienteOperadoraModel::select([
			'CODIGO_ESTABELECIMENTO as ESTABELECIMENTO',
			'adquirentes.ADQUIRENTE'
		])
			->where('COD_CLIENTE', session('codigologin'))
			->leftJoin('adquirentes', 'cliente_operadora.COD_ADQUIRENTE', 'adquirentes.CODIGO')
			->orderBy('CODIGO_ESTABELECIMENTO', 'asc')
			->get();

		return view('recebimentos.recebimentos-operadoras')->with([
			'erp' => $erp,
			'empresas' => $empresas,
			'adquirentes' => $adquirentes,
			'bandeiras' => $bandeiras,
			'modalidades' => $modalidades,
			'estabelecimentos' => $estabelecimentos,
			'domicilios_bancarios' => $domicilios_bancarios,
		]);
	}

	public function search(Request $request)
	{
		$allowedPerPage = [10, 20, 50, 100, 200];
		$perPage = $request->input('por_pagina', 10);
		$perPage = in_array($perPage, $allowedPerPage) ? $perPage : 10;
		$filters = $request->all();
		$filters['cliente_id'] = session('codigologin');

		try {
			$query = RecebimentosFilter::filter($filters)
				->getQuery()
				->orderBy('DATA_PAGAMENTO');

			$payments = (clone $query)->paginate($perPage);
			$totals = [
				'TOTAL_BRUTO' => (clone $query)->sum('pagamentos_operadoras.VALOR_BRUTO'),
				'TOTAL_LIQUIDO' => (clone $query)->sum('pagamentos_operadoras.VALOR_LIQUIDO'),
				'TOTAL_CANCELAMENTO' => 0,
				'TOTAL_CHARGEBACK' => 0,
				'PAG_AVULSO' => 0,
				'TOTAL_ANTECIPACAO' => 0,
				'TOTAL_DESPESAS' => 0

			];
			$totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];

			return response()->json([
				'recebimentos' => $payments,
				'totais' => $totals,
			]);
		} catch (Exception $e) {
			return response()->json([
				'message' => 'Não foi possível realizar a consulta em Recebimentos Operadoras.',
			], 500);
		}
	}

	public function filter(Request $request)
	{
		$allowedPerPage = [10, 20, 50, 100, 200];
		$perPage = $request->input('por_pagina', 10);
		$perPage = in_array($perPage, $allowedPerPage) ? $perPage : 10;
		$filters = $request->input('filters');
		$filters['cliente_id'] = session('codigologin');
		$subfilters = $request->input('subfilters');

		try {
			$query = RecebimentosSubFilter::subfilter($filters, $subfilters)
				->getQuery()
				->orderBy('DATA_PAGAMENTO');

			$payments = (clone $query)->paginate($perPage);
			$totals = [
				'TOTAL_BRUTO' => (clone $query)->sum('VALOR_BRUTO'),
				'TOTAL_LIQUIDO' => (clone $query)->sum('VALOR_LIQUIDO'),
				'PAG_NORMAL' => (clone $query)
					->where('COD_TIPO_PAGAMENTO', 1)
					->sum('VALOR_BRUTO'),
				'PAG_ANTECIPADO' => (clone $query)
					->where('COD_TIPO_PAGAMENTO', 2)
					->sum('VALOR_BRUTO'),
				'PAG_AVULSO' => 0,
				'TOTAL_ANTECIPACAO' => 0,
				'TOTAL_DESPESAS' => 0
			];
			$totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];

			return response()->json([
				'recebimentos' => $payments,
				'totais' => $totals,
			]);
		} catch (Exception $e) {
			return response()->json([
				'message' => 'Não foi possível realizar a consulta em Recebimentos Operadoras.',
			], 500);
		}
	}

	public function export(Request $request)
	{
		set_time_limit(300);

		$sort = [
			'column' => $request->input('sort_column', 'DATA_PAGAMENTO'),
			'direction' => $request->input('sort_direction', 'asc')
		];
		$filters = $request->except(['_token', 'sort_column', 'sort_direction']);
		$filters['sort'] = $sort;
		$subfilters = $request->except(['_token']);
		Arr::set($filters, 'cliente_id', session('codigologin'));
		return (new RecebimentosOperadorasExport($filters, $subfilters))->download('recebimentos_operadoras_' . time() . '.xlsx');
	}

	public function exportCsv(Request $request)
	{
		set_time_limit(300);

		$sort = [
			'column' => $request->input('sort_column', 'DATA_PAGAMENTO'),
			'direction' => $request->input('sort_direction', 'asc')
		];
		$filters = $request->except(['_token', 'sort_column', 'sort_direction']);
		$filters['sort'] = $sort;
		$subfilters = $request->except(['_token']);
		Arr::set($filters, 'cliente_id', session('codigologin'));
		return (new RetornoRecebimentosOperadorasExport($filters, $subfilters))->download('recebimentos_operadoras_' . time() . '.csv');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
