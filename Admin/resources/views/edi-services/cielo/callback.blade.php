@extends('layouts.authLayout')

@php
  $success = session('success');
  $access_token = session('access_token');
@endphp

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
            </div>
          @endif
        </div>
        <input type="hidden" value="{{ $access_token }}">
        <a
          href="{{ $success ? route('cielo.results') : route('cielo.credenciamento') }}"
          class="btn btn-round btn-block waves-effect waves-light"
          type="submit"
        >
          <span class="font-weight-bold">{{ $success ? 'AVANÇAR' : 'TENTAR NOVAMENTE'  }}</span>
          <i class="fas fa-sign-in-alt ml-1"></i>
        </a>
      </div>
    </div>
  </main>
@endsection
