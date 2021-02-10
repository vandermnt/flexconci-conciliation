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

class VendasErpExport implements FromQuery, WithStrictNullComparison, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;
    
    protected $filters;
    protected $subfilters;
    protected $dynamicHeaders;

    public function __construct($filters, $subfilters, $dynamicHeaders = null) {
        $this->filters = $filters;
        $this->subfilters = $subfilters;
        $this->dynamicHeaders = $dynamicHeaders ?? (object) array();
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
            $venda->VALOR_TAXA ?? 0,
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
            ->getQuery()
            ->orderBy('DATA_VENDA');
    }
}

