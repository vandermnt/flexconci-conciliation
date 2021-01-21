@php
  $descriptionKey = $attributes->get('item-description-key');
  $idKey = $attributes->get('item-id-key');
  $data = $attributes->get('data');
@endphp

<div class="input-check-group {{ $attributes->get('class') }}">
  <label>{{ $attributes->get('label') }}</label>
  <div class="check-group">

    @isset($data)
      @foreach($attributes->get('data') as $item)
        <div class="form-group mr-2">
          <input
            id="{{ $attributes->get('id') }}-{{ ((array) $item)[$idKey] }}"
            name="{{ $attributes->get('name') }}"
            value="{{ ((array) $item)[$idKey] }}"
            type="checkbox"
            @if($attributes->get('data-group'))
              data-group="{{ $attributes->get('data-group') }}"
              data-checker="{{ $attributes->get('data-checker') }}"
            @endif
            @if($attributes->get('checked'))
              checked
            @endif
          >
          <label
              for="{{ $attributes->get('id') }}-{{ ((array) $item)[$idKey] }}"
          >
            {{ ((array) $item)[$descriptionKey] }}
          </label>
        </div>
      @endforeach
    @endisset
  </div>
</div>