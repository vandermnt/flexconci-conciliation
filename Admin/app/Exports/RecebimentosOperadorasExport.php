<?php

namespace App\Exports;

use App\Filters\RecebimentosSubFilter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RecebimentosOperadorasExport extends BaseExport implements FromQuery, WithStrictNullComparison, ShouldAutoSize, WithHeadings, WithMapping
{
	use Exportable;

  protected $keys = [
    'DESCRICAO_ERP' => ['header' => 'ID. ERP', 'type' => 'string'],
    'TIPO_LANCAMENTO' => ['header' => 'Tipo de Lançamento', 'type' => 'string'],
    'NOME_EMPRESA' => ['header' => 'Empresa', 'type' => 'string'],
    'CNPJ' => ['header' => 'CNPJ', 'type' => 'forceToString'],
    'DATA_VENDA' => ['header' => 'Venda', 'type' => 'date'],
    'DATA_PREVISAO' => ['header' => 'Previsão', 'type' => 'date'],
    'DATA_PAGAMENTO' => ['header' => 'Pagamento', 'type' => 'date'],
    'ADQUIRENTE' => ['header' => 'Operadora', 'type' => 'string'],
    'BANDEIRA' => ['header' => 'Bandeira', 'type' => 'string'],
    'MODALIDADE' => ['header' => 'Forma de Pagamento', 'type' => 'string'],
    'TIPO_PAGAMENTO' => ['header' => 'Tipo de Recebimento', 'type' => 'string'],
    'NSU' => ['header' => 'NSU', 'type' => 'forceToString'],
    'AUTORIZACAO' => ['header' => 'Autorização', 'type' => 'forceToString'],
    'TID' => ['header' => 'TID', 'type' => 'forceToString'],
    'CARTAO' => ['header' => 'Cartão', 'type' => 'forceToString'],
    'VALOR_BRUTO' => ['header' => 'Valor Bruto', 'type' => 'numeric'],
    'TAXA_PERCENTUAL' => ['header' => 'Taxa %', 'type' => 'numeric'],
    'VALOR_TAXA' => ['header' => 'Taxa R$', 'type' => 'numeric'],
    'TAXA_ANTECIPACAO' => ['header' => 'Taxa Antec. %', 'type' => 'numeric'],
    'VALOR_LIQUIDO' => ['header' => 'Valor Líquido', 'type' => 'numeric'],
    'POSSUI_TAXA_MINIMA' => ['header' => 'Possui Tarifa Mínima', 'type' => 'forceToString'],
    'PARCELA' => ['header' => 'Parcela', 'type' => 'string'],
    'TOTAL_PARCELAS' => ['header' => 'Total Parc.', 'type' => 'string'],
    'ESTABELECIMENTO' => ['header' => 'Estabelecimento', 'type' => 'forceToString'],
    'COD_AJUSTE' => ['header' => 'Cód. Ajuste', 'type' => 'forceToString'],
    'DESC_AJUSTE' => ['header' => 'Desc. Ajuste', 'type' => 'forceToString'],
    'CLASSIFICACAO_AJUSTE' => ['header' => 'Classificação Ajuste', 'type' => 'forceToString'],
    'BANCO' => ['header' => 'Banco', 'type' => 'string'],
    'AGENCIA' => ['header' => 'Agência', 'type' => 'forceToString'],
    'CONTA' => ['header' => 'Conta', 'type' => 'forceToString'],
    'OBSERVACOES' => ['header' => 'Observação', 'type' => 'string'],
    'PRODUTO' => ['header' => 'Produto', 'type' => 'string'],
    'MEIOCAPTURA' => ['header' => 'Meio de Captura', 'type' => 'string'],
    'STATUS_CONCILIACAO' => ['header' => 'Status Conciliação Rec', 'type' => 'string'],
    'DIVERGENCIA' => ['header' => 'Divergência Venda', 'type' => 'string'],
    'RETORNO_ERP_BAIXA' => ['header' => 'Baixa Realizada ERP', 'type' => 'string'],
  ];

	public function __construct($filters, $subfilters, $hidden = []) {
    parent::__construct($filters, $subfilters, $hidden);
  }

	public function headings(): array
  {
    return $this->getHeaders();
  }

	public function map($item): array
  {
    return $this->getValues($item);
  }

	public function query()
	{
		return RecebimentosSubFilter::subfilter($this->filters, $this->subfilters)
			->getQuery();
	}
}
