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
				DB::raw('(
          (`pagamentos_operadoras`.`VALOR_BRUTO` - `pagamentos_operadoras`.`VALOR_LIQUIDO`) * 100)
            / `pagamentos_operadoras`.`VALOR_BRUTO`
            as `TAXA_PERCENTUAL`'),
				DB::raw('
          (`pagamentos_operadoras`.`VALOR_BRUTO` - `pagamentos_operadoras`.`VALOR_LIQUIDO`)
            as `VALOR_TAXA`'),
				DB::raw('null as `TAXA_ANTECIPACAO_PERCENTUAL`'),
				DB::raw('null as `VALOR_ANTECIPACAO`'),
				'pagamentos_operadoras.VALOR_LIQUIDO',
				DB::raw('
        if(coalesce(`vendas`.`TAXA_MINIMA`, 0) <> 0, \'Sim\', \'Não\')
            as `POSSUI_TAXA_MINIMA`'),
				'pagamentos_operadoras.PARCELA',
				'pagamentos_operadoras.TOTAL_PARCELAS',
				'vendas.HORA_TRANSACAO',
				'pagamentos_operadoras.ID_LOJA as ESTABELECIMENTO',
				'vendas.TERMINAL',
				'lista_bancos.NOME_WEB as BANCO',
				'lista_bancos.IMAGEM_LINK as BANCO_IMAGEM',
				'pagamentos_operadoras.AGENCIA',
				'pagamentos_operadoras.CONTA',
				'pagamentos_operadoras.OBSERVACOES',
				'produto_web.PRODUTO_WEB as PRODUTO',
				'meio_captura.DESCRICAO as MEIOCAPTURA',
				'status_conciliacao.STATUS_CONCILIACAO',
				'vendas.DIVERGENCIA',
				'vendas.JUSTIFICATIVA',
				'pagamentos_operadoras.COD_TIPO_PAGAMENTO',
				DB::raw('IF(vendas_erp.RETORNO_ERP_BAIXA = \'S\', \'Sim\', \'Não\') as RETORNO_ERP_BAIXA'),
			])
			->leftJoin('vendas', 'vendas.CODIGO', 'pagamentos_operadoras.COD_VENDA')
			->leftJoin('vendas_erp', 'vendas.COD_VENDA_ERP', 'vendas_erp.CODIGO')
			->leftJoin('produto_web', 'produto_web.CODIGO', 'pagamentos_operadoras.COD_PRODUTO')
			->leftJoin('grupos_clientes', 'grupos_clientes.CODIGO', 'pagamentos_operadoras.COD_GRUPO_CLIENTE')
			->leftJoin('adquirentes', 'adquirentes.CODIGO', 'pagamentos_operadoras.COD_ADQUIRENTE')
			->leftJoin('bandeira', 'bandeira.CODIGO', 'pagamentos_operadoras.COD_BANDEIRA')
			->leftJoin('modalidade', 'modalidade.CODIGO', 'pagamentos_operadoras.COD_FORMA_PAGAMENTO')
			->leftJoin('tipo_pagamento', 'tipo_pagamento.CODIGO', 'pagamentos_operadoras.COD_TIPO_PAGAMENTO')
			->leftJoin('lista_bancos', 'lista_bancos.CODIGO', 'pagamentos_operadoras.COD_BANCO')
			->leftJoin('meio_captura', 'meio_captura.CODIGO', 'pagamentos_operadoras.COD_MEIO_CAPTURA')
			->leftJoin('status_conciliacao', 'status_conciliacao.CODIGO', 'pagamentos_operadoras.COD_STATUS')
			->where('pagamentos_operadoras.COD_CLIENTE', $filters['cliente_id']);

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
		if (Arr::has($filters, 'status_conciliacao')) {
			$this->query->whereIn('pagamentos_operadoras.COD_STATUS', $filters['status_conciliacao']);
		}
		if (!is_null($recebimento_conciliado_erp) && count($recebimento_conciliado_erp) < 2) {
			$filterValue = $recebimento_conciliado_erp[0];
			$whereOperator = $filterValue === 'true' ? '!=' : "=";

			$this->query->where('ID_VENDA_ERP', $whereOperator, null);
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
