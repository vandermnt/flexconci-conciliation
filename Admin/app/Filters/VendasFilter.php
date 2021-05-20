<?php

namespace App\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\VendasModel;
use App\GruposClientesModel;
use App\Filters\BaseFilter;

class VendasFilter extends BaseFilter
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

		$this->query = VendasModel::select(
			[
				'vendas.CODIGO as ID',
				'vendas_erp.DESCRICAO_TIPO_PRODUTO as DESCRICAO_ERP',
				'vendas.EMPRESA as NOME_EMPRESA',
				'vendas.CNPJ',
				'vendas.DATA_VENDA',
				'vendas.DATA_PREVISTA_PAGTO as DATA_PREVISAO',
                'vendas.DATA_CANCELAMENTO',
				'adquirentes.ADQUIRENTE',
				'adquirentes.IMAGEM as ADQUIRENTE_IMAGEM',
				'bandeira.BANDEIRA',
				'bandeira.IMAGEM as BANDEIRA_IMAGEM',
				'modalidade.DESCRICAO as MODALIDADE',
				'vendas.NSU',
				'vendas.AUTORIZACAO',
				'vendas.CARTAO',
				'vendas.TID',
				'vendas.VALOR_BRUTO',
				'vendas.PERCENTUAL_TAXA',
				DB::raw('
            (`vendas`.`VALOR_BRUTO` - `vendas`.`VALOR_LIQUIDO`)
              as `VALOR_TAXA`'),
				'vendas.VALOR_LIQUIDO',
				DB::raw('
            if(coalesce(`vendas`.`TAXA_MINIMA`, 0) <> 0, \'Sim\', \'Não\')
                as `POSSUI_TAXA_MINIMA`'),
				'vendas.PARCELA',
				'vendas.TOTAL_PARCELAS',
				'vendas.HORA_TRANSACAO',
				'vendas.ESTABELECIMENTO',
				'vendas.TERMINAL',
				'vendas.NUMERO_RESUMO_VENDA as RESUMO',
				'lista_bancos.BANCO',
				'lista_bancos.IMAGEM_LINK as BANCO_IMAGEM',
				'vendas.AGENCIA',
				'vendas.CONTA',
				'vendas.OBSERVACOES',
				'produto_web.PRODUTO_WEB as PRODUTO',
				'meio_captura.DESCRICAO as MEIOCAPTURA',
				'status_conciliacao.STATUS_CONCILIACAO',
				'status_conciliacao.IMAGEM_URL as STATUS_CONCILIACAO_IMAGEM',
				'vendas.DIVERGENCIA',
				'status_financeiro.STATUS_FINANCEIRO',
				'vendas.JUSTIFICATIVA'
			]
		)
			->leftJoin('vendas_erp', 'vendas_erp.CODIGO', 'vendas.COD_VENDA_ERP')
			->leftJoin('adquirentes', 'adquirentes.CODIGO', 'vendas.ADQID')
			->leftJoin('bandeira', 'bandeira.CODIGO', 'vendas.COD_BANDEIRA')
			->leftJoin('modalidade', 'modalidade.CODIGO', 'vendas.CODIGO_MODALIDADE')
			->leftJoin('lista_bancos', 'lista_bancos.CODIGO', 'vendas.BANCO')
			->leftJoin('produto_web', 'produto_web.CODIGO', 'vendas.COD_PRODUTO')
			->leftJoin('meio_captura', 'vendas.COD_MEIO_CAPTURA', 'meio_captura.CODIGO')
			->leftJoin('status_conciliacao', 'vendas.COD_STATUS_CONCILIACAO', 'status_conciliacao.CODIGO')
			->leftJoin('status_financeiro', 'vendas.COD_STATUS_FINANCEIRO', 'status_financeiro.CODIGO')
			->where('vendas.COD_CLIENTE', $filters['cliente_id']);

		if (Arr::has($filters, 'id')) {
			$this->query->whereIn('vendas.CODIGO', $filters['id']);
		}
		if (Arr::has($filters, ['data_inicial', 'data_final'])) {
			$this->query->whereBetween('vendas.DATA_VENDA', [
				$filters['data_inicial'],
				$filters['data_final']
			]);
		}
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
