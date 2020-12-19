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

    public function filterErp(Request $request) {
        $quantidadesPermitidas = [5, 10, 20, 50, 100, 200];
        $filters = $request->except(['status_conciliacao']);
        $filters['cliente_id'] = session('codigologin');
        
        $status_conciliacao = $request->input('status_conciliacao', false);
        $por_pagina = $request->input('por_pagina', 5);
        $por_pagina = in_array($por_pagina, $quantidadesPermitidas) ? $por_pagina : 5;

        try {
            $status_conciliada = StatusConciliacaoModel::conciliada()->first()->CODIGO;
            $status_nao_conciliada = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;
            $status_justificada = StatusConciliacaoModel::justificada()->first()->CODIGO;
            $status_divergente = StatusConciliacaoModel::divergente()->first()->CODIGO;
            $status_manual = StatusConciliacaoModel::manual()->first()->CODIGO;

            $totais_chaves = [
                $status_conciliada => 'TOTAL_CONCILIADA',
                $status_nao_conciliada => 'TOTAL_NAO_CONCILIADA',
                $status_justificada => 'TOTAL_JUSTIFICADA',
                $status_divergente => 'TOTAL_DIVERGENTE',
                $status_manual => 'TOTAL_MANUAL',
            ];

            $erp_base_query = clone VendasErpFilter::filter($filters)->getQuery();

            $erp_query = (clone $erp_base_query)->whereIn('COD_STATUS_CONCILIACAO', $request->input('status_conciliacao'));
            $erp_totais = [
                'TOTAL_BRUTO' => $erp_query->sum('TOTAL_VENDA'),
                'TOTAL_LIQUIDO' => $erp_query->sum('VALOR_LIQUIDO_PARCELA'),
            ];
            $erp_totais['TOTAL_TAXA'] = $erp_totais['TOTAL_BRUTO'] - $erp_totais['TOTAL_LIQUIDO'];

            foreach($totais_chaves as $chave => $valor) {
                $erp_totais[$valor] = 0;
            }

            $totais_conciliacao = (clone $erp_base_query)
                ->select('COD_STATUS_CONCILIACAO')
                ->selectRaw('sum(TOTAL_VENDA) as TOTAL_BRUTO')
                ->groupBy('COD_STATUS_CONCILIACAO')
                ->get()
                ->toArray();

            foreach($totais_conciliacao as $total_conciliacao) {
                $total_chave = $totais_chaves[$total_conciliacao['COD_STATUS_CONCILIACAO']];
                $erp_totais[$total_chave] = $total_conciliacao['TOTAL_BRUTO'];
            }

            $erp = $erp_query->paginate($por_pagina);

            return response()->json([
                'erp' => [
                    'vendas' => $erp,
                    'totais' => $erp_totais
                ]
            ]);
        } catch(Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possível realizar a consulta em Vendas ERP.'
            ], 500);
        }
    }

    public function filterOperadoras(Request $request) {
        $quantidadesPermitidas = [5, 10, 20, 50, 100, 200];
        $filters = $request->except(['status_conciliacao']);
        $filters['cliente_id'] = session('codigologin');

        $por_pagina = $request->input('por_pagina', 5);
        $por_pagina = in_array($por_pagina, $quantidadesPermitidas) ? $por_pagina : 5;

        try {
            $status_nao_conciliada = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;

            $operadoras_query = VendasFilter::filter($filters)
                                    ->getQuery()
                                    ->where('COD_STATUS_CONCILIACAO', $status_nao_conciliada);

            $operadoras_totais = [
                'TOTAL_BRUTO' => $operadoras_query->sum('VALOR_BRUTO'),
                'TOTAL_LIQUIDO' => $operadoras_query->sum('VALOR_LIQUIDO'),
            ];
            $operadoras_totais['TOTAL_TAXA'] = $operadoras_totais['TOTAL_BRUTO'] - $operadoras_totais['TOTAL_LIQUIDO'];
            $operadoras = $operadoras_query->paginate($por_pagina);

            return response()->json([
                'operadoras' => [
                    'vendas' => $operadoras,
                    'totais' => $operadoras_totais
                ]
            ]);
        } catch(Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possível realizar a consulta em Vendas Operadoras.'
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
