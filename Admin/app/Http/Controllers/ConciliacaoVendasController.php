<?php

namespace App\Http\Controllers;

use App\ClienteModel;
use App\GruposClientesModel;
use App\StatusConciliacaoModel;
use App\Filters\VendasErpFilter;
use App\Filters\VendasFilter;
use App\Filters\VendasErpSubFilter;
use App\Filters\VendasSubFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConciliacaoVendasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $erp = ClienteModel::select([
            'erp.ERP',
            'erp.TITULO_CAMPO_ADICIONAL1 as TITULO_CAMPO1',
            'erp.TITULO_CAMPO_ADICIONAL2 as TITULO_CAMPO2',
            'erp.TITULO_CAMPO_ADICIONAL3 as TITULO_CAMPO3'
        ])
        ->leftJoin('erp', 'clientes.COD_ERP', 'erp.CODIGO')
        ->where('clientes.CODIGO', session('codigologin'))
        ->first();

        $empresas = GruposClientesModel::where('COD_CLIENTE', session('codigologin'))
            ->orderBy('NOME_EMPRESA')
            ->get();
            
        $status_conciliacao = StatusConciliacaoModel::orderBy('STATUS_CONCILIACAO')
            ->get();
        
        return view('conciliacao.conciliacao-vendas')
            ->with([
                'erp' => $erp,
                'empresas' => $empresas,
                'status_conciliacao' => $status_conciliacao,
            ]);
    }

    public function searchErp(Request $request) {
        $per_page = $this->getPerPage(
            $request->input('por_pagina', null), 
            [5, 10, 20, 50, 100, 200]
        );
        $filters = $request->all();
        $filters['cliente_id'] = session('codigologin');

        try {
            $status_conciliada = StatusConciliacaoModel::conciliada()->first()->CODIGO;
            $status_nao_conciliada = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;
            $status_justificada = StatusConciliacaoModel::justificada()->first()->CODIGO;
            $status_divergente = StatusConciliacaoModel::divergente()->first()->CODIGO;
            $status_manual = StatusConciliacaoModel::manual()->first()->CODIGO;

            $status_keys = [
                $status_conciliada => 'TOTAL_CONCILIADO',
                $status_nao_conciliada => 'TOTAL_NAO_CONCILIADO',
                $status_justificada => 'TOTAL_JUSTIFICADO',
                $status_divergente => 'TOTAL_DIVERGENTE',
                $status_manual => 'TOTAL_CONCILIADO_MANUAL',
            ];

            $query = VendasErpFilter::filter($filters)->getQuery();
            
            $totals = [
                'TOTAL_BRUTO' => (clone $query)->sum(DB::raw('coalesce(`vendas_erp`.`VALOR_VENDA_PARCELA`, `vendas_erp`.`TOTAL_VENDA`)')) ?? 0,
                'TOTAL_LIQUIDO' => (clone $query)->sum('VALOR_LIQUIDO_PARCELA') ?? 0,
            ];
            $totals['TOTAL_TAXA'] = ($totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO']) ?? 0;

            foreach($status_keys as $status => $key) {
                $totals[$key] = (clone $query)
                    ->selectRaw('sum(coalesce(`vendas_erp`.`VALOR_VENDA_PARCELA`, `vendas_erp`.`TOTAL_VENDA`)) as TOTAL')
                    ->where('vendas_erp.COD_STATUS_CONCILIACAO', $status)
                    ->first()
                    ->TOTAL ?? 0;
            }

            $sales = $query->paginate($per_page);

            return response()->json([
                'vendas' => $sales,
                'totais' => $totals
            ]);
        } catch(Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possível realizar a consulta em Vendas ERP.'
            ], 500);
        }
    }

    public function filterErp(Request $request) {
        $per_page = $this->getPerPage(
            $request->input('por_pagina', null), 
            [5, 10, 20, 50, 100, 200]
        );
        $filters = $request->input('filters');
        $filters['cliente_id'] = session('codigologin');
        $subfilters = $request->input('subfilters');

        try {
            $query = VendasErpSubFilter::subfilter($filtros, $subfiltros)->getQuery();

            $sales = (clone $query)->paginate($per_page);
            $totals = [
                'TOTAL_BRUTO' => $query->sum('VALOR_VENDA'),
                'TOTAL_LIQUIDO' => $query->sum('VALOR_LIQUIDO_PARCELA')
            ];
            $totals['TOTAL_TAXA'] = $totais['TOTAL_BRUTO'] - $totais['TOTAL_LIQUIDO'];

            return response()->json([
                'vendas' => $sales,
                'totais' => $totals,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possível realizar a consulta em Vendas ERP.'
            ], 500);
        }
    }

    public function searchOperadoras(Request $request) {
        $per_page = $this->getPerPage(
            $request->input('por_pagina', null), 
            [5, 10, 20, 50, 100, 200]
        );
        $filters = $request->except(['status_conciliacao']);
        $filters['cliente_id'] = session('codigologin');

        try {
            $status_nao_conciliada = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;
            $query = VendasFilter::filter($filters)
                        ->getQuery()
                        ->where('vendas.COD_STATUS_CONCILIACAO', $status_nao_conciliada);
            
            $totals = [
                'TOTAL_BRUTO' => (clone $query)->sum('VALOR_BRUTO'),
                'TOTAL_LIQUIDO' => (clone $query)->sum('VALOR_LIQUIDO'),
            ];
            $totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];
            $totals['TOTAL_PENDENCIAS_OPERADORAS'] = $totals['TOTAL_BRUTO'];

            $sales = $query->paginate($per_page);

            return response()->json([
                'vendas' => $sales,
                'totais' => $totals
            ]);
        } catch(Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possível realizar a consulta em Vendas Operadoras.'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    private function getPerPage($per_page = null, $allowed_per_page) {
        $per_page = $per_page ?? $allowed_per_page[0];
        $per_page = in_array($per_page, $allowed_per_page) ? $per_page : $allowed_per_page[0];

        return $per_page;
    }
}
