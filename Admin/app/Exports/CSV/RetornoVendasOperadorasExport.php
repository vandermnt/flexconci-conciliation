<?php

namespace App\Exports\CSV;

use App\Filters\VendasSubFilter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RetornoVendasOperadorasExport implements FromQuery, WithStrictNullComparison, WithHeadings, WithMapping
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
            'Núm. Máquina',
            'Banco',
            'Agencia',
            'Conta',
            'Observação',
            'Produto',
            'Meio de Captura',
            'Status Conciliação',
            'Divergência',
            'Status Financeiro',
            'Justificativa',
        ];
    }

    public function map($venda): array
    {
        return [
            trim($venda->DESCRICAO_ERP, " "),
            trim($venda->NOME_EMPRESA, " "),
            trim($venda->CNPJ, " "),
            is_null($venda->DATA_VENDA) ? null : date_format(date_create($venda->DATA_VENDA), 'd/m/Y'),
            is_null($venda->DATA_PREVISAO) ? null : date_format(date_create($venda->DATA_PREVISAO), 'd/m/Y'),
            trim($venda->ADQUIRENTE, " "),
            trim($venda->BANDEIRA, " "),
            trim($venda->MODALIDADE, " "),
            trim($venda->NSU, " "),
            trim($venda->AUTORIZACAO, " "),
            trim($venda->TID, " "),
            trim($venda->CARTAO, " "),
            round(($venda->VALOR_BRUTO ?? 0), 2),
            round(($venda->PERCENTUAL_TAXA ?? 0), 2),
            round((($venda->VALOR_TAXA ?? 0) * -1), 2),
            round(($venda->VALOR_LIQUIDO ?? 0), 2),
            trim($venda->PARCELA, " "),
            trim($venda->TOTAL_PARCELAS, " "),
            trim($venda->HORA_TRANSACAO, " "),
            trim($venda->ESTABELECIMENTO, " "),
            trim($venda->TERMINAL, " "),
            trim($venda->BANCO, " "),
            trim($venda->AGENCIA, " "),
            trim($venda->CONTA, " "),
            trim($venda->OBSERVACOES, " "),
            trim($venda->PRODUTO, " "),
            trim($venda->MEIOCAPTURA, " "),
            trim($venda->STATUS_CONCILIACAO, " "),
            trim($venda->DIVERGENCIA, " "),
            trim($venda->STATUS_FINANCEIRO, " "),
            trim($venda->JUSTIFICATIVA, " ")
        ];
    }

    public function query()
    {
        return VendasSubFilter::subfilter($this->filters, $this->subfilters)
            ->getQuery()
            ->orderBy('DATA_VENDA');
    }
}
