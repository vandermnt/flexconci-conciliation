<?php

namespace App\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Filters\BaseFilter;
use App\PagamentoOperadoraModel;

class PagamentosOperadorasComprovanteFilter extends BaseFilter
{
	protected $query = null;
	protected $totalsQuery = null;
	protected $whiteList = [
		'cliente_id',
		'data_pagamento',
		'conta',
		'agencia',
		'adquirente',
		'banco',
	];

	public static function filter($params)
	{
		$pagamentosOperadorasFilter = app(PagamentosOperadorasComprovanteFilter::class);
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

		$this->query = PagamentoOperadoraModel::select(
			[
				'pagamentos_operadoras.DATA_PAGAMENTO',
				'pagamentos_operadoras.EMPRESA',
				'pagamentos_operadoras.ID_LOJA as ESTABELECIMENTO',
				'modalidade.DESCRICAO as FORMA_PAGAMENTO',
				'lista_bancos.BANCO',
				'bandeira.BANDEIRA',
				'adquirentes.ADQUIRENTE',
				DB::raw('SUM(pagamentos_operadoras.VALOR_LIQUIDO) as VALOR')
			]
		)
			->leftJoin('lista_bancos', 'pagamentos_operadoras.COD_BANCO', 'lista_bancos.CODIGO')
			->leftJoin('bandeira', 'pagamentos_operadoras.COD_BANDEIRA', 'bandeira.CODIGO')
			->leftJoin('adquirentes', 'pagamentos_operadoras.COD_ADQUIRENTE', 'adquirentes.CODIGO')
			->leftJoin('modalidade', 'pagamentos_operadoras.COD_FORMA_PAGAMENTO', 'modalidade.CODIGO')
			->where('pagamentos_operadoras.COD_CLIENTE', $filters['cliente_id'])
			->groupBy('pagamentos_operadoras.DATA_PAGAMENTO', 'pagamentos_operadoras.CONTA', 'pagamentos_operadoras.AGENCIA', 'lista_bancos.BANCO', 'adquirentes.ADQUIRENTE', 'pagamentos_operadoras.EMPRESA');

		if (Arr::has($filters, 'data_pagamento')) {
			$this->query->where('pagamentos_operadoras.DATA_PAGAMENTO', $filters['data_pagamento']);
		}
		if (Arr::has($filters, 'conta')) {
			$this->query->where('pagamentos_operadoras.CONTA', $filters['conta']);
		}
		if (Arr::has($filters, 'agencia')) {
			$this->query->where('pagamentos_operadoras.AGENCIA', $filters['agencia']);
		}
		if (Arr::has($filters, 'adquirente')) {
			$this->query->where('adquirentes.ADQUIRENTE', $filters['adquirente']);
		}
		if (Arr::has($filters, 'banco')) {
			$this->query->where('lista_bancos.BANCO', $filters['banco']);
		}

		$this->buildOrderClause($sort);

		return $this;
	}

	public function getQuery()
	{
		return $this->query;
	}
}
