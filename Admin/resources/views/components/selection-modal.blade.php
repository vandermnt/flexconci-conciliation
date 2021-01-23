<div
  id="{{ $attributes->get('id') }}"
  class="modal fade"
  role="dialog"
  tabindex="-1"
  data-backdrop="static"
  data-keyboard="false"
  aria-labelledby="{{ $attributes->get('modal-label-id') }}"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <header class="modal-header d-flex align-items-center">
        <h5
          class="modal-title" id="{{ $attributes->get('modal-label-id') }}"
        >
          {{ $attributes->get('modal-label') }}  
        </h5>
        <button
          class="close"
          type="button"
          data-dismiss="modal"
          data-action="cancel"
          data-group="{{ $attributes->get('data-group') ?? $attributes->get('data-filter-group') }}"
          data-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </header>
      <main class="modal-body">
        <div class="form-group">
          <h6>Pesquisar</h6>
          <input
            class="form-control"
            type="text"
            data-filter-group="{{ $attributes->get('data-filter-group') }}"
            data-filter-fields="{{ $attributes->get('data-filter-fields') }}"
          >
        </div>

        {{ $slot }}
      </main>
      <footer class="modal-footer">
        @isset($footer)
          {{ $footer }}
        @else
          <button
            type="button"
            class="btn btn-danger font-weight-bold"
            data-action="cancel"
            data-group="{{ $attributes->get('data-group') ?? $attributes->get('data-filter-group') }}"
            data-dismiss="modal"
          >
            Cancelar
          </button>

          <button
            type="button"
            class="btn btn-success font-weight-bold"
            data-action="confirm"
            data-group="{{ $attributes->get('data-group') ?? $attributes->get('data-filter-group') }}"
            data-dismiss="modal"
          >
            Confirmar
          </button>
        @endisset
      </footer>
    </div>
  </div>
</div>