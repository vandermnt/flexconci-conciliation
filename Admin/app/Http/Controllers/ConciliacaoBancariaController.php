<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClienteOperadoraModel;

class ConciliacaoBancariaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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

        return view('conciliacao.conciliacao-bancaria-v2')
            ->with([
                'adquirentes' => $adquirentes
            ]);
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
