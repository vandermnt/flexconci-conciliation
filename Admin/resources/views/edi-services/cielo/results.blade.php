@extends('edi-services.cielo.layout')

@php $pageId = 'pagina-resultados-cielo'; @endphp

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/globals/global.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/edi-services/cielo/index.css') }}">
@endsection

@section('content')
  <div class="card-body">
    <div class="px-3">
      <div class="header">
        <h1 class="text-center">Registro de EDI</h1>
        <img src="{{ URL::asset('assets/images/logos/cielo.svg') }}" alt="Cielo Logo">
      </div>
      <div id="js-error-alert" class="alert alert-danger w-100 hidden">
        <p class="font-weight-bold text-center m-0"></p>
      </div>
      <input
        id="js-access-token"
        type="hidden"
        value="{{ $accessToken }}"
        data-register-url="{{ route('cielo.register-edi') }}"
      >
      <div id="results">
        <div class="mb-2 hidden" id="js-estabelecimentos-registrados">
          <p class="font-weight-bold m-0 mb-1">Estabelecimentos registrados:</p>
          <ul class="p-0 m-0">
            <li data-template class="badge badge-primary p-2 mr-1 mt-1 font-weight-bold hidden"></li>
          </ul>
        </div>
        <div class="mb-2 hidden" id="js-matrizes-duplicadas">
          <p class="font-weight-bold m-0 mb-1">Matrizes duplicadas:</p>
          <div>
            <ul class="p-0 m-0">
              <li data-template class="badge badge-primary p-2 mr-1 mt-1 font-weight-bold hidden"></li>
            </ul>
          </div>
        </div>

        <div id="js-matrizes-nao-duplicadas" class="hidden">
          <p class="font-weight-bold m-0 mb-1">Matrizes não duplicadas:</p>
          <ul class="p-0 m-0">
            <li data-template class="badge badge-danger p-2 mr-1 mt-1 font-weight-bold hidden"></li>
          </ul>
          <p class="text-danger text-justify m-0 mt-2">
            As matrizes não duplicadas não puderam ser feitas automaticamente.
            É necessário entrar em contato com a cielo pelo email
            <a class="text-primary" href="mailto:edi@cielo.com.br" role="button">edi@cielo.com.br</a>
            e solicitar a duplicação dessas matrizes.
          </p>
        </div>
      </div>
      <button
        id="js-next"
        disabled
        class="btn btn-round btn-block waves-effect waves-light mt-4"
        type="button"
        data-redirect-to="{{ url('/') }}"
      >
        <span class="font-weight-bold">CONCLUIR</span>
        <i class="fas fa-check ml-1"></i>
      </button>
    </div>
  </div>

  @section('components')
    <div id="cielo-loader" class="hidden">
      <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
  @endsection
@endsection

@section('scripts')
  <script defer src="{{ URl::asset('assets/js/sweetalert.min.js') }}"></script>
  <script defer src="{{ URl::asset('assets/js/lib/ui/index.js') }}"></script>
  <script defer src="{{ URl::asset('assets/js/lib/api.js') }}"></script>
  <script defer src="{{ URl::asset('assets/js/edi-services/cielo/results.js') }}"></script>
@endsection
