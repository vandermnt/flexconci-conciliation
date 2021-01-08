<?php

namespace App\Http\Middleware;
use App\VendasErpModel;
use App\StatusConciliacaoModel;

use Closure;

class VerificaVendasParaDesconciliacao
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
        $id_erp = $request->input('id_erp');
        $status_manual = StatusConciliacaoModel::manual()->first()->CODIGO;


        if(count($id_erp) !== 1) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Para realizar a desconciliação selecione apenas uma venda ERP.'
            ], 400);
        }

        $venda_erp = VendasErpModel::select('CODIGO')
            ->where('CODIGO', $id_erp[0])
            ->where('COD_CLIENTE', session('codigologin'))
            ->where('COD_STATUS_CONCILIACAO', $status_manual)
            ->first();

        if(is_null($venda_erp)) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'A venda não foi encontrada ou ainda não foi conciliada.',
            ], 400);
        }

        return $next($request);
    }
}
