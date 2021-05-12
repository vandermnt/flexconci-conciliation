<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\ClienteModel;
use App\JustificativaModel;
use App\VendasModel;
use App\VendasErpModel;
use App\GruposClientesModel;
use App\StatusConciliacaoModel;
use App\ClienteOperadoraModel;
use App\Filters\VendasErpFilter;
use App\Filters\VendasFilter;
use App\Filters\VendasErpSubFilter;
use App\Filters\VendasSubFilter;
use App\Exports\VendasConciliacaoExport;
use App\Exports\VendasErpConciliacaoExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class ConciliacaoVendasController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$erp = ClienteModel::select([
			'erp.ERP',
			'erp.TITULO_CAMPO_ADICIONAL1 as TITULO_CAMPO1',
			'erp.TITULO_CAMPO_ADICIONAL2 as TITULO_CAMPO2',
			'erp.TITULO_CAMPO_ADICIONAL3 as TITULO_CAMPO3'
		])
			->leftJoin('erp', 'clientes.COD_ERP', 'erp.CODIGO')
			->where('clientes.CODIGO', session('codigologin'))
			->first();

		$empresas = GruposClientesModel::where('COD_CLIENTE', session('codigologin'))
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

		$status_conciliacao = StatusConciliacaoModel::orderBy('STATUS_CONCILIACAO')
			->get();

		$justificativas = JustificativaModel::select([
			'CODIGO',
			'JUSTIFICATIVA'
		])
			->where('COD_CLIENTE', session('codigologin'))
			->get();

		return view('conciliacao.conciliacao-vendas')
			->with([
				'erp' => $erp,
				'empresas' => $empresas,
				'adquirentes' => $adquirentes,
				'status_conciliacao' => $status_conciliacao,
				'justificativas' => $justificativas,
			]);
	}

	public function searchErp(Request $request)
	{
		$per_page = $this->getPerPage(
			$request->input('por_pagina', null),
			[5, 10, 20, 50, 100, 200]
		);
		$filters = $request->except(['status_conciliacao']);
		$filters['cliente_id'] = session('codigologin');

		try {
			$status_conciliada = StatusConciliacaoModel::conciliada()->first()->CODIGO;
			$status_nao_conciliada = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;
			$status_justificada = StatusConciliacaoModel::justificada()->first()->CODIGO;
			$status_divergente = StatusConciliacaoModel::divergente()->first()->CODIGO;
			$status_manual = StatusConciliacaoModel::manual()->first()->CODIGO;

			$status_keys = [
				$status_conciliada => 'TOTAL_CONCILIADO',
				$status_nao_conciliada => 'TOTAL_NAO_CONCILIADO',
				$status_justificada => 'TOTAL_JUSTIFICADO',
				$status_divergente => 'TOTAL_DIVERGENTE',
				$status_manual => 'TOTAL_CONCILIADO_MANUAL',
			];

			$query = VendasErpFilter::filter($filters)->getQuery();
			$sales_query = (clone $query)->whereIn('vendas_erp.COD_STATUS_CONCILIACAO', $request->input('status_conciliacao'));

			$totals = [
				'TOTAL_BRUTO' => (clone $sales_query)->sum(DB::raw('coalesce(`vendas_erp`.`VALOR_VENDA_PARCELA`, `vendas_erp`.`TOTAL_VENDA`)')) ?? 0,
				'TOTAL_LIQUIDO' => (clone $sales_query)->sum('VALOR_LIQUIDO_PARCELA') ?? 0,
				'TOTAL_LIQUIDO_OPERADORA' => (clone $sales_query)->sum('VALOR_LIQUIDO_OPERADORA') ?? 0,
				'TOTAL_DIFERENCA_LIQUIDO' => (clone $sales_query)->sum('DIFERENCA_LIQUIDO') ?? 0,
				'TOTAL_TAXA' => (clone $sales_query)->sum('VALOR_TAXA') ?? 0,
			];

			foreach ($status_keys as $status => $key) {
				$totals[$key] = (clone $query)
					->selectRaw('sum(coalesce(`vendas_erp`.`VALOR_VENDA_PARCELA`, `vendas_erp`.`TOTAL_VENDA`)) as TOTAL')
					->where('vendas_erp.COD_STATUS_CONCILIACAO', $status)
					->first()
					->TOTAL ?? 0;
			}

			$sales = $sales_query->paginate($per_page);

			return response()->json([
				'vendas' => $sales,
				'totais' => $totals
			]);
		} catch (Exception $e) {
			return response()->json([
				'mensagem' => 'Não foi possível realizar a consulta em Vendas ERP.'
			], 500);
		}
	}

	public function filterErp(Request $request)
	{
		$per_page = $this->getPerPage(
			$request->input('por_pagina', null),
			[5, 10, 20, 50, 100, 200]
		);
		$filters = $request->input('filters');
		$filters['cliente_id'] = session('codigologin');
		$subfilters = $request->input('subfilters');

		try {
			$query = VendasErpSubFilter::subfilter($filters, $subfilters)->getQuery();

			$totals = [
				'TOTAL_BRUTO' => $query->sum('VALOR_VENDA'),
				'TOTAL_LIQUIDO' => $query->sum('VALOR_LIQUIDO_PARCELA'),
				'TOTAL_LIQUIDO_OPERADORA' => $query->sum('VALOR_LIQUIDO_OPERADORA') ?? 0,
				'TOTAL_DIFERENCA_LIQUIDO' => $query->sum('DIFERENCA_LIQUIDO') ?? 0,
				'TOTAL_TAXA' => $query->sum('VALOR_TAXA') ?? 0,
			];

			$sales = (clone $query)->paginate($per_page);

			return response()->json([
				'vendas' => $sales,
				'totais' => $totals,
			]);
		} catch (Exception $e) {
			return response()->json([
				'mensagem' => 'Não foi possível realizar a consulta em Vendas ERP.'
			], 500);
		}
	}

	public function searchOperadoras(Request $request)
	{
		$per_page = $this->getPerPage(
			$request->input('por_pagina', null),
			[5, 10, 20, 50, 100, 200]
		);
		$filters = $request->except(['status_conciliacao']);
		$filters['cliente_id'] = session('codigologin');

		try {
			$status_nao_conciliada = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;
			$has_status_nao_conciliada = in_array($status_nao_conciliada, $request->input('status_conciliacao'));
			$filters['status_conciliacao'] = $has_status_nao_conciliada ? [$status_nao_conciliada] : [null];

			$query = VendasFilter::filter($filters)
				->getQuery();

			$totals = [
				'TOTAL_BRUTO' => (clone $query)->sum('VALOR_BRUTO'),
				'TOTAL_LIQUIDO' => (clone $query)->sum('VALOR_LIQUIDO'),
			];
			$totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];
			$totals['TOTAL_PENDENCIAS_OPERADORAS'] = $totals['TOTAL_BRUTO'];

			$sales = $query->paginate($per_page);

			return response()->json([
				'vendas' => $sales,
				'totais' => $totals
			]);
		} catch (Exception $e) {
			return response()->json([
				'mensagem' => 'Não foi possível realizar a consulta em Vendas Operadoras.'
			], 500);
		}
	}

	public function filterOperadoras(Request $request)
	{
		$per_page = $this->getPerPage(
			$request->input('por_pagina', null),
			[5, 10, 20, 50, 100, 200]
		);
		$filters = $filters = $request->input('filters');
		$filters['cliente_id'] = session('codigologin');
		$subfilters = $request->input('subfilters');

		try {
			$status_nao_conciliada = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;
			$filters['status_conciliacao'] = [$status_nao_conciliada];

			$query = VendasSubFilter::subfilter($filters, $subfilters)
				->getQuery();

			$sales = (clone $query)->paginate($per_page);
			$totals = [
				'TOTAL_BRUTO' => $query->sum('VALOR_BRUTO'),
				'TOTAL_LIQUIDO' => $query->sum('VALOR_LIQUIDO')
			];
			$totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];

			return response()->json([
				'vendas' => $sales,
				'totais' => $totals,
			]);
		} catch (Exception $e) {
			return response()->json([
				'mensagem' => 'Não foi possível realizar a consulta em Vendas Operadoras.'
			], 500);
		}
	}

	public function conciliarManualmente(Request $request)
	{
		$idOperadora = collect($request->input('id_operadora'))->first();
		$idErp = collect($request->input('id_erp'))->first();

		$statusNaoConciliada = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;
		$statusManualmente = StatusConciliacaoModel::manual()->first();
		$now = new DateTime("now", new DateTimeZone('America/Sao_Paulo'));

		$vendaErp = VendasErpModel::where('CODIGO', $idErp)
			->where('COD_CLIENTE', session('codigologin'))
			->where('COD_STATUS_CONCILIACAO', $statusNaoConciliada)
			->first();
		$vendaOperadora = VendasModel::where('CODIGO', $idOperadora)
			->where('COD_CLIENTE', session('codigologin'))
			->where('COD_STATUS_CONCILIACAO', $statusNaoConciliada)
			->first();

		$vendaErp->COD_VENDAS_OPERADORAS = $vendaOperadora->CODIGO;
		$vendaErp->COD_STATUS_CONCILIACAO = $statusManualmente->CODIGO;
		$vendaErp->DATA_CONCILIACAO = $now->format('Y-m-d');
		$vendaErp->HORA_CONCILIACAO = $now->format('H:i:s');
		$vendaOperadora->COD_VENDA_ERP = $vendaErp->CODIGO;
		$vendaOperadora->ID_VENDAS_ERP = $vendaErp->DESCRICAO_TIPO_PRODUTO;
		$vendaOperadora->COD_STATUS_CONCILIACAO = $statusManualmente->CODIGO;
		$vendaErp->save();
		$vendaOperadora->save();

		return response()->json([
			'status' => 'sucesso',
			'mensagem' => 'As vendas foram conciliadas com sucesso.',
			'erp' => [
				'ID' => $vendaErp->CODIGO,
				'DATA_CONCILIACAO' => $vendaErp->DATA_CONCILIACAO,
				'HORA_CONCILIACAO' => $vendaErp->HORA_CONCILIACAO,
				'TOTAL_BRUTO' => $vendaErp->VALOR_VENDA_PARCELA ?? $venda_erp->TOTAL_VENDA,
			],
			'operadora' => [
				'ID' => $vendaOperadora->CODIGO,
				'DESCRICAO_ERP' => $vendaOperadora->ID_VENDAS_ERP,
				'TOTAL_BRUTO' =>  $vendaOperadora->VALOR_BRUTO,
				'TOTAL_LIQUIDO' =>  $vendaOperadora->VALOR_LIQUIDO,
				'TOTAL_TAXA' =>  $vendaOperadora->VALOR_BRUTO - $vendaOperadora->VALOR_LIQUIDO,
			],
			'STATUS_CONCILIACAO' => $statusManualmente->STATUS_CONCILIACAO,
			'STATUS_CONCILIACAO_IMAGEM' => $statusManualmente->IMAGEM_URL,
		], 200);
	}

	public function desconciliarManualmente(Request $request)
	{
		$idErp = collect($request->input('id_erp'))->first();
		$statusManualmente = StatusConciliacaoModel::manual()->first()->CODIGO;
		$statusNaoConciliada = StatusConciliacaoModel::naoConciliada()->first();

		$vendaErp = VendasErpModel::where('CODIGO', $idErp)
			->where('COD_CLIENTE', session('codigologin'))
			->where('COD_STATUS_CONCILIACAO', $statusManualmente)
			->first();
		$vendaOperadora = VendasModel::where('CODIGO', $vendaErp->COD_VENDAS_OPERADORAS)
			->first();

		$vendaErp->COD_VENDAS_OPERADORAS = null;
		$vendaErp->COD_STATUS_CONCILIACAO = $statusNaoConciliada->CODIGO;
		$vendaErp->DATA_CONCILIACAO = null;
		$vendaErp->HORA_CONCILIACAO = null;
		$vendaOperadora->COD_VENDA_ERP = null;
		$vendaOperadora->ID_VENDAS_ERP = null;
		$vendaOperadora->COD_STATUS_CONCILIACAO = $statusNaoConciliada->CODIGO;
		$vendaErp->save();
		$vendaOperadora->save();

		return response()->json([
			'status' => 'sucesso',
			'mensagem' => 'As vendas foram desconciliadas com êxito.',
			'erp' => [
				'ID' => $vendaErp->CODIGO,
				'DATA_CONCILIACAO' => $vendaErp->DATA_CONCILIACAO,
				'HORA_CONCILIACAO' => $vendaErp->HORA_CONCILIACAO,
				'TOTAL_BRUTO' => $vendaErp->VALOR_VENDA_PARCELA ?? $venda_erp->TOTAL_VENDA,
			],
			'operadora' => [
				'ID' => $vendaOperadora->CODIGO,
				'DESCRICAO_ERP' => $vendaOperadora->ID_VENDAS_ERP,
				'TOTAL_BRUTO' =>  $vendaOperadora->VALOR_BRUTO,
				'TOTAL_LIQUIDO' =>  $vendaOperadora->VALOR_LIQUIDO,
				'TOTAL_TAXA' =>  $vendaOperadora->VALOR_BRUTO - $vendaOperadora->VALOR_LIQUIDO,
			],
			'STATUS_CONCILIACAO' => $statusNaoConciliada->STATUS_CONCILIACAO,
			'STATUS_CONCILIACAO_IMAGEM' => $statusNaoConciliada->IMAGEM_URL,
		], 200);
	}

	public function exportarErp(Request $request)
	{
		set_time_limit(300);

		$headers = ClienteModel::select(
			[
				'erp.TITULO_CAMPO_ADICIONAL1 as CAMPO1',
				'erp.TITULO_CAMPO_ADICIONAL2 as CAMPO2',
				'erp.TITULO_CAMPO_ADICIONAL3 as CAMPO3'
			]
		)
			->leftJoin('erp', 'clientes.COD_ERP', 'erp.CODIGO')
			->where('clientes.CODIGO', session('codigologin'))
			->first()
			->toArray();

		$sort = [
			'column' => $request->input('sort_column', 'DATA_VENDA'),
			'direction' => $request->input('sort_direction', 'asc')
		];
		$filters = $request->except(['_token', 'sort_column', 'sort_direction']);
		$filters['sort'] = $sort;
		$subfilters = $request->except(['_token']);
		Arr::set($filters, 'cliente_id', session('codigologin'));
		$hiddenColumns = $request->input('hidden', []);
		return (new VendasErpConciliacaoExport($filters, $subfilters, $hiddenColumns, $headers))->download('vendas_erp_' . time() . '.xlsx');
	}

	public function exportarOperadoras(Request $request)
	{
		set_time_limit(300);

		$status_nao_conciliada = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;

		$sort = [
			'column' => $request->input('sort_column', 'DATA_VENDA'),
			'direction' => $request->input('sort_direction', 'asc')
		];
		$filters = $request->except(['_token', 'sort_column', 'sort_direction']);
		$filters['cliente_id'] = session('codigologin');
		$filters['sort'] = $sort;
		$subfilters = $request->except(['_token']);

		$status_conciliacao = $filters['status_conciliacao'];

		$filters['status_conciliacao'] = in_array($status_nao_conciliada, $status_conciliacao) ?
			[$status_nao_conciliada] : [null];

		$hiddenColumns = $request->input('hidden', []);
		return (new VendasConciliacaoExport($filters, $subfilters, $hiddenColumns))->download('vendas_operadoras_' . time() . '.xlsx');
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

	private function getPerPage($per_page = null, $allowed_per_page)
	{
		$per_page = $per_page ?? $allowed_per_page[0];
		$per_page = in_array($per_page, $allowed_per_page) ? $per_page : $allowed_per_page[0];

		return $per_page;
	}
}
