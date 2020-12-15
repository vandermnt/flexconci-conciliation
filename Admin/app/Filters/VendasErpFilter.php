<?php

namespace App\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\VendasErpModel;
use App\GruposClientesModel;
use App\Filters\BaseFilter;

class VendasErpFilter extends BaseFilter {
  protected $query = null;
  protected $whiteList = [
    'cliente_id',
    'data_inicial',
    'data_final',
    'grupos_clientes',
    'adquirentes',
    'bandeiras',
    'modalidades',
    'id_erp',
    'status_conciliacao',
  ];

  public static function filter(Request $filters) {
    $vendasErpFilter = app(VendasErpFilter::class);
    return $vendasErpFilter->apply($filters);
  }

  public function apply(Request $filters) {
    $filters = $filters->only($whiteList);
    $filters = Arr::where($filters, function($value, $key) {
      return boolval($value);
    });

    $datas = [
      ($filters['data_inicial'] ?? date('Y-m-d')),
      ($filters['data_final'] ?? date('Y-m-d'))
    ];

    $this->query = VendasErpModel::select(
        [
          'vendas_erp.CODIGO as ID_ERP',
          'grupos_clientes.NOME_EMPRESA',
          'grupos_clientes.CNPJ',
          'vendas_erp.DATA_VENDA',
          'vendas_erp.DATA_VENCIMENTO',
          'adquirentes.ADQUIRENTE',
          'adquirentes.IMAGEM as ADQUIRENTE_IMAGEM',
          'bandeira.BANDEIRA',
          'bandeira.IMAGEM as BANDEIRA_IMAGEM',
          'modalidade.DESCRICAO as MODALIDADE',
          'vendas_erp.NSU',
          'vendas_erp.CODIGO_AUTORIZACAO',
          'vendas_erp.TID',
          'vendas_erp.TOTAL_VENDA',
          'vendas_erp.TAXA',
          DB::raw('
            (`vendas_erp`.`TOTAL_VENDA` - `vendas_erp`.`VALOR_LIQUIDO_PARCELA`)
              as `VALOR_TAXA`'),
          'vendas_erp.VALOR_LIQUIDO_PARCELA',
          'vendas_erp.PARCELA',
          'vendas_erp.TOTAL_PARCELAS',
          'vendas_erp.BANCO',
          'vendas_erp.AGENCIA',
          'vendas_erp.CONTA_CORRENTE',
          'produto_web.PRODUTO_WEB as PRODUTO',
          'meio_captura.DESCRICAO as MEIOCAPTURA',
          'status_conciliacao.STATUS_CONCILIACAO',
          'status_financeiro.STATUS_FINANCEIRO',
          'vendas_erp.JUSTIFICATIVA',
          'vendas_erp.CAMPO_ADICIONAL1 as CAMPO1',
          'vendas_erp.CAMPO_ADICIONAL2 as CAMPO2',
          'vendas_erp.CAMPO_ADICIONAL3 as CAMPO3',
          'vendas_erp.DATA_IMPORTACAO',
          'vendas_erp.HORA_IMPORTACAO',
          'vendas_erp.DATA_CONCILIACAO',
          'vendas_erp.HORA_CONCILIACAO',
        ]
      )
      ->leftJoinSub(GruposClientesModel::groupBy('COD_CLIENTE'), 'grupos_clientes', function($join) {
        $join->on('grupos_clientes.COD_CLIENTE', 'vendas_erp.COD_CLIENTE');
      })
      ->leftJoin('adquirentes', 'adquirentes.CODIGO', 'vendas_erp.COD_OPERADORA')
      ->leftJoin('bandeira', 'bandeira.CODIGO', 'vendas_erp.COD_BANDEIRA')
      ->leftJoin('modalidade', 'modalidade.CODIGO', 'vendas_erp.COD_MODALIDADE')
      ->leftJoin('produto_web', 'produto_web.CODIGO', 'vendas_erp.COD_PRODUTO')
      ->leftJoin('meio_captura', 'vendas_erp.COD_MEIO_CAPTURA', 'meio_captura.CODIGO')
      ->leftJoin('status_conciliacao', 'vendas_erp.COD_STATUS_CONCILIACAO', 'status_conciliacao.CODIGO')
      ->leftJoin('status_financeiro', 'vendas_erp.COD_STATUS_FINANCEIRO', 'status_financeiro.CODIGO')
      ->where('vendas_erp.COD_CLIENTE', $filters['cliente_id'])
      ->whereBetween('DATA_VENDA', $datas)
      ->orderBy('vendas_erp.DATA_VENDA');
    
    if(Arr::has($filters, 'id_erp')) {
      $this->query->whereIn('vendas_erp.CODIGO', $filters['id_erp']);
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

    return $this;
  }

  public function getQuery() {
    return $this->query;
  }
}