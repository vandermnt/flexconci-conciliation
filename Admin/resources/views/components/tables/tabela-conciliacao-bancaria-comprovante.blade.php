<div class="table-responsive {{ $attributes->get('class') }}">
  <table
    class="table table-striped"
    id="{{ $attributes->get('id') ?? 'js-tabela' }}"
  >
    <thead>
      <tr>
        @isset($actions)
          <th>
            <div class="d-flex flex-column justify-content-end">
              <p class="m-0">{{ $getHeader('actions') ?? 'Ações' }}</p>
            </div>
          </th>
        @endisset
        @if($isColumnVisible('ID_ERP'))
          <th>
            <div class="d-flex flex-column align-items-center" data-table-toggle="table-sort">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="DESCRICAO_ERP"
              >
                <p class="m-0">ID. ERP</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="text" class="form-control" name="DESCRICAO_ERP">
            </div>
          </th>
        @endif
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="NOME_EMPRESA"
            >
              <p class="m-0">Empresa</p>
              <img class="table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control resize" name="NOME_EMPRESA" autocomplete="off">
          </div>
        </th>
				<th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="BANDEIRA"
            >
              <p class="m-0">Bandeira</p>
              <img class="table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control resize" name="BANDEIRA" autocomplete="off">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="MODALIDADE"
            >
              <p class="m-0">F. Pagamento</p>
              <img class="table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control resize" name="MODALIDADE" autocomplete="off">
          </div>
        </th>
				<th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="ESTABELECIMENTO"
            >
              <p class="m-0">Estabelecimento</p>
              <img class="table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
						<input type="text" class="form-control fix-width" name="ESTABELECIMENTO" autocomplete="off">
          </div>
        </th>
				<th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="VALOR"
            >
              <p class="m-0">Valor</p>
              <img class="table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control resize" name="VALOR" autocomplete="off">
          </div>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr data-id="ID" class="table-row-template hidden">
        @isset($actions)
          {{ $actions }}
        @endisset
        @if($isColumnVisible('ID_ERP'))
          <td data-column="DESCRICAO_ERP"></td>
        @endif
        <td data-column="NOME_EMPRESA"></td>
				<td data-column="BANDEIRA"></td>
				<td data-column="MODALIDADE"></td>
				<td data-column="ESTABELECIMENTO"></td>
				<td data-column="VALOR" data-format="currency"></td>
      </tr>
    </tbody>
    <tfoot>
			<tr>
        <td></td>
        @isset($actions)
          <td></td>
        @endisset
				@if($isColumnVisible('ID_ERP'))
          <td></td>
        @endif
        <td></td>
        <td></td>
				<td class="totals">
					<div class="d-flex flex-wrap text-center">
						<span class="w-100">Total</span>
					</div>
				</td>
				<td class="totals">
					<div class="d-flex flex-wrap text-center">
						<span id="total-comprovante" class="w-100 text-center" data-column="TOTAL_PREVISTO_OPERADORA" data-format="currency">R$ 0,00</span>
					</div>
				</td>
      </tr>
    </tfoot>
  </table>
</div>
