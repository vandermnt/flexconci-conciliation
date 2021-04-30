<?php

namespace App\Exports\CSV;

use App\Filters\VendasSubFilter;
use App\Exports\BaseExport;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RetornoVendasOperadorasExport extends BaseExport implements FromQuery, WithStrictNullComparison, WithHeadings, WithMapping
{
  use Exportable;

  protected $keys = [
    'DESCRICAO_ERP' => ['header' => 'ID. ERP', 'type' => 'string'],
    'NOME_EMPRESA' => ['header' => 'Empresa', 'type' => 'string'],
    'CNPJ' => ['header' => 'CNPJ', 'type' => 'forceToString'],
    'DATA_VENDA' => ['header' => 'Venda', 'type' => 'date'],
    'DATA_PREVISAO' => ['header' => 'Previsão', 'type' => 'date'],
    'ADQUIRENTE' => ['header' => 'Operadora', 'type' => 'string'],
    'BANDEIRA' => ['header' => 'Bandeira', 'type' => 'string'],
    'MODALIDADE' => ['header' => 'Forma de Pagamento', 'type' => 'string'],
    'NSU' => ['header' => 'NSU', 'type' => 'forceToString'],
    'AUTORIZACAO' => ['header' => 'Autorização', 'type' => 'forceToString'],
    'TID' => ['header' => 'Cartão', 'type' => 'forceToString'],
    'CARTAO' => ['header' => 'TID', 'type' => 'forceToString'],
    'VALOR_BRUTO' => ['header' => 'Valor Bruto', 'type' => 'numeric'],
    'PERCENTUAL_TAXA' => ['header' => 'Taxa %', 'type' => 'numeric'],
    'VALOR_TAXA' => ['header' => 'Taxa R$', 'type' => 'numeric'],
    'VALOR_LIQUIDO' => ['header' => 'Valor Líquido', 'type' => 'numeric'],
    'POSSUI_TAXA_MINIMA' => ['header' => 'Possui Tarifa Mínima', 'type' => 'string'],
    'PARCELA' => ['header' => 'Parcela', 'type' => 'string'],
    'TOTAL_PARCELAS' => ['header' => 'Total Parcelas', 'type' => 'string'],
    'HORA_TRANSACAO' => ['header' => 'Hora', 'type' => 'string'],
    'ESTABELECIMENTO' => ['header' => 'Estabelecimento', 'type' => 'forceToString'],
    'TERMINAL' => ['header' => 'Núm. Máquina', 'type' => 'forceToString'],
    'BANCO' => ['header' => 'Banco', 'type' => 'string'],
    'AGENCIA' => ['header' => 'Agencia', 'type' => 'forceToString'],
    'CONTA' => ['header' => 'Conta', 'type' => 'forceToString'],
    'OBSERVACOES' => ['header' => 'Observação', 'type' => 'string'],
    'PRODUTO' => ['header' => 'Produto', 'type' => 'string'],
    'MEIOCAPTURA' => ['header' => 'Meio de Captura', 'type' => 'string'],
    'STATUS_CONCILIACAO' => ['header' => 'Status Conciliação', 'type' => 'string'],
    'DIVERGENCIA' => ['header' => 'Divergência', 'type' => 'string'],
    'STATUS_FINANCEIRO' => ['header' => 'Status Financeiro', 'type' => 'string'],
    'JUSTIFICATIVA' => ['header' => 'Justificativa', 'type' => 'string'],
  ];

  public function __construct($filters, $subfilters, $hidden = []) {
    parent::__construct($filters, $subfilters, $hidden);
  }

  public function headings(): array
  {
    return $this->getHeaders();
  }

  public function map($venda): array
  {
    return $this->getValues($venda);
  }

  public function query()
  {
    return VendasSubFilter::subfilter($this->filters, $this->subfilters)
        ->getQuery();
  }
}
