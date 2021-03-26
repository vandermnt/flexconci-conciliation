@extends('edi-services.cielo.layout')

@php
  $success = session('success');
  $access_token = session('access_token');
  $pageId = 'pagina-authorize-cielo';
@endphp

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/edi-services/cielo/index.css') }}">
@endsection

@section('content')
  <div class="card-body">
    <div class="px-3">
      <div class="header">
        <h1 class="text-center">Autorização de Acesso</h1>
        @if($success)
          <p class="text-muted text-center">O primeiro passo foi concluído. Avance para o próximo passo.</p>
        @endif
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
@endsection
