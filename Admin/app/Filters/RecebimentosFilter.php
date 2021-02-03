<?php

namespace App\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Filters\BaseFilter;

class RecebimentosFilter extends BaseFilter {
  protected $query = null;
  protected $whiteList = [
    'id',
    'cliente_id',
    'data_inicial',
    'data_final',
    'grupos_clientes',
    'adquirentes',
    'domicilios_bancarios'
  ];

  public static function filter($filters) {
    $recebimentosFilter = app(RecebimentosFilter::class);
    return $recebimentosFilter->apply($filters);
  }

  public function apply($filters) {
    $filters = Arr::only($filters, $this->whiteList);
    $filters = Arr::where($filters, function($value, $key) {
      return boolval($value);
    });

    $this->query = DB::table('pagamentos_operadoras')
      ->select([
        'pagamentos_operadoras.CODIGO as ID',
        'grupos_clientes.NOME_EMPRESA',
        'grupos_clientes.CNPJ',
        'pagamentos_operadoras.DATA_VENDA',
        'pagamentos_operadoras.DATA_PREV_PAG_ORIGINAL as DATA_PREVISAO',
        'pagamentos_operadoras.DATA_PAGAMENTO',
        'adquirentes.ADQUIRENTE',
        'adquirentes.IMAGEM as ADQUIRENTE_IMAGEM',
        'bandeira.BANDEIRA',
        'bandeira.IMAGEM as BANDEIRA_IMAGEM',
        'modalidade.DESCRICAO as MODALIDADE',
        'pagamentos_operadoras.NSU',
        'pagamentos_operadoras.CODIGO_AUTORIZACAO as AUTORIZACAO',
        DB::raw('null as TID'),
        'pagamentos_operadoras.NUMERO_CARTAO as CARTAO',
        'pagamentos_operadoras.VALOR_BRUTO',
        'pagamentos_operadoras.TAXA_PERCENTUAL',
        DB::raw('
          (`pagamentos_operadoras`.`VALOR_BRUTO` - `pagamentos_operadoras`.`VALOR_LIQUIDO`)
            as `VALOR_TAXA`'),
        'pagamentos_operadoras.VALOR_LIQUIDO',
        'pagamentos_operadoras.PARCELA',
        'pagamentos_operadoras.TOTAL_PARCELAS',
        DB::raw('null as HORA'),
        DB::raw('null as ESTABELECIMENTO'),
        'lista_bancos.NOME_WEB as BANCO',
        'lista_bancos.IMAGEM_LINK as BANCO_IMAGEM',
        'pagamentos_operadoras.AGENCIA',
        'pagamentos_operadoras.CONTA',
        DB::raw('null as OBSERVACOES'),
        DB::raw('null as PRODUTO'),
        'meio_captura.DESCRICAO as MEIOCAPTURA',
        'status_conciliacao.STATUS_CONCILIACAO',
        DB::raw('null as DIVERGENCIA'),
        'status_financeiro.STATUS_FINANCEIRO',
        DB::raw('null as JUSTIFICATIVA'),
      ])
        ->leftJoin('grupos_clientes', 'grupos_clientes.CODIGO', 'COD_GRUPO_CLIENTE')
        ->leftJoin('adquirentes', 'adquirentes.CODIGO', 'COD_ADQUIRENTE')
        ->leftJoin('bandeira', 'bandeira.CODIGO', 'COD_BANDEIRA')
        ->leftJoin('modalidade', 'modalidade.CODIGO', 'COD_FORMA_PAGAMENTO')
        ->leftJoin('lista_bancos', 'lista_bancos.CODIGO', 'COD_BANCO')
        ->leftJoin('meio_captura', 'meio_captura.CODIGO', 'COD_MEIO_CAPTURA')
        ->leftJoin('status_conciliacao', 'status_conciliacao.CODIGO', 'COD_STATUS')
        ->leftJoin('status_financeiro', 'status_financeiro.CODIGO', 'COD_STATUS_FINANCEIRO')
        ->where('pagamentos_operadoras.COD_CLIENTE', $filters['cliente_id']);

    if(Arr::has($filters, 'id')) {
      $this->query->whereIn('pagamentos_operadoras.CODIGO', $filters['id']);
    }
    if(Arr::has($filters, ['data_inicial', 'data_final'])) {
      $this->query->whereBetween('pagamentos_operadoras.DATA_PAGAMENTO', [
        $filters['data_inicial'],
        $filters['data_final']
      ]);
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
    if(Arr::has($filters, 'domicilios_bancarios')) {
      $this->query->whereIn('lista_bancos.CODIGO', function($query) use ($filters) {
        $query->select('domicilio_cliente.COD_BANCO')
          ->from('domicilio_cliente')
          ->whereIn('domicilio_cliente.CODIGO', $filters['domicilios_bancarios']);
      });
      $this->query->whereIn('pagamentos_operadoras.AGENCIA', function($query) use ($filters) {
        $query->select('domicilio_cliente.AGENCIA')
          ->from('domicilio_cliente')
          ->whereIn('domicilio_cliente.CODIGO', $filters['domicilios_bancarios']);
      });
      $this->query->whereIn('pagamentos_operadoras.CONTA', function($query) use ($filters) {
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