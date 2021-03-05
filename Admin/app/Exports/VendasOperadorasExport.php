<?php

namespace App\Exports;

use App\Filters\VendasSubFilter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VendasOperadorasExport implements FromQuery, WithStrictNullComparison, ShouldAutoSize, WithHeadings, WithMapping
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
            'Possui Tarifa Mínima',
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
            $venda->DESCRICAO_ERP,
            $venda->NOME_EMPRESA,
            $venda->CNPJ." ",
            is_null($venda->DATA_VENDA) ? null : date_format(date_create($venda->DATA_VENDA), 'd/m/Y'),
            is_null($venda->DATA_PREVISAO) ? null : date_format(date_create($venda->DATA_PREVISAO), 'd/m/Y'),
            $venda->ADQUIRENTE,
            $venda->BANDEIRA,
            $venda->MODALIDADE,
            $venda->NSU." ",
            $venda->AUTORIZACAO." ",
            $venda->TID." ",
            $venda->CARTAO." ",
            round(($venda->VALOR_BRUTO ?? 0), 2),
            round(($venda->PERCENTUAL_TAXA ?? 0), 2),
            round((($venda->VALOR_TAXA ?? 0) * -1), 2),
            round(($venda->VALOR_LIQUIDO ?? 0), 2),
            $venda->POSSUI_TAXA_MINIMA,
            $venda->PARCELA,
            $venda->TOTAL_PARCELAS,
            $venda->HORA_TRANSACAO,
            $venda->ESTABELECIMENTO." ",
            $venda->TERMINAL." ",
            $venda->BANCO,
            $venda->AGENCIA." ",
            $venda->CONTA." ",
            $venda->OBSERVACOES,
            $venda->PRODUTO,
            $venda->MEIOCAPTURA,
            $venda->STATUS_CONCILIACAO,
            $venda->DIVERGENCIA,
            $venda->STATUS_FINANCEIRO,
            $venda->JUSTIFICATIVA
        ];
    }

    public function query()
    {
        return VendasSubFilter::subfilter($this->filters, $this->subfilters)
            ->getQuery()
            ->orderBy('DATA_VENDA');
    }
}
