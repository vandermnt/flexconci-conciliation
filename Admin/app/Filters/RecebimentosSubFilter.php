<?php

namespace App\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Filters\BaseSubFilter;

class RecebimentosSubFilter extends BaseSubFilter {
  protected $query = null;
  protected $whiteList = [
    'ID',
    'NOME_EMPRESA',
    'CNPJ',
    'DATA_VENDA',
    'DATA_PREVISAO',
    'DATA_PAGAMENTO',
    'ADQUIRENTE',
    'BANDEIRA',
    'MODALIDADE',
    'NSU',
    'AUTORIZACAO',
    'TID',
    'CARTAO',
    'VALOR_BRUTO',
    'TAXA_PERCENTUAL',
    'VALOR_TAXA',
    'VALOR_LIQUIDO',
    'PARCELA',
    'TOTAL_PARCELAS',
    'HORA',
    'ESTABELECIMENTO',
    'BANCO',
    'AGENCIA',
    'CONTA',
    'OBSERVACOES',
    'PRODUTO',
    'MEIOCAPTURA',
    'STATUS_CONCILIACAO',
    'DIVERGENCIA',
    'STATUS_FINANCEIRO',
    'JUSTIFICATIVA'
  ];
  protected $numericFilters = [
    'VALOR_BRUTO',
    'TAXA_PERCENTUAL',
    'VALOR_TAXA',
    'VALOR_LIQUIDO',
    'PARCELA',
    'TOTAL_PARCELAS',
  ];

  public static function subfilter($filters, $subfilters) {
    $instance = app(RecebimentosSubFilter::class);
    return $instance->apply($filters, $subfilters);
  }

  public function apply($filters, $subfilters) {
    $subfilters = Arr::only($subfilters, $this->whiteList);
    $subfilters = Arr::where($subfilters, function($value, $key) {
      return boolval($value);
    });

    $filterQuery = $this->getFilterQuery($filters);

    $this->query = DB::table('recebimentos_sub')
      ->select('*')
      ->from(
        DB::raw('('.$filterQuery->toSql().') as recebimentos_sub')
      )
      ->mergeBindings($filterQuery);

      foreach($subfilters as $subfilter => $value) {
        $this->buildWhereClause($subfilter, $value);
      }

    return $this;
  }

  public function getQuery() {
    return $this->query;
  }
}