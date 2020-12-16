<?php

namespace App\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\VendasModel;
use App\GruposClientesModel;
use App\Filters\BaseFilter;

class VendasFilter extends BaseFilter {
  protected $query = null;
  protected $whiteList = [
    'cliente_id',
    'data_inicial',
    'data_final',
    'grupos_clientes',
    'adquirentes',
    'bandeiras',
    'modalidades',
    'meios_captura',
    'id_erp',
    'status_conciliacao',
    'status_financeiro',
  ];

  public static function filter($filters) {
    $vendasFilter = app(VendasFilter::class);
    return $vendasFilter->apply($filters);
  }

  public function apply($filters) {
    $filters = Arr::only($filters, $this->whiteList);
    $filters = Arr::where($filters, function($value, $key) {
      return boolval($value);
    });

    $datas = [
      ($filters['data_inicial'] ?? date('Y-m-d')),
      ($filters['data_final'] ?? date('Y-m-d'))
    ];

    $this->query = VendasModel::select(
        [
          'vendas.CODIGO as ID',
          'grupos_clientes.NOME_EMPRESA',
          'grupos_clientes.CNPJ',
          'vendas.DATA_VENDA',
          'vendas.DATA_PREVISTA_PAGTO as DATA_PREVISAO',
          'adquirentes.ADQUIRENTE',
          'adquirentes.IMAGEM as ADQUIRENTE_IMAGEM',
          'bandeira.BANDEIRA',
          'bandeira.IMAGEM as BANDEIRA_IMAGEM',
          'modalidade.DESCRICAO as MODALIDADE',
          'vendas.NSU',
          'vendas.AUTORIZACAO',
          'vendas.CARTAO',
          'vendas.TID',
          'vendas.VALOR_BRUTO',
          'vendas.PERCENTUAL_TAXA',
          DB::raw('
            (`vendas`.`VALOR_BRUTO` - `vendas`.`VALOR_LIQUIDO`)
              as `VALOR_TAXA`'),
          'vendas.VALOR_LIQUIDO',
          'vendas.PARCELA',
          'vendas.TOTAL_PARCELAS',
          'vendas.HORA_TRANSACAO',
          'vendas.ESTABELECIMENTO',
          'lista_bancos.BANCO',
          'lista_bancos.IMAGEM_LINK as BANCO_IMAGEM',
          'vendas.AGENCIA',
          'vendas.CONTA',
          'vendas.OBSERVACOES',
          'produto_web.PRODUTO_WEB as PRODUTO',
          'meio_captura.DESCRICAO as MEIOCAPTURA',
          'status_conciliacao.STATUS_CONCILIACAO',
          'status_financeiro.STATUS_FINANCEIRO',
          'vendas.JUSTIFICATIVA'
        ]
      )
      ->leftJoinSub(GruposClientesModel::groupBy('COD_CLIENTE'), 'grupos_clientes', function($join) {
        $join->on('grupos_clientes.COD_CLIENTE', 'vendas.COD_CLIENTE');
      })
      ->leftJoin('adquirentes', 'adquirentes.CODIGO', 'vendas.ADQID')
      ->leftJoin('bandeira', 'bandeira.CODIGO', 'vendas.COD_BANDEIRA')
      ->leftJoin('modalidade', 'modalidade.CODIGO', 'vendas.CODIGO_MODALIDADE')
      ->leftJoin('lista_bancos', 'lista_bancos.CODIGO', 'vendas.BANCO')
      ->leftJoin('produto_web', 'produto_web.CODIGO', 'vendas.COD_PRODUTO')
      ->leftJoin('meio_captura', 'vendas.COD_MEIO_CAPTURA', 'meio_captura.CODIGO')
      ->leftJoin('status_conciliacao', 'vendas.COD_STATUS_CONCILIACAO', 'status_conciliacao.CODIGO')
      ->leftJoin('status_financeiro', 'vendas.COD_STATUS_FINANCEIRO', 'status_financeiro.CODIGO')
      ->where('vendas.COD_CLIENTE', $filters['cliente_id'])
      ->whereBetween('vendas.DATA_VENDA', $datas)
      ->orderBy('vendas.DATA_VENDA');
    
    if(Arr::has($filters, 'id_erp')) {
      $this->query->whereIn('vendas.CODIGO', $filters['id_erp']);
    }
    if(Arr::has($filters, 'grupos_clientes')) {
      $this->query->whereIn('grupos_clientes.CODIGO', $filters['grupos_clientes']);
    }
    if(Arr::has($filters, 'adquirentes')) {
      $this->query->whereIn('adquirentes.CODIGO', $filters['adquirentes']);
    }
    if(Arr::has($filters, 'bandeiras')) {
      $this->query->whereIn('bandeira.CODIGO', $filters['bandeiras']);
    }
    if(Arr::has($filters, 'modalidades')) {
      $this->query->whereIn('modalidade.CODIGO', $filters['modalidades']);
    }
    if(Arr::has($filters, 'status_conciliacao')) {
      $this->query->whereIn('status_conciliacao.CODIGO', $filters['status_conciliacao']);
    }
    if(Arr::has($filters, 'status_financeiro')) {
      $this->query->whereIn('status_financeiro.CODIGO', $filters['status_financeiro']);
    }

    return $this;
  }

  public function getQuery() {
    return $this->query;
  }
}