@extends('layouts.authLayout')

@section('headerStyle')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/edi-services/cielo/index.css') }}">
@endsection

@section('content')
  <main id="pagina-credenciamento-cielo" class="pagina-edi-cielo card shadow-lg">
    <div class="card-body">
      <div class="px-3">
        <div class="header">
          <h1 class="text-center">Autorização de Acesso</h1>
          <p class="text-muted text-center">Informe seu e-mail abaixo e clique no botão Avançar</p>
          <img src="{{ URL::asset('assets/images/logos/cielo.svg') }}" alt="Cielo Logo">
        </div>
        <form method="GET" action="{{ route('cielo.authenticate') }}">
          <div class="form-group">
            <input
              type="email"
              class="form-control"
              name="email"
              id="email"
              placeholder="Digite seu e-mail"
              required
            >
            @if($errors->has('email'))
              <p class="mt-1 text-danger text-center font-weight-bold">{{ $errors->first('email') }}</p>
            @endif
          </div>

          <button
            class="btn btn-round btn-block waves-effect waves-light"
            type="submit"
          >
            <span class="font-weight-bold">AVANÇAR</span>
            <i class="fas fa-sign-in-alt ml-1"></i>
          </button>
        </form>
      </div>
    </div>
  </main>
@endsection
