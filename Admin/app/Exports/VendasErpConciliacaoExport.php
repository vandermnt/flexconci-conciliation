<?php

namespace App\Exports;

use App\Filters\VendasErpSubFilter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VendasErpConciliacaoExport implements FromQuery, WithStrictNullComparison, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;
    
    protected $filters;
    protected $subfilters;

    public function __construct($filters, $subfilters) {
        $this->filters = $filters;
        $this->subfilters = $subfilters;
    }

    public function headings(): array
    {
        return [
            'ID ERP',
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
            'Valor Bruto',
            'Taxa R$',
            'Taxa %',
            'Taxa Op. %',
            'Dif. Taxa %',
            'Valor Líquido',
            'Valor Líquido Op.',
            'Dif. Líquido R$',
            'Parcela',
            'Total Parcelas',
            'Banco',
            'Agencia',
            'Conta Corrente',
            'Produto',
            'Meio de Captura',
            'Status Conciliação',
            'Divergência',
            'Status Financeiro',
            'Justificativa',
            'Campo 1',
            'Campo 2',
            'Campo 3',
            'Data de Importação',
            'Hora de Importação',
            'Data de Conciliação',
            'Hora de Conciliação',
        ];
    }

    public function map($venda): array
    {
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
            ($venda->VALOR_VENDA_PARCELA ?? $venda->TOTAL_VENDA) ?? 0,
            $venda->VALOR_TAXA ?? 0,
            $venda->TAXA ?? 0,
            $venda->TAXA_OPERADORA ?? 0,
            $venda->TAXA_DIFERENCA ?? 0,
            $venda->VALOR_LIQUIDO_PARCELA ?? 0,
            $venda->VALOR_LIQUIDO_OPERADORA ?? 0,
            $venda->DIFERENCA_LIQUIDO ?? 0,
            $venda->PARCELA,
            $venda->TOTAL_PARCELAS,
            $venda->BANCO,
            $venda->AGENCIA,
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
            is_null($venda->HORA_IMPORTACAO) ? null : date_format(date_create($venda->HORA_IMPORTACAO), 'H:m:s'),
            is_null($venda->DATA_CONCILIACAO) ? null : date_format(date_create($venda->DATA_CONCILIACAO), 'd/m/Y'),
            is_null($venda->HORA_CONCILIACAO) ? null : date_format(date_create($venda->HORA_CONCILIACAO), 'H:m:s'),
        ];
    }

    public function query()
    {
        return VendasErpSubFilter::subfilter($this->filters, $this->subfilters)
            ->getQuery()
            ->orderBy('DATA_VENDA');
    }
}
