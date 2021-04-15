<?php

namespace App\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Filters\BaseSubFilter;

class RecebimentosSubFilter extends BaseSubFilter
{
	protected $query = null;
	protected $whiteList = [
		'ID',
		'DESCRICAO_ERP',
		'NOME_EMPRESA',
		'CNPJ',
		'DATA_VENDA',
		'DATA_PREVISAO',
		'DATA_PAGAMENTO',
		'ADQUIRENTE',
		'BANDEIRA',
		'MODALIDADE',
		'TIPO_PAGAMENTO',
		'NSU',
		'AUTORIZACAO',
		'TID',
		'CARTAO',
		'NUMERO_RESUMO_VENDA',
		'VALOR_BRUTO',
		'TAXA_PERCENTUAL',
		'VALOR_TAXA',
		'VALOR_LIQUIDO',
		'TAXA_ANTECIPACAO',
		'VALOR_TAXA_ANTECIPACAO',
		'POSSUI_TAXA_MINIMA',
		'PARCELA',
		'TOTAL_PARCELAS',
		'ESTABELECIMENTO',
		'TERMINAL',
		'NUMERO_TERMINAL',
		'BANCO',
		'AGENCIA',
		'CONTA',
		'OBSERVACOES',
		'PRODUTO',
		'MEIOCAPTURA',
		'STATUS_CONCILIACAO',
		'DIVERGENCIA',
		'JUSTIFICATIVA',
		'RETORNO_ERP_BAIXA'
	];
	protected $numericFilters = [
		'VALOR_BRUTO',
		'TAXA_PERCENTUAL',
		'VALOR_TAXA',
		'VALOR_LIQUIDO',
		'PARCELA',
		'TOTAL_PARCELAS',
		'TAXA_ANTECIPACAO',
		'VALOR_TAXA_ANTECIPACAO',
	];

	public static function subfilter($filters, $subfilters)
	{
		$instance = app(RecebimentosSubFilter::class);
		return $instance->apply($filters, $subfilters);
	}

	public function apply($filters, $subfilters)
	{
		$subfilters = Arr::only($subfilters, $this->whiteList);
		$subfilters = Arr::where($subfilters, function ($value, $key) {
			return boolval($value);
		});

		$filterQuery = $this->getFilterQuery($filters);

		$this->query = DB::table('recebimentos_sub')
			->select('*')
			->from(
				DB::raw('(' . $filterQuery->toSql() . ') as recebimentos_sub')
			)
			->mergeBindings($filterQuery);

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
