<?php

namespace App\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\VendasModel;
use App\GruposClientesModel;
use App\Filters\BaseFilter;
use App\PagamentoOperadoraModel;

class PagamentosOperadorasFilter extends BaseFilter
{
	protected $query = null;
	protected $totalsQuery = null;
	protected $whiteList = [
		'id',
		'cliente_id',
		'data_inicial',
		'data_final',
		'grupos_clientes',
		'adquirentes',
		'bandeiras',
		'modalidades',
		'meios_captura',
		'status_conciliacao',
		'status_financeiro',
		'estabelecimentos'
	];

	public static function filter($params)
	{
		$pagamentosOperadorasFilter = app(PagamentosOperadorasFilter::class);
		return $pagamentosOperadorasFilter->apply($params);
	}

	public function apply($params)
	{
		$params = Arr::only($params, $this->getAllowedKeys());
		$params = Arr::where($params, function ($value, $key) {
			return boolval($value);
		});
		$filters = Arr::except($params, 'sort');
		$sort = collect(Arr::only($params, 'sort'))->get('sort');

		$this->totalsQuery = PagamentoOperadoraModel::select([
			'pagamentos_operadoras.DATA_PAGAMENTO',
			'pagamentos_operadoras.EMPRESA',
			'pagamentos_operadoras.VALOR_LIQUIDO',
			'adquirentes.ADQUIRENTE',
		])
			->leftJoin('tipo_pagamento', 'tipo_pagamento.CODIGO', 'pagamentos_operadoras.COD_TIPO_PAGAMENTO')
			->leftJoin('adquirentes', 'adquirentes.CODIGO', 'pagamentos_operadoras.COD_ADQUIRENTE')
			->where(
				[
					['pagamentos_operadoras.COD_CLIENTE', $filters['cliente_id']],
					['tipo_pagamento.CODIGO', '!=', 3]
				]
			);

		$this->query = PagamentoOperadoraModel::select([
			'pagamentos_operadoras.CODIGO as ID',
			'pagamentos_operadoras.DATA_PAGAMENTO',
			'lista_bancos.BANCO',
			'lista_bancos.IMAGEM_LINK as BANCO_IMAGEM',
			'pagamentos_operadoras.AGENCIA',
			'pagamentos_operadoras.CONTA',
			'adquirentes.ADQUIRENTE',
			'adquirentes.IMAGEM as ADQUIRENTE_IMAGEM',
			DB::raw('SUM(pagamentos_operadoras.VALOR_LIQUIDO) as VALOR_PREVISTO_OPERADORA'),
		])
			->leftJoin('adquirentes', 'adquirentes.CODIGO', 'pagamentos_operadoras.COD_ADQUIRENTE')
			->leftJoin('tipo_pagamento', 'tipo_pagamento.CODIGO', 'pagamentos_operadoras.COD_TIPO_PAGAMENTO')
			->leftJoin('lista_bancos', 'lista_bancos.CODIGO', 'pagamentos_operadoras.COD_BANCO')
			->where(
				'pagamentos_operadoras.COD_CLIENTE',
				$filters['cliente_id']
			)
			->where(function ($query) {
				$query->where('tipo_pagamento.CODIGO', '!=', 3)
					->orWhereNull('tipo_pagamento.CODIGO');
			})->groupBy('pagamentos_operadoras.DATA_PAGAMENTO', 'pagamentos_operadoras.AGENCIA', 'pagamentos_operadoras.CONTA', 'lista_bancos.BANCO', 'adquirentes.ADQUIRENTE');

		if (Arr::has($filters, ['data_inicial', 'data_final'])) {
			$this->query->whereBetween('pagamentos_operadoras.DATA_PAGAMENTO', [
				$filters['data_inicial'],
				$filters['data_final']
			]);
			$this->totalsQuery->whereBetween('pagamentos_operadoras.DATA_PAGAMENTO', [
				$filters['data_inicial'],
				$filters['data_final']
			]);
		}
		if (Arr::has($filters, 'adquirentes')) {
			$this->query->whereIn('pagamentos_operadoras.COD_ADQUIRENTE', $filters['adquirentes']);
			$this->totalsQuery->whereIn('pagamentos_operadoras.COD_ADQUIRENTE', $filters['adquirentes']);
		}

		$this->buildOrderClause($sort);

		return $this;
	}

	public function getQuery()
	{
		return [$this->query, $this->totalsQuery];
	}
}
