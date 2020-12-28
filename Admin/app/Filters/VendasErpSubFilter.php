<?php

namespace App\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Filters\BaseSubFilter;

class VendasErpSubFilter extends BaseSubFilter {
  protected $query = null;
  protected $whiteList = [
    'ID_ERP',
    'NOME_EMPRESA',
    'CNPJ',
    'DATA_VENDA',
    'DATA_VENCIMENTO',
    'ADQUIRENTE',
    'BANDEIRA',
    'MODALIDADE',
    'NSU',
    'CODIGO_AUTORIZACAO',
    'TID',
    'TOTAL_VENDA',
    'TAXA',
    'VALOR_TAXA',
    'VALOR_LIQUIDO_PARCELA',
    'PARCELA',
    'TOTAL_PARCELAS',
    'BANCO',
    'AGENCIA',
    'PRODUTO',
    'CONTA_CORRENTE',
    'MEIOCAPTURA',
    'STATUS_CONCILIACAO',
    'STATUS_FINANCEIRO',
    'JUSTIFICATIVA',
    'CAMPO1',
    'CAMPO2',
    'CAMPO3',
    'DATA_IMPORTACAO',
    'HORA_IMPORTACAO',
    'DATA_CONCILIACAO',
    'HORA_CONCILIACAO',
  ];

  public static function subfilter($filters, $subfilters) {
    $instance = app(VendasErpSubFilter::class);
    return $instance->apply($filters, $subfilters);
  }

  public function apply($filters, $subfilters) {
    $subfilters = Arr::only($subfilters, $this->whiteList);
    $subfilters = Arr::where($subfilters, function($value, $key) {
      return boolval($value);
    });

    $filterQuery = $this->getFilterQuery($filters);

    $this->query = DB::table('vendas_erp_sub')
      ->from(
        DB::raw('('.$filterQuery->toSql().') as vendas_erp_sub')
      )
      ->mergeBindings($filterQuery->getQuery());

    foreach($subfilters as $subfilter => $value) {
      $this->query->where($subfilter, 'like', '%'.$value.'%');
    }

    return $this;
  }

  public function getQuery() {
    return $this->query;
  }
}