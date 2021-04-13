<?php

namespace App\Exports\CSV;

use App\Filters\RecebimentosSubFilter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RetornoRecebimentosOperadorasExport implements FromQuery, WithStrictNullComparison, WithHeadings, WithMapping
{
	use Exportable;

	protected $filters;
	protected $subfilters;

	public function __construct($filters, $subfilters)
	{
		$this->filters = $filters;
		$this->subfilters = $subfilters;
	}

	public function headings(): array
	{
		return [
			'ID. ERP',
			'Tipo de Lançamento',
			'Empresa',
			'CNPJ',
			'Venda',
			'Previsão',
			'Pagamento',
			'Operadora',
			'Bandeira',
			'Forma de Pagamento',
			'Tipo de Recebimento',
			'NSU',
			'Autorização',
			'TID',
			'Cartão',
			'Resumo',
			'Valor Bruto',
			'Taxa %',
			'Taxa R$',
			'Taxa Antec. %',
			'Valor Líquido',
			'Possui Tarifa Mínima',
			'Parcela',
			'Total Parc.',
			'Estabelecimento',
			'Cód. Ajuste',
			'Desc. Ajuste',
			'Classificação Ajuste',
			'Núm. Máquina',
			'Banco',
			'Agencia',
			'Conta',
			'Observação',
			'Produto',
			'Meio de Captura',
			'Status Conciliação Rec',
			'Divergência Venda',
			'Justificativa',
			'Baixa Realizada ERP',
		];
	}

	public function map($item): array
	{
		return [
			trim($item->DESCRICAO_ERP, " "),
			trim($item->TIPO_LANCAMENTO, " "),
			trim($item->NOME_EMPRESA, " "),
			trim($item->CNPJ, " "),
			is_null($item->DATA_VENDA) ? null : date_format(date_create($item->DATA_VENDA), 'd/m/Y'),
			is_null($item->DATA_PREVISAO) ? null : date_format(date_create($item->DATA_PREVISAO), 'd/m/Y'),
			is_null($item->DATA_PAGAMENTO) ? null : date_format(date_create($item->DATA_PAGAMENTO), 'd/m/Y'),
			trim($item->ADQUIRENTE, " "),
			trim($item->BANDEIRA, " "),
			trim($item->MODALIDADE, " "),
			trim($item->TIPO_PAGAMENTO, " "),
			trim($item->NSU, " "),
			trim($item->AUTORIZACAO, " "),
			trim($item->TID, " "),
			trim($item->CARTAO, " "),
			trim($item->RESUMO, " "),
			round(($item->VALOR_BRUTO ?? 0), 2),
			round(($item->TAXA_PERCENTUAL ?? 0), 2),
			round((($item->VALOR_TAXA ?? 0) * -1), 2),
			round(($item->TAXA_ANTECIPACAO ?? 0), 2),
			round(($item->VALOR_LIQUIDO ?? 0), 2),
			trim($item->POSSUI_TAXA_MINIMA, " "),
			trim($item->PARCELA, " "),
			trim($item->TOTAL_PARCELAS, " "),
			trim($item->ESTABELECIMENTO, " "),
			trim($item->COD_AJUSTE, " "),
			trim($item->DESC_AJUSTE, " "),
			trim($item->CLASSIFICACAO_AJUSTE, " "),
			trim($item->TERMINAL, " "),
			trim($item->BANCO, " "),
			trim($item->AGENCIA, " "),
			trim($item->CONTA, " "),
			trim($item->OBSERVACOES, " "),
			trim($item->PRODUTO, " "),
			trim($item->MEIOCAPTURA, " "),
			trim($item->STATUS_CONCILIACAO, " "),
			trim($item->DIVERGENCIA, " "),
			trim($item->JUSTIFICATIVA, " "),
			trim($item->RETORNO_ERP_BAIXA, " "),

		];
	}

	public function query()
	{
		return RecebimentosSubFilter::subfilter($this->filters, $this->subfilters)
			->getQuery();
	}
}
