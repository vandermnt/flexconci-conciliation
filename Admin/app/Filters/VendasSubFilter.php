<?php

namespace App\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Filters\BaseSubFilter;

class VendasSubFilter extends BaseSubFilter {
  protected $query = null;
  protected $whiteList = [
    'ID',
    'NOME_EMPRESA',
    'DATA_VENDA',
    'DATA_PREVISAO',
    'ADQUIRENTE',
    'BANDEIRA',
    'MODALIDADE',
    'NSU',
    'AUTORIZACAO',
    'CARTAO',
    'TID',
    'VALOR_BRUTO',
    'PERCENTUAL_TAXA',
    'VALOR_TAXA',
    'VALOR_LIQUIDO',
    'PARCELA',
    'TOTAL_PARCELAS',
    'HORA_TRANSACAO',
    'ESTABELECIMENTO',
    'BANCO',
    'AGENCIA',
    'CONTA',
    'OBSERVACOES',
    'PRODUTO',
    'MEIOCAPTURA',
    'STATUS_CONCILIACAO',
    'STATUS_FINANCEIRO',
    'JUSTIFICATIVA'
  ];

  public static function subfilter($filters, $subfilters) {
    $instance = app(VendasSubFilter::class);
    return $instance->apply($filters, $subfilters);
  }

  public function apply($filters, $subfilters) {
    $subfilters = Arr::only($subfilters, $this->whiteList);
    $subfilters = Arr::where($subfilters, function($value, $key) {
      return boolval($value);
    });

    $filterQuery = $this->getFilterQuery($filters);

    $this->query = DB::table('vendas_sub')
      ->from(
        DB::raw('('.$filterQuery->toSql().') as vendas_sub')
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