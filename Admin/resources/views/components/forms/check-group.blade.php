<div class="input-check-group {{ $attributes->get('class') }}">
  <label>{{ $attributes->get('label') }}</label>
  <div class="check-group">
    @foreach(($getOptions() ?? []) as $item)
      <div class="form-group mr-2">
        <input
          id="{{ $id }}-{{ $getItemValue($item) }}"
          name="{{ $attributes->get('name') }}"
          value="{{ $getItemValue($item) }}"
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
            for="{{ $id }}-{{ $getItemValue($item) }}"
        >
          {{ $getItemDescription($item) }}
        </label>
      </div>
    @endforeach
  </div>
</div>