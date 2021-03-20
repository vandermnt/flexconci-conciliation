@extends('layouts.authLayout')

@section('headerStyle')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/edi-services/cielo/credenciamento.css') }}">
@endsection

@section('content')
  <main id="pagina-callback-cielo" class="card shadow-lg">
    <div class="card-body">
      <div class="px-3">
        <div class="header">
          <h1>Autorização de Acesso</h1>
          <img src="{{ URL::asset('assets/images/logos/cielo.svg') }}" alt="Cielo Logo">
          @if($errors->has('error'))
            <div class="alert alert-danger w-100">
              <p class="font-weight-bold text-center">{{ $errors->first('error') }}</p>
            </div>
          @endif
          @if($success)
            <div class="alert alert-success w-100">
              <p class="font-weight-bold text-center">{{ $success }}</p>
              <p class="font-weight-bold text-center">Chave de Acesso: {{ $access_token }}</p>
            </div>
          @endif
        </div>

        <a
          href="{{ url('/') }}"
          class="btn btn-round btn-block waves-effect waves-light"
          type="submit"
        >
          <span class="font-weight-bold">CONCLUIR</span>
          <i class="fas fa-sign-in-alt ml-1"></i>
        </a>
      </div>
    </div>
  </main>
@endsection
