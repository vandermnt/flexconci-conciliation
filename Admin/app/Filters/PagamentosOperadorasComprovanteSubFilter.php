<?php

namespace App\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Filters\BaseSubFilter;

class PagamentosOperadorasComprovanteSubFilter extends BaseSubFilter
{
	protected $query = null;
	protected $whiteList = [
		'NOME_EMPRESA',
		'BANDEIRA',
		'MODALIDADE',
		'ESTABELECIMENTO',
		'VALOR'
	];
	protected $numericFilters = [
		'VALOR',
	];

	public static function subfilter($filters, $subfilters)
	{
		$instance = app(PagamentosOperadorasComprovanteSubFilter::class);
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
