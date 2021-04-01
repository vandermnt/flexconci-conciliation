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
              data-tbsort-by="DATA_PAGAMENTO"
            >
              <p class="m-0">Data de Pagamento</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="DATA_PAGAMENTO">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="BANCO"
            >
              <p class="m-0">Banco</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="BANCO">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="AGENCIA"
            >
              <p class="m-0">Agencia</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="date" class="form-control" name="AGENCIA">
          </div>
        </th>
				<th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="CONTA"
            >
              <p class="m-0">Conta</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="CONTA">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="ADQUIRENTE"
            >
              <p class="m-0">Operadora</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="ADQUIRENTE">
          </div>
        </th>
				<th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="VALOR_PREVISTO_OPERADORA"
            >
              <p class="m-0">Valor Previsto Operadora</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="VALOR_PREVISTO_OPERADORA">
          </div>
        </th>
				<th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="VALOR_EXTRATO_BANCARIO"
            >
              <p class="m-0">Valor Extrato Bancário</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="VALOR_EXTRATO_BANCARIO">
          </div>
        </th>
				<th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="DIFERENCA"
            >
              <p class="m-0">Diferença</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="DIFERENCA">
          </div>
        </th>
				<th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="STATUS"
            >
              <p class="m-0">Status</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="STATUS">
          </div>
        </th>
				<th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="HISTORICO"
            >
              <p class="m-0">Histórico</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="HISTORICO">
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
        <td data-column="DATA_PAGAMENTO" data-format='date'></td>
        <td data-column="BANCO"></td>
        <td data-column="AGENCIA"></td>
				<td data-column="CONTA"></td>
				<td data-column="ADQUIRENTE"></td>
				<td data-column="VALOR_PREVISTO_OPERADORA" data-format="currency"></td>
				<td data-column="VALOR_EXTRATO_BANCARIO" data-format="currency"></td>
				<td data-column="DIFERENCA"></td>
				<td data-column="STATUS"></td>
				<td data-column="HISTORICO"></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
				<td></td>
				<td></td>
				<td></td>
        <td></td>
        <td></td>
				<td></td>
				<td></td>
      </tr>
    </tfoot>
  </table>
</div>
