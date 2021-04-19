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

		$this->totalsQuery = PagamentoOperadoraModel::select([
			'pagamentos_operadoras.DATA_PAGAMENTO',
		])
			->leftJoin('adquirentes', 'adquirentes.CODIGO', 'pagamentos_operadoras.COD_ADQUIRENTE')
			->leftJoin('tipo_pagamento', 'tipo_pagamento.CODIGO', 'pagamentos_operadoras.COD_TIPO_PAGAMENTO')
			->leftJoin('lista_bancos', 'lista_bancos.CODIGO', 'pagamentos_operadoras.COD_BANCO')
			->where(
				[
					['pagamentos_operadoras.COD_CLIENTE', $filters['cliente_id']],
					['tipo_pagamento.CODIGO', '!=', 3]
				]
			);

		$this->query = PagamentoOperadoraModel::select([
			'pagamentos_operadoras.CODIGO as ID',
			'pagamentos_operadoras.DATA_PAGAMENTO',
			'pagamentos_operadoras.EMPRESA as NOME_EMPRESA',
			'pagamentos_operadoras.ID_LOJA as ESTABELECIMENTO',
			'pagamentos_operadoras.CONTA',
			'pagamentos_operadoras.AGENCIA',
			'modalidade.DESCRICAO as MODALIDADE',
			'lista_bancos.BANCO',
			'bandeira.BANDEIRA',
			'adquirentes.ADQUIRENTE',
			DB::raw('SUM(pagamentos_operadoras.VALOR_LIQUIDO) as VALOR'),
		])
			->leftJoin('adquirentes', 'adquirentes.CODIGO', 'pagamentos_operadoras.COD_ADQUIRENTE')
			->leftJoin('tipo_pagamento', 'tipo_pagamento.CODIGO', 'pagamentos_operadoras.COD_TIPO_PAGAMENTO')
			->leftJoin('lista_bancos', 'lista_bancos.CODIGO', 'pagamentos_operadoras.COD_BANCO')
			->leftJoin('modalidade', 'modalidade.CODIGO', 'pagamentos_operadoras.COD_FORMA_PAGAMENTO')
			->leftJoin('bandeira', 'bandeira.CODIGO', 'pagamentos_operadoras.COD_BANDEIRA')
			->where(
				[
					['pagamentos_operadoras.COD_CLIENTE',  $filters['cliente_id']],
					['tipo_pagamento.CODIGO', '!=', 3]
				]
			)
			->groupBy('pagamentos_operadoras.DATA_PAGAMENTO', 'pagamentos_operadoras.AGENCIA', 'pagamentos_operadoras.CONTA', 'lista_bancos.BANCO', 'adquirentes.ADQUIRENTE', 'pagamentos_operadoras.EMPRESA', 'bandeira.BANDEIRA', 'modalidade.descricao', 'pagamentos_operadoras.ID_LOJA');

		if (Arr::has($filters, 'data_pagamento')) {
			$this->query->where('pagamentos_operadoras.DATA_PAGAMENTO', $filters['data_pagamento']);
			$this->totalsQuery->where('pagamentos_operadoras.DATA_PAGAMENTO', $filters['data_pagamento']);
		}
		if (Arr::has($filters, 'conta')) {
			$this->query->where('pagamentos_operadoras.CONTA', $filters['conta']);
			$this->totalsQuery->where('pagamentos_operadoras.CONTA', $filters['conta']);
		}
		if (Arr::has($filters, 'agencia')) {
			$this->query->where('pagamentos_operadoras.AGENCIA', $filters['agencia']);
			$this->totalsQuery->where('pagamentos_operadoras.AGENCIA', $filters['agencia']);
		}
		if (Arr::has($filters, 'adquirente')) {
			$this->query->where('adquirentes.ADQUIRENTE', $filters['adquirente']);
			$this->totalsQuery->where('adquirentes.ADQUIRENTE', $filters['adquirente']);
		}
		if (Arr::has($filters, 'banco')) {
			$this->query->where('lista_bancos.BANCO', $filters['banco']);
			$this->totalsQuery->where('lista_bancos.BANCO', $filters['banco']);
		}

		$this->buildOrderClause($sort);

		return $this;
	}

	public function getQuery()
	{
		return [$this->query, $this->totalsQuery];
	}
}
