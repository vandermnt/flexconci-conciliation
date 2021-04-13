<?php

namespace App\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\VendasModel;
use App\GruposClientesModel;
use App\Filters\BaseFilter;
use App\PagamentoOperadoraModel;

class PagamentosOperadorasComprovanteFilter extends BaseFilter
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
			'pagamentos_operadoras.EMPRESA',
			'pagamentos_operadoras.COD_ADQUIRENTE',
		])
			->where('pagamentos_operadoras.COD_CLIENTE', $filters['cliente_id']);

		$this->query = PagamentoOperadoraModel::select(
			[
				'pagamentos_operadoras.EMPRESA',
				'lista_bancos.BANCO',
				'bandeira.BANDEIRA',
				'adquirentes.ADQUIRENTE',
				'pagamentos_operadoras.COD_FORMA_PAGAMENTO as FORMA_PAGAMENTO',
				DB::raw('SUM(pagamentos_operadoras.VALOR_LIQUIDO) as VALOR_PREVISTO_OPERADORA')
			]
		)
			->leftJoin('lista_bancos', 'pagamentos_operadoras.COD_BANCO', 'lista_bancos.CODIGO')
			->leftJoin('bandeira', 'pagamentos_operadoras.COD_BANDEIRA', 'bandeira.BANDEIRA')
			->leftJoin('adquirentes', 'pagamentos_operadoras.COD_ADQUIRENTE', 'adquirentes.CODIGO')
			->where('pagamentos_operadoras.COD_CLIENTE', $filters['cliente_id'])
			->groupBy('pagamentos_operadoras.DATA_PAGAMENTO', 'pagamentos_operadoras.CONTA', 'pagamentos_operadoras.AGENCIA', 'lista_bancos.BANCO', 'adquirentes.ADQUIRENTE');


		if (Arr::has($filters, 'id')) {
			$this->query->whereIn('vendas.CODIGO', $filters['id']);
		}
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
		if (Arr::has($filters, 'bandeiras')) {
			$this->query->whereIn('bandeira.CODIGO', $filters['bandeiras']);
		}
		if (Arr::has($filters, 'modalidades')) {
			$this->query->whereIn('modalidade.CODIGO', $filters['modalidades']);
		}
		if (Arr::has($filters, 'estabelecimentos')) {
			$this->query->whereIn('vendas.ESTABELECIMENTO', $filters['estabelecimentos']);
		}
		if (Arr::has($filters, 'status_conciliacao')) {
			$this->query->whereIn('status_conciliacao.CODIGO', $filters['status_conciliacao']);
		}
		if (Arr::has($filters, 'status_financeiro')) {
			$this->query->whereIn('status_financeiro.CODIGO', $filters['status_financeiro']);
		}

		$this->buildOrderClause($sort);

		return $this;
	}

	public function getQuery()
	{
		return [$this->query, $this->totalsQuery];
	}
}
