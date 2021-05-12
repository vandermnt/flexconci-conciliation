<div
  id="{{ $attributes->get('id') }}"
  class="table-config {{ $attributes->get('class') }}"
  @php echo $renderDataset() @endphp
>
  <button class="table-config-control btn button no-hover">
    <i class="fas fa-cog"></i>
    <span>Exibição de colunas</span>
  </button>
  <div class="table-config-body">
    <div class="table-config-list">
      <div template class="table-config-option">
        <input
          type="checkbox"
          data-group="{{ $attributes->get('checker-group') }}"
          data-checker="global"
          template
        >
        <span>Todos</span>
      </div>
    </div>
    <div class="table-config-actions">
      <button class="btn" data-action="confirm">
        Confirmar
        <i class="fa fa-check-circle"></i>
      </button>
    </div>
  </div>
</div>
