<div 
  class="card box {{ $attributes->get('class') }}"
  data-key="{{ $attributes->get('data-key') }}"
  data-format="{{ $attributes->get('data-format') }}"
>
  <div class="card-body">
    <h4>{{ $title }}</h4>
    <div class="d-flex align-items-center justify-content-between {{ $attributes->get('content-class') }}">
      <p
        id="{{ $attributes->get('content-id') }}"
        class="content"
      >
        {{ $content }}
      </p>
      <img src="{{ url($iconPath)}}" alt="{{ $iconDescription }}">
    </div>
  </div>
</div>