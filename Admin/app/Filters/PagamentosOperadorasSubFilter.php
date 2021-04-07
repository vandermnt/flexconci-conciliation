<?php

namespace App\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Filters\BaseSubFilter;

class PagamentosOperadorasSubFilter extends BaseSubFilter
{
	protected $query = null;
	protected $whiteList = [
		'DATA_PAGAMENTO',
		'ADQUIRENTE',
		'BANDEIRA',
		'VALOR_BRUTO',
		'PERCENTUAL_TAXA',
		'VALOR_TAXA',
		'VALOR_LIQUIDO',
		'VALOR_PREVISTO_OPERADORA',
		'BANCO',
		'AGENCIA',
		'CONTA',
	];
	protected $numericFilters = [
		'VALOR_BRUTO',
		'PERCENTUAL_TAXA',
		'VALOR_TAXA',
		'VALOR_LIQUIDO',
		'VALOR_PREVISTO_OPERADORA',
		'PARCELA',
		'TOTAL_PARCELAS',
	];

	public static function subfilter($filters, $subfilters)
	{
		$instance = app(PagamentosOperadorasSubFilter::class);
		return $instance->apply($filters, $subfilters);
	}

	public function apply($filters, $subfilters)
	{
		$subfilters = Arr::only($subfilters, $this->whiteList);
		$subfilters = Arr::where($subfilters, function ($value, $key) {
			return boolval($value);
		});

		$filterQuery = $this->getFilterQuery($filters);

		$this->query = DB::table('vendas_sub')
			->from(
				DB::raw('(' . $filterQuery[0]->toSql() . ') as vendas_sub')
			)
			->mergeBindings($filterQuery[0]->getQuery());

		foreach ($subfilters as $subfilter => $value) {
			$this->buildWhereClause($subfilter, $value);
		}

		$this->buildOrderClause($filters['sort']);

		return $this;
	}

	public function getQuery()
	{
		return $this->query;
	}
}
