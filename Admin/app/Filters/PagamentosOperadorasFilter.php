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
		$vendasFilter = app(VendasFilter::class);
		return $vendasFilter->apply($params);
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
				'lista_bancos.BANCO',
				'pagamentos_operadoras.AGENCIA',
				'pagamentos_operadoras.CONTA',
				'adquirentes.ADQUIRENTE as OPERADORA',
				DB::raw('SUM(pagamentos_operadoras.VALOR_LIQUIDO)')
			]
		)
			// ->leftJoin('lista_bancos', 'lista_bancos.CODIGO', 'pagamentos_operadoras.COD_BANCO')
			// ->leftJoin('adquirentes', 'adquirentes.CODIGO', 'pagamentos_operadoras.COD_ADQUIRENTE')
			->where('vendas.COD_CLIENTE', $filters['cliente_id'])
			->get();

		if (Arr::has($filters, 'id')) {
			$this->query->whereIn('vendas.CODIGO', $filters['id']);
		}
		// if (Arr::has($filters, ['data_inicial', 'data_final'])) {
		// 	$this->query->whereBetween('vendas.DATA_VENDA', [
		// 		$filters['data_inicial'],
		// 		$filters['data_final']
		// 	]);
		// }
		if (Arr::has($filters, 'grupos_clientes')) {
			$this->query->whereIn('vendas.EMPRESA', function ($query) use ($filters) {
				$query->select('NOME_EMPRESA')
					->from('grupos_clientes')
					->whereIn('grupos_clientes.CODIGO', $filters['grupos_clientes']);
			});
		}
		if (Arr::has($filters, 'adquirentes')) {
			$this->query->whereIn('adquirentes.CODIGO', $filters['adquirentes']);
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
		return $this->query;
	}
}
