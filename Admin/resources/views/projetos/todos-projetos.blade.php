@extends('layouts.analytics-master')

@section('title', 'Metrica - Admin & Dashboard Template')

@section('headerStyle')
<link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table-dragger@1.0.3/dist/table-dragger.js"></script>

@stop

@section('content')

<div id="tudo_page" class="container-fluid">
  <div class="row">
    <div class="col-sm-12">

      @component('common-components.breadcrumb')
      @slot('title') Todos Projetos @endslot
      @slot('item1') Projetos @endslot
      <!-- @slot('item2') Antecipação de Venda @endslot -->
      @endcomponent

    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <table class="table">
                <thead class="">
                  <tr style="background: #2D5275;">
                    <th style="color: white">Cliente</th>
                    <th style="color: white">Tipo de Projeto</th>
                    <th style="color: white">Descrição do Projeto</th>
                    <th style="color: white">Func. Resp. pelo Projeto</th>
                    <th style="color: white">Data Inicial</th>
                    <th style="color: white">Data Prazo</th>
                    <th style="color: white; text-align: center;">Opções</th>
                  </tr>
                </thead>
                <tbody>
                  @if(isset($projetos))
                  @foreach($projetos as $projeto)
                  <tr>
                    <td>{{ $projeto->NOME }}</td>
                    <td>{{ $projeto->TIPO_PROJETO }}</td>
                    <td>{{ $projeto->DESCRICAO_PROJETO }}</td>
                    <td>{{ $projeto->NOME_FUNCIONARIO }}</td>
                    <?php $dt_inicial = date("d/m/Y", strtotime($projeto->DATA_INICIAL));?>
                    <td>{{ $dt_inicial }}</td>
                    <?php $dt_final = date("d/m/Y", strtotime($projeto->DATA_FINAL));?>
                    <td>{{ $dt_final }}</td>
                    <td style="text-align: center">
                      <a href="{{  action('ProjetosController@detalhamentoProjeto', ['codprojeto' => $projeto->CODIGO]) }}">
                        <i class="fas fa-search" data-toggle="tooltip" data-placement="top" title="Detalhamento do Projeto"></i>
                      </a>
                    </td>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><!--end card-body-->

@section('footerScript')
<script src="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-us-aea-en.js') }}"></script>
<script src="{{ URL::asset('assets/pages/jquery.analytics_dashboard.init.js') }}"></script>
@stop
@stop
