<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use App\Filters\VendasErpSubFilter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VendasErpExport extends BaseExport implements FromQuery, WithStrictNullComparison, ShouldAutoSize, WithHeadings, WithMapping
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
      'CARTAO' => ['header' => 'Cartão', 'type' => 'forceToString'],
      'VALOR_VENDA' => ['header' => 'Valor Bruto', 'type' => 'numeric'],
      'TAXA' => ['header' => 'Taxa %', 'type' => 'numeric'],
      'VALOR_TAXA' => ['header' => 'Taxa R$', 'type' => 'numeric'],
      'VALOR_LIQUIDO_PARCELA' => ['header' => 'Valor Líquido', 'type' => 'numeric'],
      'PARCELA' => ['header' => 'Parcela', 'type' => 'string'],
      'TOTAL_PARCELAS' => ['header' => 'Total Parcelas', 'type' => 'string'],
      'HORA' => ['header' => 'Hora', 'type' => 'string'],
      'ESTABELECIMENTO' => ['header' => 'Estabelecimento', 'type' => 'forceToString'],
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
        return [
            'ID. ERP',
            'Empresa',
            'CNPJ',
            'Venda',
            'Previsão',
            'Operadora',
            'Bandeira',
            'Forma de Pagamento',
            'NSU',
            'Autorização',
            'TID',
            'Cartão',
            'Valor Bruto',
            'Taxa %',
            'Taxa R$',
            'Valor Líquido',
            'Parcela',
            'Total Parcelas',
            'Hora',
            'Estabelecimento',
            'Banco',
            'Agencia',
            'Conta',
            'Produto',
            'Meio de Captura',
            'Status Conciliação',
            'Divergência',
            'Status Financeiro',
            'Justificativa',
            ucwords(mb_strtolower($this->dynamicHeaders->TITULO_CAMPO1 ?? 'Campo 1', 'utf-8')),
            ucwords(mb_strtolower($this->dynamicHeaders->TITULO_CAMPO2 ?? 'Campo 2', 'utf-8')),
            ucwords(mb_strtolower($this->dynamicHeaders->TITULO_CAMPO3 ?? 'Campo 3', 'utf-8')),
            'Data Importação',
            'Hora Importação',
            'Data Conciliação',
            'Hora Conciliação',
        ];
    }

    public function map($venda): array
    {
      return $this->getValues($venda);
        return [
            $venda->DESCRICAO_ERP,
            $venda->NOME_EMPRESA,
            $venda->CNPJ." ",
            is_null($venda->DATA_VENDA) ? null : date_format(date_create($venda->DATA_VENDA), 'd/m/Y'),
            is_null($venda->DATA_VENCIMENTO) ? null : date_format(date_create($venda->DATA_VENCIMENTO), 'd/m/Y'),
            $venda->ADQUIRENTE,
            $venda->BANDEIRA,
            $venda->MODALIDADE,
            $venda->NSU." ",
            $venda->CODIGO_AUTORIZACAO." ",
            $venda->TID." ",
            $venda->CARTAO." ",
            $venda->VALOR_VENDA ?? 0,
            $venda->TAXA ?? 0,
            ($venda->VALOR_TAXA ?? 0) * -1,
            $venda->VALOR_LIQUIDO_PARCELA ?? 0,
            $venda->PARCELA,
            $venda->TOTAL_PARCELAS,
            $venda->HORA,
            $venda->ESTABELECIMENTO." ",
            $venda->BANCO,
            $venda->AGENCIA." ",
            $venda->CONTA_CORRENTE." ",
            $venda->PRODUTO,
            $venda->MEIOCAPTURA,
            $venda->STATUS_CONCILIACAO,
            $venda->DIVERGENCIA,
            $venda->STATUS_FINANCEIRO,
            $venda->JUSTIFICATIVA,
            $venda->CAMPO1,
            $venda->CAMPO2,
            $venda->CAMPO3,
            is_null($venda->DATA_IMPORTACAO) ? null : date_format(date_create($venda->DATA_IMPORTACAO), 'd/m/Y'),
            $venda->HORA_IMPORTACAO,
            is_null($venda->DATA_CONCILIACAO) ? null : date_format(date_create($venda->DATA_CONCILIACAO), 'd/m/Y'),
            $venda->HORA_CONCILIACAO,
        ];
    }

    public function query()
    {
        return VendasErpSubFilter::subfilter($this->filters, $this->subfilters)
            ->getQuery();
    }
}

