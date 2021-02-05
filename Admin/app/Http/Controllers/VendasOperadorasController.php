<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Filters\VendasFilter;
use App\Filters\VendasSubFilter;
use App\VendasModel;
use App\MeioCaptura;
use App\StatusConciliacaoModel;
use App\StatusFinanceiroModel;
use App\GruposClientesModel;
use App\AdquirentesModel;
use App\ClienteOperadoraModel;
use App\Exports\VendasOperadorasExport;

class VendasOperadorasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresas = $empresas = GruposClientesModel::select(['CODIGO', 'NOME_EMPRESA', 'CNPJ'])
            ->where('COD_CLIENTE', session('codigologin'))
            ->orderBy('NOME_EMPRESA')
            ->get();

        $adquirentes = ClienteOperadoraModel::select([
                'adquirentes.CODIGO',
                'adquirentes.ADQUIRENTE',
                'adquirentes.IMAGEM'
            ])
            ->join('adquirentes', 'COD_ADQUIRENTE', 'adquirentes.CODIGO')
            ->where('COD_CLIENTE', '=', session('codigologin'))
            ->distinct()
            ->orderBy('ADQUIRENTE')
            ->get();

        $bandeiras = VendasModel::select([
            'bandeira.CODIGO',
            'bandeira.BANDEIRA',
            'bandeira.IMAGEM'
            ])
            ->leftJoin('bandeira', 'COD_BANDEIRA', 'bandeira.CODIGO')
            ->where('COD_CLIENTE', session('codigologin'))
            ->whereNotNull('bandeira.BANDEIRA')
            ->distinct()
            ->orderBy('BANDEIRA')
            ->get();

        $modalidades = VendasModel::select([
            'modalidade.CODIGO',
            'modalidade.DESCRICAO'
            ])
            ->leftJoin('modalidade', 'modalidade.CODIGO', 'CODIGO_MODALIDADE')
            ->where('COD_CLIENTE', session('codigologin'))
            ->whereNotNull('modalidade.DESCRICAO')
            ->distinct()
            ->orderBy('DESCRICAO')
            ->get();

        $estabelecimentos = VendasModel::select([
                'ESTABELECIMENTO',
                'adquirentes.ADQUIRENTE'
            ])
            ->where('COD_CLIENTE', session('codigologin'))
            ->leftJoin('adquirentes', 'vendas.ADQID', 'adquirentes.CODIGO')
            ->orderBy('ESTABELECIMENTO', 'asc')
            ->distinct()
            ->get();

        $status_conciliacao = StatusConciliacaoModel::orderBy('STATUS_CONCILIACAO')
            ->get();

        $status_financeiro = StatusFinanceiroModel::orderBy('STATUS_FINANCEIRO')
            ->get();

        return view('vendas.vendas-operadoras')
            ->with([
                'empresas' => $empresas,
                'adquirentes' => $adquirentes,
                'bandeiras' => $bandeiras,
                'modalidades' => $modalidades,
                'estabelecimentos' => $estabelecimentos,
                'status_conciliacao' => $status_conciliacao,
                'status_financeiro' => $status_financeiro,
            ]);
    }

    public function search(Request $request) {
        $allowedPerPage = [10, 20, 50, 100, 200];
        $perPage = $request->input('por_pagina', 10);
        $perPage = in_array($perPage, $allowedPerPage) ? $perPage : 10;
        $filters = $request->all();
        $filters['cliente_id'] = session('codigologin');

        try {
            $query = VendasFilter::filter($filters)
                ->getQuery();

            $sales = (clone $query)->paginate($perPage);
            $totals = [
                'TOTAL_BRUTO' => (clone $query)->sum('VALOR_BRUTO'),
                'TOTAL_LIQUIDO' => (clone $query)->sum('VALOR_LIQUIDO'),
                'TOTAL_TARIFA_MINIMA' => (clone $query)->sum('TAXA_MINIMA') ?? 0,
            ];
            $totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];

            return response()->json([
                'vendas' => $sales,
                'totais' => $totals,
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => 'Não foi possível realizar a consulta em Vendas Operadoras.',
            ], 500);
        }
    }

    public function filter(Request $request) {
        $allowedPerPage = [10, 20, 50, 100, 200];
        $perPage = $request->input('por_pagina', 10);
        $perPage = in_array($perPage, $allowedPerPage) ? $perPage : 10;
        $filters = $request->input('filters');
        $filters['cliente_id'] = session('codigologin');
        $subfilters = $request->input('subfilters');

        try {
            $query = VendasSubFilter::subfilter($filters, $subfilters)
                ->getQuery();

            $sales = (clone $query)->paginate($perPage);
            $totals = [
                'TOTAL_BRUTO' => (clone $query)->sum('VALOR_BRUTO'),
                'TOTAL_LIQUIDO' => (clone $query)->sum('VALOR_LIQUIDO'),
                'TOTAL_TARIFA_MINIMA' => (clone $query)->sum('TAXA_MINIMA') ?? 0,
            ];
            $totals['TOTAL_TAXA'] = $totals['TOTAL_BRUTO'] - $totals['TOTAL_LIQUIDO'];

            return response()->json([
                'vendas' => $sales,
                'totais' => $totals,
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => 'Não foi possível realizar a consulta em Vendas Operadoras.',
            ], 500);
        }
    }

    public function export(Request $request) {
        set_time_limit(300);

        $filters = $request->except(['_token']);
        $subfilters = $request->except(['_token']);
        Arr::set($filters, 'cliente_id', session('codigologin'));
        return (new VendasOperadorasExport($filters, $subfilters))->download('vendas_operadoras_'.time().'.xlsx');
    }

    public function print(Request $request, $id) {
        $sale = VendasFilter::filter([
                'id' => [$id],
                'data_inicial' => '0001-01-01',
                'data_final' => date('Y-m-d'),
                'cliente_id' => session('codigologin')
            ])
            ->getQuery()
            ->first();
        $customPaper = array(0, 0, 240.53, 210.28);

        return \PDF::loadView('vendas.comprovante-venda-operadora', compact('sale'))
            ->setPaper($customPaper, 'landscape')
            ->stream('comprovante_venda_'.$id.'_'.time().'.pdf');
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
}
