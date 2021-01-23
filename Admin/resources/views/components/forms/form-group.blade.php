@php
  $checkerGroup = $attributes->get('data-group');
  $checkerElement = $attributes->get('data-checker');
@endphp
<div class="form-group">
  @if($attributes->get('label'))
    <label for="{{ $attributes->get('id') }}">{{ $attributes->get('label') }}</label>
  @endif
  
  <input
    id="{{ $attributes->get('id') }}"
    class="form-control"
    type="{{ $attributes->get('type') }}"
    @if($attributes->get('name')) name="{{ $attributes->get('name') }}" @endif 
    @if($attributes->get('value')) value="{{ $attributes->get('value') }}" @endif
    @if($checkerGroup) data-group="{{ $checkerGroup }}" @endif
    @if($checkerElement) data-checker="{{ $checkerElement }}" @endif
    @if($attributes->get('required')) required @endif
  >
</div>