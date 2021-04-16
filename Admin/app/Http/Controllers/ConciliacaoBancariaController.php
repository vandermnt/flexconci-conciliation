<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Filters\VendasFilter;
use App\Filters\PagamentosOperadorasFilter;
use App\Filters\PagamentosOperadorasComprovanteFilter;
use App\Filters\PagamentosOperadorasComprovanteSubFilter;
use App\VendasModel;
use App\JustificativaModel;
use App\StatusConciliacaoModel;
use App\StatusFinanceiroModel;
use App\GruposClientesModel;
use App\ClienteOperadoraModel;
use App\Exports\CSV\RetornoVendasOperadorasExport;
use App\Exports\VendasOperadorasExport;
use App\Filters\PagamentosOperadorasSubFilter;

class ConciliacaoBancariaController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$empresas = $empresas = GruposClientesModel::select(['CODIGO', 'NOME_EMPRESA', 'CNPJ'])
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

		$status_conciliacao = StatusConciliacaoModel::orderBy('STATUS_CONCILIACAO')
			->get();

		$status_financeiro = StatusFinanceiroModel::orderBy('STATUS_FINANCEIRO')
			->get();

		return view('conciliacao.conciliacao-bancaria')
			->with([
				'empresas' => $empresas,
				'adquirentes' => $adquirentes,
				'bandeiras' => $bandeiras,
				'modalidades' => $modalidades,
				'estabelecimentos' => $estabelecimentos,
				'status_conciliacao' => $status_conciliacao,
				'status_financeiro' => $status_financeiro,
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
			$queries = PagamentosOperadorasFilter::filter($filters)
				->getQuery();
			$query = $queries[0];
			$totalsQuery = $queries[1];

			$sales = (clone $query)->paginate($perPage);
			$totals = [
				'TOTAL_PREVISTO_OPERADORA' => (clone $totalsQuery)->sum('pagamentos_operadoras.VALOR_LIQUIDO'),
			];

			return response()->json([
				'vendas' => $sales,
				'totais' => $totals,
			]);
		} catch (Exception $e) {
			return response()->json([
				'message' => 'Não foi possível realizar a consulta em Vendas Operadoras.',
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
			$query = PagamentosOperadorasSubFilter::subfilter($filters, $subfilters)
				->getQuery();

			$sales = (clone $query)->paginate($perPage);
			$totals = [
				// 'TOTAL_BRUTO' => (clone $query)->sum('VALOR_BRUTO'),
				'TOTAL_PREVISTO_OPERADORA' => (clone $query)->sum('VALOR_PREVISTO_OPERADORA'),
			];
			// $totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];

			return response()->json([
				'vendas' => $sales,
				'totais' => $totals,
			]);
		} catch (Exception $e) {
			return response()->json([
				'message' => 'Não foi possível realizar a consulta em Vendas Operadoras.',
			], 500);
		}
	}

	public function justify(Request $request)
	{
		$ids = $request->input('id') ?? [];
		$idJustificativa = $request->input('justificativa') ?? null;

		$justificativa = JustificativaModel::where('CODIGO', $idJustificativa)
			->where('COD_CLIENTE', session('codigologin'))
			->first();

		if (is_null($justificativa)) {
			return response()->json([
				'status' => 'erro',
				'mensagem' => 'A justificativa deve ser informada.'
			], 400);
		}

		$statusNaoConciliado = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;
		$statusJustificado = StatusConciliacaoModel::justificada()->first();

		$validIds = VendasModel::whereIn('CODIGO', $ids)
			->where('COD_CLIENTE', session('codigologin'))
			->where('COD_STATUS_CONCILIACAO', $statusNaoConciliado)
			->pluck('CODIGO')
			->toArray();

		VendasModel::whereIn('CODIGO', $validIds)
			->update([
				'JUSTIFICATIVA' => $justificativa->JUSTIFICATIVA,
				'COD_STATUS_CONCILIACAO' => $statusJustificado->CODIGO,
			]);

		$sales = VendasFilter::filter([
			'id' => $validIds,
			'cliente_id' => session('codigologin')
		])->getQuery()->get();

		$totals = [
			'TOTAL_BRUTO' => $sales->sum('VALOR_BRUTO'),
			'TOTAL_LIQUIDO' => $sales->sum('VALOR_LIQUIDO'),
		];
		$totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];

		return response()->json([
			'status' => 'sucesso',
			'mensagem' => 'As vendas foram justificadas com sucesso.',
			'vendas' => $sales,
			'totais' => $totals,
		], 200);
	}

	public function unjustify(Request $request)
	{
		$ids = $request->input('id') ?? [];

		$statusJustificado = StatusConciliacaoModel::justificada()->first()->CODIGO;
		$statusNaoConciliado = StatusConciliacaoModel::naoConciliada()->first();

		$validIds = VendasModel::whereIn('CODIGO', $ids)
			->where('COD_CLIENTE', session('codigologin'))
			->where('COD_STATUS_CONCILIACAO', $statusJustificado)
			->pluck('CODIGO')
			->toArray();

		VendasModel::whereIn('CODIGO', $validIds)
			->update([
				'JUSTIFICATIVA' => null,
				'COD_STATUS_CONCILIACAO' => $statusNaoConciliado->CODIGO,
			]);

		$sales = VendasFilter::filter([
			'id' => $validIds,
			'cliente_id' => session('codigologin'),
		])->getQuery()->get();

		$totals = [
			'TOTAL_BRUTO' => $sales->sum('VALOR_BRUTO'),
			'TOTAL_LIQUIDO' => $sales->sum('VALOR_LIQUIDO'),
		];
		$totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];

		return response()->json([
			'status' => 'sucesso',
			'mensagem' => 'As vendas foram desjustificadas com sucesso.',
			'vendas' => $sales,
			'totais' => $totals,
		], 200);
	}

	public function export(Request $request)
	{
		set_time_limit(300);

		$sort = [
			'column' => $request->input('sort_column', 'DATA_VENDA'),
			'direction' => $request->input('sort_direction', 'asc')
		];
		$filters = $request->except(['_token', 'sort_column', 'sort_direction']);
		$filters['sort'] = $sort;
		$subfilters = $request->except(['_token']);
		Arr::set($filters, 'cliente_id', session('codigologin'));
		return (new VendasOperadorasExport($filters, $subfilters))->download('vendas_operadoras_' . time() . '.xlsx');
	}

	public function exportCsv(Request $request)
	{
		set_time_limit(300);

		$sort = [
			'column' => $request->input('sort_column', 'DATA_VENDA'),
			'direction' => $request->input('sort_direction', 'asc')
		];
		$filters = $request->except(['_token', 'sort_column', 'sort_direction']);
		$filters['sort'] = $sort;
		$subfilters = $request->except(['_token']);
		Arr::set($filters, 'cliente_id', session('codigologin'));
		return (new RetornoVendasOperadorasExport($filters, $subfilters))->download('vendas_operadoras_' . time() . '.csv');
	}

	public function print(Request $request, $id)
	{
		$sale = VendasFilter::filter([
			'id' => [$id],
			'cliente_id' => session('codigologin')
		])
			->getQuery()
			->first();
		$customPaper = array(0, 0, 240.53, 210.28);

		return \PDF::loadView('vendas.comprovante-venda-operadora', compact('sale'))
			->setPaper($customPaper, 'landscape')
			->stream('comprovante_venda_' . $id . '_' . time() . '.pdf');
	}

	public function searchComprovante(Request $request)
	{
		$allowedPerPage = [5, 10, 20, 50, 100, 200];
		$perPage = $request->input('por_pagina', 10);
		$perPage = in_array($perPage, $allowedPerPage) ? $perPage : 10;
		$filters = $request->all();
		$filters['cliente_id'] = session('codigologin');

		try {
			$queries = PagamentosOperadorasComprovanteFilter::filter($filters)
				->getQuery();
			$query = $queries[0];
			$totalsQuery = $queries[1];
			$sales = (clone $query)->paginate($perPage);
			$totals = [
				'TOTAL_PREVISTO_OPERADORA' => (clone $totalsQuery)->sum('pagamentos_operadoras.VALOR_LIQUIDO'),
			];

			return response()->json([
				'vendas' => $sales,
				'totais' => $totals,
			]);
		} catch (Exception $e) {
			return response()->json([
				'message' => 'Não foi possível realizar a consulta em Vendas Operadoras.',
			], 500);
		}
	}

	public function filterComprovante(Request $request)
	{
		$allowedPerPage = [5, 10, 20, 50, 100, 200];
		$perPage = $request->input('por_pagina', 10);
		$perPage = in_array($perPage, $allowedPerPage) ? $perPage : 10;
		$filters = $request->input('filters');
		$filters['cliente_id'] = session('codigologin');
		$subfilters = $request->input('subfilters');

		try {
			$query = PagamentosOperadorasComprovanteSubFilter::subfilter($filters, $subfilters)
				->getQuery();

			$sales = (clone $query)->paginate($perPage);
			$totals = [
				'TOTAL_PREVISTO_OPERADORA' => (clone $query)->sum('VALOR')
			];

			return response()->json([
				'vendas' => $sales,
				'totais' => $totals,
			]);
		} catch (Exception $e) {
			return response()->json([
				'message' => 'Não foi possível realizar a consulta em Vendas Operadoras.',
			], 500);
		}
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
