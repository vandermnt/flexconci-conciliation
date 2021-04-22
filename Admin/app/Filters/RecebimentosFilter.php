<?php

namespace App\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Filters\BaseFilter;

class RecebimentosFilter extends BaseFilter
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
		'estabelecimentos',
		'domicilios_bancarios',
		'recebimento_conciliado_erp',
		'tipo_pagamento'
	];

	public static function filter($params)
	{
		$recebimentosFilter = app(RecebimentosFilter::class);
		return $recebimentosFilter->apply($params);
	}

	public function apply($params)
	{
		$params = Arr::only($params, $this->getAllowedKeys());
		$params = Arr::where($params, function ($value, $key) {
			return boolval($value);
		});
		$filters = Arr::except($params, 'sort');
		$sort = collect(Arr::only($params, 'sort'))->get('sort');
		$recebimento_conciliado_erp = $filters['recebimento_conciliado_erp'] ?? null;

		$this->query = DB::table('pagamentos_operadoras')
			->select([
				'pagamentos_operadoras.CODIGO as ID',
				'pagamentos_operadoras.ID_VENDA_ERP as DESCRICAO_ERP',
				'grupos_clientes.NOME_EMPRESA',
				'grupos_clientes.CNPJ',
				'pagamentos_operadoras.DATA_VENDA',
				'pagamentos_operadoras.DATA_PREV_PAG_ORIGINAL as DATA_PREVISAO',
				'pagamentos_operadoras.DATA_PAGAMENTO',
				'adquirentes.ADQUIRENTE',
				'adquirentes.IMAGEM as ADQUIRENTE_IMAGEM',
				'bandeira.BANDEIRA',
				'bandeira.IMAGEM as BANDEIRA_IMAGEM',
				'modalidade.DESCRICAO as MODALIDADE',
				'tipo_pagamento.TIPO_PAGAMENTO',
				'pagamentos_operadoras.NSU',
				'pagamentos_operadoras.CODIGO_AUTORIZACAO as AUTORIZACAO',
				'pagamentos_operadoras.TID',
				'pagamentos_operadoras.NUMERO_CARTAO as CARTAO',
				'pagamentos_operadoras.VALOR_BRUTO',
				'pagamentos_operadoras.TAXA_ANTECIPACAO',
				'pagamentos_operadoras.VALOR_TAXA_ANTECIPACAO',
				'pagamentos_operadoras.NUMERO_RESUMO_VENDA',
				'pagamentos_operadoras.NUMERO_TERMINAL',
				'pagamentos_operadoras.TAXA_PERCENTUAL',
				'pagamentos_operadoras.VALOR_TAXA',
				'pagamentos_operadoras.VALOR_LIQUIDO',
				'pagamentos_operadoras.PARCELA',
				'pagamentos_operadoras.TOTAL_PARCELAS',
				'pagamentos_operadoras.ID_LOJA as ESTABELECIMENTO',
				'lista_bancos.NOME_WEB as BANCO',
				'lista_bancos.IMAGEM_LINK as BANCO_IMAGEM',
				'pagamentos_operadoras.AGENCIA',
				'pagamentos_operadoras.CONTA',
				'pagamentos_operadoras.OBSERVACOES',
				'pagamentos_operadoras.DIVERGENCIA',
				'pagamentos_operadoras.JUSTIFICATIVA',
				'pagamentos_operadoras.COD_TIPO_PAGAMENTO',
				'pagamentos_operadoras.NUMERO_OPERACAO_ANTECIPACAO',
				'status_conciliacao.STATUS_CONCILIACAO',
				'tipo_lancamento.TIPO_LANCAMENTO',
				'produto_web.PRODUTO_WEB as PRODUTO',
			])
			->leftJoin('produto_web', 'produto_web.CODIGO', 'pagamentos_operadoras.COD_PRODUTO')
			->leftJoin('grupos_clientes', 'grupos_clientes.CODIGO', 'pagamentos_operadoras.COD_GRUPO_CLIENTE')
			->leftJoin('adquirentes', 'adquirentes.CODIGO', 'pagamentos_operadoras.COD_ADQUIRENTE')
			->leftJoin('bandeira', 'bandeira.CODIGO', 'pagamentos_operadoras.COD_BANDEIRA')
			->leftJoin('modalidade', 'modalidade.CODIGO', 'pagamentos_operadoras.COD_FORMA_PAGAMENTO')
			->leftJoin('tipo_pagamento', 'tipo_pagamento.CODIGO', 'pagamentos_operadoras.COD_TIPO_PAGAMENTO')
			->leftJoin('tipo_lancamento', 'tipo_lancamento.CODIGO', 'pagamentos_operadoras.COD_TIPO_LANCAMENTO')
			->leftJoin('lista_bancos', 'lista_bancos.CODIGO', 'pagamentos_operadoras.COD_BANCO')
			->leftJoin('meio_captura', 'meio_captura.CODIGO', 'pagamentos_operadoras.COD_MEIO_CAPTURA')
			->leftJoin('status_conciliacao', 'status_conciliacao.CODIGO', 'pagamentos_operadoras.COD_STATUS')
			->where(
				[
					['pagamentos_operadoras.COD_CLIENTE', $filters['cliente_id']],
					['tipo_pagamento.CODIGO', '!=', 3]
				]
			);

		if (Arr::has($filters, 'id')) {
			$this->query->whereIn('pagamentos_operadoras.CODIGO', $filters['id']);
		}
		if (Arr::has($filters, ['data_inicial', 'data_final'])) {
			$this->query->whereBetween('pagamentos_operadoras.DATA_PAGAMENTO', [
				$filters['data_inicial'],
				$filters['data_final']
			]);
		}
		if (Arr::has($filters, 'grupos_clientes')) {
			$this->query->whereIn('grupos_clientes.CODIGO', $filters['grupos_clientes']);
		}
		if (Arr::has($filters, 'adquirentes')) {
			$this->query->whereIn('adquirentes.CODIGO', $filters['adquirentes']);
		}
		if (Arr::has($filters, 'estabelecimentos')) {
			$this->query->whereIn('pagamentos_operadoras.ID_LOJA', $filters['estabelecimentos']);
		}
		if (Arr::has($filters, 'bandeiras')) {
			$this->query->whereIn('bandeira.CODIGO', $filters['bandeiras']);
		}
		if (Arr::has($filters, 'modalidades')) {
			$this->query->whereIn('modalidade.CODIGO', $filters['modalidades']);
		}
		if (Arr::has($filters, 'status_conciliacao')) {
			$this->query->whereIn('pagamentos_operadoras.COD_STATUS', $filters['status_conciliacao']);
		}
		if (!is_null($recebimento_conciliado_erp) && count($recebimento_conciliado_erp) < 2) {
			$filterValue = $recebimento_conciliado_erp[0];
			$whereOperator = $filterValue === 'true' ? '!=' : "=";
			$this->query->where('ID_VENDA_ERP', $whereOperator, NULL);
		}
		if (Arr::has($filters, 'domicilios_bancarios')) {
			$this->query->whereIn('lista_bancos.CODIGO', function ($query) use ($filters) {
				$query->select('domicilio_cliente.COD_BANCO')
					->from('domicilio_cliente')
					->whereIn('domicilio_cliente.CODIGO', $filters['domicilios_bancarios']);
			});
			$this->query->whereIn('pagamentos_operadoras.AGENCIA', function ($query) use ($filters) {
				$query->select('domicilio_cliente.AGENCIA')
					->from('domicilio_cliente')
					->whereIn('domicilio_cliente.CODIGO', $filters['domicilios_bancarios']);
			});
			$this->query->whereIn('pagamentos_operadoras.CONTA', function ($query) use ($filters) {
				$query->select('domicilio_cliente.CONTA')
					->from('domicilio_cliente')
					->whereIn('domicilio_cliente.CODIGO', $filters['domicilios_bancarios']);
			});
		}
		if (Arr::has($filters, 'tipo_pagamento')) {
			$this->query->whereIn('pagamentos_operadoras.COD_TIPO_PAGAMENTO', $filters['tipo_pagamento']);
		}

		$this->buildOrderClause($sort);

		return $this;
	}

	public function getQuery()
	{
		return $this->query;
	}
}
