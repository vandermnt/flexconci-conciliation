<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

use App\ClienteModel;
use App\GruposClientesModel;
use App\StatusConciliacaoModel;
use App\Filters\VendasErpFilter;
use App\Filters\VendasFilter;

class ConciliacaoAutomaticaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $erp = ClienteModel::select('erp.ERP')
            ->leftJoin('erp', 'clientes.COD_ERP', 'erp.CODIGO')
            ->where('clientes.CODIGO', session('codigologin'))
            ->first()
            ->ERP;

        $empresas = GruposClientesModel::where('COD_CLIENTE', session('codigologin'))
            ->orderBy('NOME_EMPRESA')
            ->get();
            
        $status_conciliacao = StatusConciliacaoModel::orderBy('STATUS_CONCILIACAO')
            ->get();

        return view('conciliacao.conciliacao-automatica')
            ->with([
                'erp' => $erp,
                'empresas' => $empresas,
                'status_conciliacao' => $status_conciliacao
            ]);
    }

    public function filter(Request $request) {
        $quantidadesPermitidas = [5, 10, 20, 50, 100, 200];
        $filters = $request->all();
        $filters['cliente_id'] = session('codigologin');
        $filters_operadoras = $request->except(['status_conciliacao']);
        $filters_operadoras['cliente_id'] = session('codigologin');

        $por_pagina_erp = $request->input('por_pagina_erp', 5);
        $por_pagina_erp = in_array($por_pagina_erp, $quantidadesPermitidas) ? $por_pagina_erp : 5;
        $por_pagina_operadoras = $request->input('por_pagina_operadoras', 5);
        $por_pagina_operadoras = in_array($por_pagina_operadoras, $quantidadesPermitidas) ? $por_pagina_operadoras : 5;

        try {
            $status_conciliada = StatusConciliacaoModel::conciliada()->first()->CODIGO;
            $status_nao_conciliada = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;
            $status_justificada = StatusConciliacaoModel::justificada()->first()->CODIGO;
            $status_divergente = StatusConciliacaoModel::divergente()->first()->CODIGO;
            $status_manual = StatusConciliacaoModel::manual()->first()->CODIGO;

            $erp_query = VendasErpFilter::filter($filters)->getQuery();
            $operadoras_query = VendasFilter::filter($filters_operadoras)
                ->getQuery()
                ->where('COD_STATUS_CONCILIACAO', $status_nao_conciliada);

            $erp_totais = [
                'TOTAL_BRUTO' => $erp_query->sum('TOTAL_VENDA'),
                'TOTAL_LIQUIDO' => $erp_query->sum('VALOR_LIQUIDO_PARCELA'),
            ];
            $erp_totais['TOTAL_TAXA'] = $erp_totais['TOTAL_BRUTO'] - $erp_totais['TOTAL_LIQUIDO'];

            $operadoras_totais = [
                'TOTAL_BRUTO' => $operadoras_query->sum('VALOR_BRUTO'),
                'TOTAL_LIQUIDO' => $operadoras_query->sum('VALOR_LIQUIDO'),
            ];
            $operadoras_totais['TOTAL_TAXA'] = $operadoras_totais['TOTAL_BRUTO'] - $operadoras_totais['TOTAL_LIQUIDO'];

            $erp = $erp_query->paginate($por_pagina_erp);
            $operadoras = $operadoras_query->paginate($por_pagina_operadoras);

            Arr::set($filters, 'status_conciliacao', [$status_conciliada]);
            $erp_totais['TOTAL_CONCILIADA'] = VendasErpFilter::filter($filters)->getQuery()->sum('TOTAL_VENDA');
            Arr::set($filters, 'status_conciliacao', [$status_divergente]);
            $erp_totais['TOTAL_DIVERGENTE']  = VendasErpFilter::filter($filters)->getQuery()->sum('TOTAL_VENDA');
            Arr::set($filters, 'status_conciliacao', [$status_manual]);
            $erp_totais['TOTAL_MANUAL']  = VendasErpFilter::filter($filters)->getQuery()->sum('TOTAL_VENDA');
            Arr::set($filters, 'status_conciliacao', [$status_justificada]);
            $erp_totais['TOTAL_JUSTIFICADA']  = VendasErpFilter::filter($filters)->getQuery()->sum('TOTAL_VENDA');
            Arr::set($filters, 'status_conciliacao', [$status_nao_conciliada]);
            $erp_totais['TOTAL_NAO_CONCILIADA']  = VendasErpFilter::filter($filters)->getQuery()->sum('TOTAL_VENDA');
        
            return response()->json([
                'erp' => [
                    'vendas' => $erp,
                    'totais' => $erp_totais
                ],
                'operadoras' => [
                    'vendas' => $operadoras,
                    'totais' => $operadoras_totais
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possível realizar a consulta.'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
