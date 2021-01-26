@php
  $options = $attributes->get('options') ?? ['Selecione uma quantidade'];
@endphp

<footer
  class="d-flex justify-content-between align-items-end flex-wrap {{ $attributes->get('class') }}"
>
  <nav class="nav-pagination">
    <ul class="pagination" id="{{ $attributes->get('pagination-id') }}">
      <li class="page-item active">
        <a href="" class="page-link">1</a>
      </li>
    </ul>
  </nav>

  <div class="form-group">
    <label for="{{ $attributes->get('per-page-select-id') }}">Quantidade por página</label>
    <select name="por_pagina" id="{{ $attributes->get('per-page-select-id') }}" class="form-control">
      @foreach($options as $option)
        <option value="{{ $option }}">{{ $option }}</option>
      @endforeach
    </select>
  </div>
</footer>