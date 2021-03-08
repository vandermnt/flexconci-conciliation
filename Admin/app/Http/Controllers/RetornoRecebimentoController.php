<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\VendasErpModel;
use App\PagamentoOperadoraModel;
use App\StatusConciliacaoModel;

class RetornoRecebimentoController extends Controller
{
    public function index(Request $request) {
        $datas = [
            $request->input('data-inicial'),
            $request->input('data-final')
        ];
        $cod_cliente = session('codigologin');
        $cod_vendas = [];
        $cod_pagamentos = [];

        try {
            $status_conciliada = StatusConciliacaoModel::conciliada()->first()->CODIGO;
            $status_divergente = StatusConciliacaoModel::divergente()->first()->CODIGO;

            $raw_query = "select auxiliar.conciflexbaixa('public',?,?,'B',?,?,?) as retorno";
            $vendas_erp_query = VendasErpModel::select([
                'vendas_erp.CODIGO as COD_VENDA_ERP',
                'vendas_erp.DESCRICAO_TIPO_PRODUTO as ID_ERP',
                'pagamentos_operadoras.CODIGO as COD_PAGAMENTO',
                'pagamentos_operadoras.DATA_PAGAMENTO',
                'pagamentos_operadoras.VALOR_TAXA',
                'pagamentos_operadoras.VALOR_BRUTO',
                'pagamentos_operadoras.COD_TIPO_PAGAMENTO as TIPO_PAGAMENTO',
            ])
                ->leftJoin('vendas', 'vendas.CODIGO', 'vendas_erp.COD_VENDAS_OPERADORAS')
                ->leftJoin('pagamentos_operadoras', 'vendas.COD_PAGAMENTO', 'pagamentos_operadoras.CODIGO')
                ->where('vendas_erp.COD_CLIENTE', session('codigologin'))
                ->whereNotNull('vendas_erp.COD_VENDAS_OPERADORAS')
                ->whereNotNull('vendas.COD_PAGAMENTO')
                ->where(function ($query) {
                    $query->whereNull('vendas_erp.RETORNO_ERP_BAIXA')
                        ->orWhere('vendas_erp.RETORNO_ERP_BAIXA', 'N');
                })
                ->whereIn('vendas_erp.COD_STATUS_CONCILIACAO', [$status_conciliada, $status_divergente])
                ->where('vendas_erp.DIVERGENCIA', 'not like', '%CONTA%')
                ->whereBetween('vendas_erp.DATA_VENDA', $datas);

            $total = $vendas_erp_query->count();
            foreach($vendas_erp_query->cursor() as $venda_erp) {
                $params = [
                    $venda_erp->ID_ERP,
                    $venda_erp->DATA_PAGAMENTO,
                    $venda_erp->VALOR_TAXA,
                    $venda_erp->VALOR_BRUTO,
                    $venda_erp->TIPO_PAGAMENTO == 1 ? 'N' : 'S',
                ];

                $retorno_erp_bool = collect(
                    DB::connection('pgsql_conciflex_seta')
                        ->select($raw_query, $params)
                )
                    ->first()
                    ->retorno;

                if($retorno_erp_bool) {
                    array_push($cod_vendas, $venda_erp->COD_VENDA_ERP);
                    array_push($cod_pagamentos, $venda_erp->COD_PAGAMENTO);
                }
            }

            VendasErpModel::whereIn('CODIGO', $cod_vendas)
                ->update([
                    'RETORNO_ERP_BAIXA' => 'S'
                ]);

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => 'Retorno Recebimento ERP realizado com sucesso.',
                'vendas' => $cod_vendas,
                'pagamentos' => $cod_pagamentos,
                'total' => $total,
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Não foi possível realizar o Retorno Recebimento ERP',
            ], 400);
        }
    }
}
