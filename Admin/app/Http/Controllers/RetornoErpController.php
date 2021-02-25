<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\VendasErpModel;
use App\StatusConciliacaoModel;

class RetornoErpController extends Controller
{
    public function index(Request $request) {
        $cod_cliente = session('codigologin');
        $datas = [
            $request->input('data-inicial'),
            $request->input('data-final')
        ];
        $cod_vendas = [];
        
        try {
            $status_conciliada = StatusConciliacaoModel::conciliada()->first()->CODIGO;
            $status_divergente = StatusConciliacaoModel::divergente()->first()->CODIGO;

            $raw_query = "select auxiliar.conciflexatualiza('public', ?, ?, ?, 'C', ?) as retorno";
            $vendas_erp_query = VendasErpModel::select([
                'vendas_erp.CODIGO as COD_VENDA_ERP',
                'vendas_erp.DESCRICAO_TIPO_PRODUTO as ID_ERP',
                'vendas_erp.DATA_VENDA',
                'vendas.DATA_PREVISTA_PAGTO as DATA_PREVISAO',
                'vendas.VALOR_TAXA',
                'vendas.VALOR_BRUTO',
            ])
                ->leftJoin('vendas', 'vendas.CODIGO', 'vendas_erp.COD_VENDAS_OPERADORAS')
                ->where('vendas_erp.COD_CLIENTE', $cod_cliente)
                ->whereIn('vendas_erp.COD_STATUS_CONCILIACAO', [$status_conciliada, $status_divergente])
                ->whereNotNull('vendas_erp.COD_VENDAS_OPERADORAS')
                ->where(function ($query) {
                    $query->whereNull('vendas_erp.RETORNO_ERP')
                        ->orWhere('vendas_erp.RETORNO_ERP', 'N'); 
                })
                ->whereBetween('vendas_erp.DATA_VENDA', $datas);

            $total = $vendas_erp_query->count();
            foreach($vendas_erp_query->cursor() as $venda_erp) {
                $params = [
                    $venda_erp->ID_ERP,
                    $venda_erp->DATA_PREVISAO,
                    $venda_erp->VALOR_TAXA,
                    $venda_erp->VALOR_BRUTO
                ];
    
                $retorno_erp_bool = collect(
                    DB::connection('pgsql_conciflex_seta')
                        ->select($raw_query, $params)
                )
                    ->first()
                    ->retorno;
    
                if($retorno_erp_bool) {
                    array_push($cod_vendas, $venda_erp->COD_VENDA_ERP);
                }
            }

            // VendasErpModel::whereIn('CODIGO', $venda->COD_VENDA_ERP)
            //     ->update([
            //         'RETORNO_ERP' => 'S'
            //     ]);

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => 'Retorno ERP realizado com sucesso.',
                'vendas' => $cod_vendas,
                'total' => $total
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Um problema ocorreu. Não foi possível realizar o retorno ERP.',
            ], 500);
        }
    }
}
