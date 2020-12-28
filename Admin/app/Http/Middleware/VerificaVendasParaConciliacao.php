<?php

namespace App\Http\Middleware;

use Closure;
use App\VendasErpModel;
use App\VendasModel;
use App\StatusConciliacaoModel;

class VerificaVendasParaConciliacao
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id_operadoras = $request->input('id_operadora');
        $id_erp = $request->input('id_erp');
        $status_nao_conciliada = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;
        
        if(count($id_erp) != 1 || count($id_operadoras) != 1) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'A conciliação deve ser realizada entre uma venda ERP e uma venda operadora',
            ], 400);
        }
        
        $venda_erp = VendasErpModel::select('CODIGO')
            ->where('CODIGO', $id_erp[0])
            ->where('COD_CLIENTE', session('codigologin'))
            ->where('COD_STATUS_CONCILIACAO', $status_nao_conciliada)
            ->first();
        $venda_operadora = VendasModel::select('CODIGO')
            ->where('CODIGO', $id_operadoras[0])
            ->where('COD_CLIENTE', session('codigologin'))
            ->where('COD_STATUS_CONCILIACAO', $status_nao_conciliada)
            ->first();

        if(is_null($venda_erp) || is_null($venda_operadora)) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'As vendas não foram encontradas ou já estão conciliadas.',
            ], 400);
        }

        return $next($request);
    }
}
