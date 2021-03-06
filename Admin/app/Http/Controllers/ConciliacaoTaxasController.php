<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VendasModel;
use App\StatusConciliacaoModel;
use App\StatusFinanceiroModel;
use App\GruposClientesModel;
use App\ClienteOperadoraModel;

class ConciliacaoTaxasController extends Controller
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

		return view('conciliacao.conciliacao-taxas')
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
