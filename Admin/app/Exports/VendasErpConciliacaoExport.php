<?php

namespace App\Exports;

use App\Filters\VendasErpSubFilter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VendasErpConciliacaoExport extends BaseExport implements FromQuery, WithStrictNullComparison, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    protected $keys = [
      'DESCRICAO_ERP' => ['header' => 'ID. ERP', 'type' => 'string'],
      'NOME_EMPRESA' => ['header' => 'Empresa', 'type' => 'string'],
      'CNPJ' => ['header' => 'CNPJ', 'type' => 'forceToString'],
      'DATA_VENDA' => ['header' => 'Venda', 'type' => 'date'],
      'DATA_VENCIMENTO' => ['header' => 'Previsão', 'type' => 'date'],
      'ADQUIRENTE' => ['header' => 'Operadora', 'type' => 'string'],
      'BANDEIRA' => ['header' => 'Bandeira', 'type' => 'string'],
      'MODALIDADE' => ['header' => 'Forma de Pagamento', 'type' => 'string'],
      'NSU' => ['header' => 'NSU', 'type' => 'forceToString'],
      'CODIGO_AUTORIZACAO' => ['header' => 'Autorização', 'type' => 'forceToString'],
      'TID' => ['header' => 'TID', 'type' => 'forceToString'],
      'VALOR_VENDA' => ['header' => 'Valor Bruto', 'type' => 'numeric'],
      'VALOR_TAXA' => ['header' => 'Taxa R$', 'type' => 'numeric'],
      'TAXA' => ['header' => 'Taxa %', 'type' => 'numeric'],
      'TAXA_OPERADORA' => ['Taxa Op. %' => 'Taxa %', 'type' => 'numeric'],
      'TAXA_DIFERENCA' => ['header' => 'Dif. Taxa %', 'type' => 'numeric'],
      'VALOR_LIQUIDO_PARCELA' => ['header' => 'Valor Líquido', 'type' => 'numeric'],
      'VALOR_LIQUIDO_OPERADORA' => ['header' => 'Valor Líquido Op.', 'type' => 'numeric'],
      'DIFERENCA_LIQUIDO' => ['header' => 'Dif. Líquido R$', 'type' => 'numeric'],
      'PARCELA' => ['header' => 'Parcela', 'type' => 'string'],
      'TOTAL_PARCELAS' => ['header' => 'Total Parcelas', 'type' => 'string'],
      'BANCO' => ['header' => 'Banco', 'type' => 'string'],
      'AGENCIA' => ['header' => 'Agencia', 'type' => 'string'],
      'CONTA_CORRENTE' => ['header' => 'Conta', 'type' => 'string'],
      'PRODUTO' => ['header' => 'Produto', 'type' => 'string'],
      'MEIOCAPTURA' => ['header' => 'Meio de Captura', 'type' => 'string'],
      'STATUS_CONCILIACAO' => ['header' => 'Status Conciliação', 'type' => 'string'],
      'DIVERGENCIA' => ['header' => 'Divergência', 'type' => 'string'],
      'JUSTIFICATIVA' => ['header' => 'Justificativa', 'type' => 'string'],
      'CAMPO1' => ['header' => 'Campo 1', 'type' => 'string'],
      'CAMPO2' => ['header' => 'Campo 2', 'type' => 'string'],
      'CAMPO3' => ['header' => 'Campo 3', 'type' => 'string'],
      'DATA_IMPORTACAO' => ['header' => 'Data Importação', 'type' => 'date'],
      'HORA_IMPORTACAO' => ['header' => 'Hora Importação', 'type' => 'string'],
      'DATA_CONCILIACAO' => ['header' => 'Data Conciliação', 'type' => 'date'],
      'HORA_CONCILIACAO' => ['header' => 'Hora Conciliação', 'type' => 'string'],
    ];

    public function __construct($filters, $subfilters, $hidden = [], $dynamicHeaders = []) {
      parent::__construct($filters, $subfilters, $hidden, $dynamicHeaders);
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
        return VendasErpSubFilter::subfilter($this->filters, $this->subfilters)
            ->getQuery();
    }
}
