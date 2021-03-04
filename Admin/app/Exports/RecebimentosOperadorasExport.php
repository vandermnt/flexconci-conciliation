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

    public function __construct($filters, $subfilters) {
        $this->filters = $filters;
        $this->subfilters = $subfilters;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Empresa',
            'CNPJ',
            'Venda',
            'Previsão',
            'Pagamento',
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
            'Taxa Antec. %',
            'Valor Líquido',
            'Parcela',
            'Total Parc.',
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

    public function map($item): array
    {
        return [
            $item->DESCRICAO_ERP,
            $item->NOME_EMPRESA,
            $item->CNPJ." ",
            is_null($item->DATA_VENDA) ? null : date_format(date_create($item->DATA_VENDA), 'd/m/Y'),
            is_null($item->DATA_PREVISAO) ? null : date_format(date_create($item->DATA_PREVISAO), 'd/m/Y'),
            is_null($item->DATA_PAGAMENTO) ? null : date_format(date_create($item->DATA_PAGAMENTO), 'd/m/Y'),
            $item->ADQUIRENTE,
            $item->BANDEIRA,
            $item->MODALIDADE,
            $item->NSU." ",
            $item->AUTORIZACAO." ",
            $item->TID." ",
            $item->CARTAO." ",
            round(($item->VALOR_BRUTO ?? 0), 2),
            round(($item->TAXA_PERCENTUAL ?? 0), 2),
            round((($item->VALOR_TAXA ?? 0) * -1), 2),
            0,
            round(($item->VALOR_LIQUIDO ?? 0), 2),
            $item->PARCELA,
            $item->TOTAL_PARCELAS,
            $item->HORA,
            $item->ESTABELECIMENTO." ",
            $item->TERMINAL." ",
            $item->BANCO,
            $item->AGENCIA." ",
            $item->CONTA." ",
            $item->OBSERVACOES,
            $item->PRODUTO,
            $item->MEIOCAPTURA,
            $item->STATUS_CONCILIACAO,
            $item->DIVERGENCIA,
            $item->STATUS_FINANCEIRO,
            $item->JUSTIFICATIVA
        ];
    }

    public function query()
    {
        return RecebimentosSubFilter::subfilter($this->filters, $this->subfilters)
            ->getQuery()
            ->orderBy('DATA_PAGAMENTO');
    }
}
