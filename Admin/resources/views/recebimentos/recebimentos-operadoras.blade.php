@extends('layouts.analytics-master')

@section('headerStyle')
  <link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/globals/global.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="{{ URL::asset('assets/css/recebimentos/pagina-vendas-operadoras.css') }}" type="text/css">
@endsection

@section('content')
  <main id="pagina-recebimentos-operadoras" class="container-fluid">
    <header>
      @component('common-components.breadcrumb')
        @slot('title') Recebimentos Operadoras @endslot
        @slot('item1') Recebimentos @endslot
      @endcomponent
    </header>

    <div class="card">
      <div class="card-body">
        <x-forms.search-form
          id="js-form-pesquisa"
          :urls="[]"
          :hidden-fields="[
            'bandeiras',
            'modalidades',
            'estabelecimentos',
            'modalidades',
            'status-conciliacao',
            'status-financeiro',
          ]"
        />
      </div>
    </div>
  </main>
@endsection