<?php

namespace App\Exports;

use App\Filters\RecebimentosSubFilter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RecebimentosOperadorasExport implements FromQuery, WithStrictNullComparison, ShouldAutoSize, WithHeadings, WithMapping
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
			'ID',
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
			'Taxa Antec. R$',
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
			'Agência',
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
			$item->DESCRICAO_ERP,
			$item->TIPO_LANCAMENTO,
			$item->NOME_EMPRESA,
			$item->CNPJ . " ",
			is_null($item->DATA_VENDA) ? null : date_format(date_create($item->DATA_VENDA), 'd/m/Y'),
			is_null($item->DATA_PREVISAO) ? null : date_format(date_create($item->DATA_PREVISAO), 'd/m/Y'),
			is_null($item->DATA_PAGAMENTO) ? null : date_format(date_create($item->DATA_PAGAMENTO), 'd/m/Y'),
			$item->ADQUIRENTE,
			$item->BANDEIRA,
			$item->MODALIDADE,
			$item->TIPO_PAGAMENTO,
			$item->NSU . " ",
			$item->AUTORIZACAO . " ",
			$item->TID . " ",
			$item->CARTAO . " ",
			$item->RESUMO . " ",
			round(($item->VALOR_BRUTO ?? 0), 2),
			round(($item->TAXA_PERCENTUAL ?? 0), 2),
			round((($item->VALOR_TAXA ?? 0) * -1), 2),
			round(($item->TAXA_ANTECIPACAO ?? 0), 2),
			round(($item->VALOR_TAXA_ANTECIPACAO ?? 0), 2),
			round(($item->VALOR_LIQUIDO ?? 0), 2),
			$item->POSSUI_TAXA_MINIMA . " ",
			$item->PARCELA,
			$item->TOTAL_PARCELAS,
			$item->ESTABELECIMENTO . " ",
			$item->COD_AJUSTE . " ",
			$item->DESC_AJUSTE . " ",
			$item->CLASSIFICACAO_AJUSTE . " ",
			$item->TERMINAL . " ",
			$item->BANCO,
			$item->AGENCIA . " ",
			$item->CONTA . " ",
			$item->OBSERVACOES,
			$item->PRODUTO,
			$item->MEIOCAPTURA,
			$item->STATUS_CONCILIACAO,
			$item->DIVERGENCIA,
			$item->JUSTIFICATIVA,
			$item->RETORNO_ERP_BAIXA
		];
	}

	public function query()
	{
		return RecebimentosSubFilter::subfilter($this->filters, $this->subfilters)
			->getQuery();
	}
}
