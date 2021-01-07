<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

use App\ClienteModel;
use App\VendasErpModel;
use App\VendasModel;
use App\GruposClientesModel;
use App\StatusConciliacaoModel;
use App\JustificativaModel;
use App\Filters\VendasErpFilter;
use App\Filters\VendasFilter;
use App\Filters\VendasErpSubFilter;
use App\Filters\VendasSubFilter;
use App\Exports\VendasErpConciliacaoExport;
use App\Exports\VendasConciliacaoExport;

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

        $justificativas = JustificativaModel::select('JUSTIFICATIVA')
            ->where('JUSTIFICATIVA_GLOBAL', 'S')
            ->orWhere('COD_CLIENTE', session('codigologin'))
            ->get();

        return view('conciliacao.conciliacao-automatica')
            ->with([
                'erp' => $erp,
                'empresas' => $empresas,
                'status_conciliacao' => $status_conciliacao,
                'justificativas' => $justificativas
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
                'TOTAL_BRUTO' => $erp_query->sum('VALOR_VENDA_PARCELA'),
                'TOTAL_LIQUIDO' => $erp_query->sum('VALOR_LIQUIDO_PARCELA'),
            ];
            $erp_totais['TOTAL_TAXA'] = $erp_totais['TOTAL_BRUTO'] - $erp_totais['TOTAL_LIQUIDO'];

            foreach($totais_chaves as $chave => $valor) {
                $erp_totais[$valor] = 0;
            }

            $totais_conciliacao = (clone $erp_base_query)
                ->select('COD_STATUS_CONCILIACAO')
                ->selectRaw('sum(VALOR_VENDA_PARCELA) as TOTAL_BRUTO')
                ->groupBy('COD_STATUS_CONCILIACAO')
                ->get()
                ->toArray();

            foreach($totais_conciliacao as $total_conciliacao) {
                $total_chave = $totais_chaves[$total_conciliacao['COD_STATUS_CONCILIACAO']];
                $erp_totais[$total_chave] = $total_conciliacao['TOTAL_BRUTO'];
            }

            $erp = $erp_query->paginate($por_pagina);

            return response()->json([
                'vendas' => $erp,
                'totais' => $erp_totais
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
                'vendas' => $operadoras,
                'totais' => $operadoras_totais
            ]);
        } catch(Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possível realizar a consulta em Vendas Operadoras.'
            ], 500);
        }
    }

    public function subFilterErp(Request $request) {
        $quantidadesPermitidas = [5, 10, 20, 50, 100, 200];
        $filtros = $request->input('filtros');
        $filtros['cliente_id'] = session('codigologin');
        $subfiltros = $request->input('subfiltros');

        $por_pagina = $request->input('por_pagina', 5);
        $por_pagina = in_array($por_pagina, $quantidadesPermitidas) ? $por_pagina : 5;

        try {
            $query = VendasErpSubFilter::subfilter($filtros, $subfiltros)->getQuery();

            $vendas = (clone $query)->paginate($por_pagina);
            $totais = [
                'TOTAL_BRUTO' => $query->sum('VALOR_VENDA_PARCELA'),
                'TOTAL_LIQUIDO' => $query->sum('VALOR_LIQUIDO_PARCELA')
            ];
            $totais['TOTAL_TAXA'] = $totais['TOTAL_BRUTO'] - $totais['TOTAL_LIQUIDO'];

            return response()->json([
                'vendas' => $vendas,
                'totais' => $totais,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possível realizar a consulta em Vendas ERP.'
            ], 500);
        }
    }
    
    public function subFilterOperadoras(Request $request) {
        $quantidadesPermitidas = [5, 10, 20, 50, 100, 200];
        $filtros = $request->input('filtros');
        $filtros['cliente_id'] = session('codigologin');
        $subfiltros = $request->input('subfiltros');
        $por_pagina = $request->input('por_pagina', 5);
        $por_pagina = in_array($por_pagina, $quantidadesPermitidas) ? $por_pagina : 5;

        try {
            $status_nao_conciliada = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;
            $filtros = Arr::set($filtros, 'status_conciliacao', [$status_nao_conciliada]);
            
            $query = VendasSubFilter::subfilter($filtros, $subfiltros)->getQuery();

            $vendas = (clone $query)->paginate($por_pagina);
            $totais = [
                'TOTAL_BRUTO' => $query->sum('VALOR_BRUTO'),
                'TOTAL_LIQUIDO' => $query->sum('VALOR_LIQUIDO')
            ];
            $totais['TOTAL_TAXA'] = $totais['TOTAL_BRUTO'] - $totais['TOTAL_LIQUIDO'];

            return response()->json([
                'vendas' => $vendas,
                'totais' => $totais
            ]);
        } catch (Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possível realizar a consulta em Vendas ERP.'
            ], 500);
        }
    }

    public function conciliarManualmente(Request $request)
    {
        $id_operadoras = $request->input('id_operadora');
        $id_erp = $request->input('id_erp');
        $status_nao_conciliada = StatusConciliacaoModel::naoConciliada()->first()->CODIGO; 

        $venda_erp = VendasErpModel::where('CODIGO', $id_erp[0])
            ->where('COD_CLIENTE', session('codigologin'))
            ->where('COD_STATUS_CONCILIACAO', $status_nao_conciliada)
            ->first();
        $venda_operadora = VendasModel::where('CODIGO', $id_operadoras[0])
            ->where('COD_CLIENTE', session('codigologin'))
            ->where('COD_STATUS_CONCILIACAO', $status_nao_conciliada)
            ->first();

        $status_manual = StatusConciliacaoModel::manual()->first();
        
        $venda_erp->COD_STATUS_CONCILIACAO = $status_manual->CODIGO;
        $venda_operadora->COD_STATUS_CONCILIACAO = $status_manual->CODIGO;
        $venda_erp->save();
        $venda_operadora->save();

        return response()->json([
            'status' => 'sucesso',
            'mensagem' => 'As vendas foram conciliadas com sucesso.',
            'erp' => [
                'ID' => $venda_erp->CODIGO,
                'TOTAL_BRUTO' => $venda_erp->VALOR_VENDA_PARCELA,
            ],
            'operadora' => [
                'ID' => $venda_operadora->CODIGO,
                'TOTAL_BRUTO' =>  $venda_operadora->VALOR_BRUTO,
                'TOTAL_LIQUIDO' =>  $venda_operadora->VALOR_LIQUIDO,
                'TOTAL_TAXA' =>  $venda_operadora->VALOR_BRUTO - $venda_operadora->VALOR_LIQUIDO,
            ],
            'STATUS_MANUAL_IMAGEM_URL' => $status_manual->IMAGEM_URL,
            'STATUS_MANUAL' => $status_manual->STATUS_CONCILIACAO
        ], 200);
    }

    public function justificar(Request $request) {
        $id_erp = $request->input('id_erp') ?? '';
        $justificativa = $request->input('justificativa') ?? null;

        if(strlen(trim($justificativa)) === 0) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'A justificativa deve ser informada.'
            ], 400);
        }

        $status_justificada = StatusConciliacaoModel::justificada()->first();
        $status_nao_conciliada = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;

        $query = VendasErpModel::whereIn('CODIGO', $id_erp)
            ->where('COD_CLIENTE', session('codigologin'))
            ->where('COD_STATUS_CONCILIACAO', $status_nao_conciliada);
        
        $vendas_erp = (clone $query)->get();
        $total_bruto = (clone $query)->sum('VALOR_VENDA_PARCELA');
        $ids_erp = (clone $query)->select('CODIGO as ID')->get();

        foreach($vendas_erp as $venda_erp) {
            $venda_erp->JUSTIFICATIVA = $justificativa;
            $venda_erp->COD_STATUS_CONCILIACAO = $status_justificada->CODIGO;
            $venda_erp->save();
        }

        return response()->json([
            'status' => 'sucesso',
            'erp' => [
                'ID_ERP' => $ids_erp,
                'TOTAL_BRUTO' => $total_bruto,
            ],
            'STATUS_JUSTIFICADO_IMAGEM_URL' => $status_justificada->IMAGEM_URL,
            'STATUS_JUSTIFICADO' => $status_justificada->STATUS_CONCILIACAO,
            'JUSTIFICATIVA' => $justificativa
        ], 200);
    }

    public function exportarErp(Request $request) {
        set_time_limit(300);

        $filters = $request->except('_token');
        Arr::set($filters, 'cliente_id', session('codigologin'));
        return (new VendasErpConciliacaoExport($filters))->download('vendas_erp_conciliacao_'.time().'.xlsx');
    }

    public function exportarOperadoras(Request $request) {
        set_time_limit(300);

        $filters = $request->except(['_token', 'status_conciliacao']);
        $status_nao_conciliada = StatusConciliacaoModel::naoConciliada()->first()->CODIGO;
        Arr::set($filters, 'cliente_id', session('codigologin'));
        Arr::set($filters, 'status_conciliacao', [$status_nao_conciliada]);
        return (new VendasConciliacaoExport($filters))->download('vendas_operadoras_conciliacao_'.time().'.xlsx');
    }
}
