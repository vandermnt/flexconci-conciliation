<?php

namespace App\Exports;

use App\Filters\VendasErpFilter;
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

    public function __construct($filters) {
        $this->filters = $filters;
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
            'Taxa %',
            'Taxa R$',
            'Valor Líquido',
            'Parcela',
            'Total Parcelas',
            'Banco',
            'Agencia',
            'Conta Corrente',
            'Produto',
            'Meio de Captura',
            'Status Conciliação',
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
            $venda->ID_ERP,
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
            number_format($venda->TOTAL_VENDA, 2, ',', '.'),
            number_format($venda->TAXA, 2, ',', '.'),
            number_format($venda->VALOR_TAXA, 2, ',', '.'),
            number_format($venda->VALOR_LIQUIDO_PARCELA, 2, ',', '.'),
            $venda->PARCELA,
            $venda->TOTAL_PARCELAS,
            $venda->BANCO,
            $venda->AGENCIA,
            $venda->CONTA_CORRENTE." ",
            $venda->PRODUTO,
            $venda->MEIOCAPTURA,
            $venda->STATUS_CONCILIACAO,
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
        return VendasErpFilter::filter($this->filters)->getQuery();
    }
}
