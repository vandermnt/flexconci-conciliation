<?php

namespace App\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Filters\BaseFilter;

class RecebimentosFuturosFilter extends BaseFilter {
  protected $query = null;
  protected $whiteList = [
    'id',
    'cliente_id',
    'data_inicial',
    'data_final',
    'grupos_clientes',
    'adquirentes',
    'bandeiras',
    'modalidades',
    'domicilios_bancarios'
  ];

  public static function filter($filters) {
    $recebimentosFilter = app(RecebimentosFuturosFilter::class);
    return $recebimentosFilter->apply($filters);
  }

  public function apply($filters) {
    $filters = Arr::only($filters, $this->whiteList);
    $filters = Arr::where($filters, function($value, $key) {
      return boolval($value);
    });

    $this->query = DB::table('vendas')
      ->select(
        [
          'vendas.CODIGO as ID',
          'vendas_erp.DESCRICAO_TIPO_PRODUTO as DESCRICAO_ERP',
          'vendas.EMPRESA as NOME_EMPRESA',
          'vendas.CNPJ',
          'vendas.DATA_VENDA',
          'vendas.DATA_PREVISTA_PAGTO as DATA_PREVISAO',
          'vendas.DATA_PAGAMENTO',
          'adquirentes.ADQUIRENTE',
          'adquirentes.IMAGEM as ADQUIRENTE_IMAGEM',
          'bandeira.BANDEIRA',
          'bandeira.IMAGEM as BANDEIRA_IMAGEM',
          'modalidade.DESCRICAO as MODALIDADE',
          'vendas.NSU',
          'vendas.AUTORIZACAO',
          'vendas.TID',
          'vendas.CARTAO',
          'vendas.VALOR_BRUTO',
          'vendas.PERCENTUAL_TAXA as TAXA_PERCENTUAL',
          DB::raw('
            (`vendas`.`VALOR_BRUTO` - `vendas`.`VALOR_LIQUIDO`)
              as `VALOR_TAXA`'),
          DB::raw('null as TAXA_ANTECIPACAO_PERCENTUAL'),
          'vendas.VALOR_LIQUIDO',
          'vendas.PARCELA',
          'vendas.TOTAL_PARCELAS',
          'vendas.HORA_TRANSACAO',
          'vendas.ESTABELECIMENTO',
          'vendas.TERMINAL',
          'lista_bancos.BANCO',
          'lista_bancos.IMAGEM_LINK as BANCO_IMAGEM',
          'vendas.AGENCIA',
          'vendas.CONTA',
          'vendas.OBSERVACOES',
          'produto_web.PRODUTO_WEB as PRODUTO',
          'meio_captura.DESCRICAO as MEIOCAPTURA',
          'status_conciliacao.STATUS_CONCILIACAO',
          'status_conciliacao.IMAGEM_URL as STATUS_CONCILIACAO_IMAGEM',
          'vendas.DIVERGENCIA',
          'status_financeiro.STATUS_FINANCEIRO',
          'vendas.JUSTIFICATIVA'
        ])
        ->leftJoin('vendas_erp', 'vendas_erp.CODIGO', 'vendas.COD_VENDA_ERP')
        ->leftJoin('adquirentes', 'adquirentes.CODIGO', 'vendas.ADQID')
        ->leftJoin('bandeira', 'bandeira.CODIGO', 'vendas.COD_BANDEIRA')
        ->leftJoin('modalidade', 'modalidade.CODIGO', 'vendas.CODIGO_MODALIDADE')
        ->leftJoin('lista_bancos', 'lista_bancos.CODIGO', 'vendas.BANCO')
        ->leftJoin('produto_web', 'produto_web.CODIGO', 'vendas.COD_PRODUTO')
        ->leftJoin('meio_captura', 'vendas.COD_MEIO_CAPTURA', 'meio_captura.CODIGO')
        ->leftJoin('status_conciliacao', 'vendas.COD_STATUS_CONCILIACAO', 'status_conciliacao.CODIGO')
        ->leftJoin('status_financeiro', 'vendas.COD_STATUS_FINANCEIRO', 'status_financeiro.CODIGO')
        ->where('vendas.COD_CLIENTE', $filters['cliente_id']);

    if(Arr::has($filters, 'id')) {
      $this->query->whereIn('vendas.CODIGO', $filters['id']);
    }
    if(Arr::has($filters, ['data_final'])) {
      $filters['data_inicial'] = date('Y-m-d');
      $this->query->whereBetween('vendas.DATA_PREVISTA_PAGTO', [
        $filters['data_inicial'],
        $filters['data_final']
      ]);
    }
    if(Arr::has($filters, 'grupos_clientes')) {
      $this->query->whereIn('vendas.COD_GRUPO_CLIENTE', $filters['grupos_clientes']);
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
    if(Arr::has($filters, 'domicilios_bancarios')) {
      $this->query->whereIn('lista_bancos.CODIGO', function($query) use ($filters) {
        $query->select('domicilio_cliente.COD_BANCO')
          ->from('domicilio_cliente')
          ->whereIn('domicilio_cliente.CODIGO', $filters['domicilios_bancarios']);
      });
      $this->query->whereIn('vendas.AGENCIA', function($query) use ($filters) {
        $query->select('domicilio_cliente.AGENCIA')
          ->from('domicilio_cliente')
          ->whereIn('domicilio_cliente.CODIGO', $filters['domicilios_bancarios']);
      });
      $this->query->whereIn('vendas.CONTA', function($query) use ($filters) {
        $query->select('domicilio_cliente.CONTA')
          ->from('domicilio_cliente')
          ->whereIn('domicilio_cliente.CODIGO', $filters['domicilios_bancarios']);
      });
    }

    return $this;
  }

  public function getQuery() {
    return $this->query;
  }
}
