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
          <th class="draggable" data-tb-section="DESCRICAO_ERP" data-th-title="ID. ERP">
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
        <th class="draggable" data-tb-section="DATA_PAGAMENTO" data-th-title="Data de Pagamento">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="DATA_PAGAMENTO"
            >
              <p class="m-0">Data de Pagamento</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="date" class="form-control" name="DATA_PAGAMENTO">
          </div>
        </th>
        <th class="draggable" data-tb-section="BANCO" data-th-title="Banco">
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
        <th class="draggable" data-tb-section="AGENCIA" data-th-title="Agencia">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="AGENCIA"
            >
              <p class="m-0">Agencia</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="AGENCIA">
          </div>
        </th>
				<th class="draggable" data-tb-section="CONTA" data-th-title="Conta">
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
        <th class="draggable" data-tb-section="ADQUIRENTE" data-th-title="Operadora">
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
				<th class="draggable" data-tb-section="VALOR_PREVISTO_OPERADORA" data-th-title="Valor Previsto Operadora">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="VALOR_PREVISTO_OPERADORA"
            >
              <p class="m-0">Valor Previsto Operadora</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
						<input type="number" min="0" step="0.01" class="form-control" name="VALOR_PREVISTO_OPERADORA">
          </div>
        </th>
				<th class="draggable" data-tb-section="VALOR_EXTRATO_BANCARIO" data-th-title="Valor Extrato Bancário">
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
				<th class="draggable" data-tb-section="DIFERENCA" data-th-title="Diferença">
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
				<th class="draggable" data-tb-section="STATUS" data-th-title="Status">
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
				<th class="draggable" data-tb-section="DATA_IMPORTACAO" data-th-title="Data Importação">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="DATA_IMPORTACAO"
            >
              <p class="m-0">Data Importação</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="date" class="form-control" name="DATA_IMPORTACAO">
          </div>
        </th>
				<th class="draggable" data-tb-section="HORA_IMPORTACAO" data-th-title="Hora Importação">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="HORA_IMPORTACAO"
            >
              <p class="m-0">Hora Importação</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="HORA_IMPORTACAO">
          </div>
        </th>
				<th class="draggable" data-tb-section="USUARIO_IMPORTACAO" data-th-title="Usuário Importação">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="USUARIO_IMPORTACAO"
            >
              <p class="m-0">Usuário Importação</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="USUARIO_IMPORTACAO">
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
          <td data-tb-section="DESCRICAO_ERP" data-column="DESCRICAO_ERP"></td>
        @endif
        <td data-tb-section="DATA_PAGAMENTO" data-column="DATA_PAGAMENTO" data-format='date'></td>
        <td
          data-tb-section="BANCO"
          data-image="BANCO_IMAGEM"
          data-default-image="assets/images/widgets/cards.svg"
          data-column="BANCO"
          data-default-value="Sem identificação"
        >
          <div
            class="icon-image tooltip-hint tooltip-left"
            data-title="BANCO"
            data-default-title="Sem identificação">
          </div>
        </td>
        <td data-tb-section="AGENCIA" data-column="AGENCIA"></td>
				<td data-tb-section="CONTA" data-column="CONTA"></td>
				<td
          data-tb-section="ADQUIRENTE"
          data-image="ADQUIRENTE_IMAGEM"
          data-default-image="assets/images/widgets/cards.svg"
          data-column="ADQUIRENTE"
          data-default-value="Sem identificação"
        >
          <div
            class="icon-image tooltip-hint tooltip-left"
            data-title="ADQUIRENTE"
            data-default-title="Sem identificação">
          </div>
        </td>
				<td data-tb-section="VALOR_PREVISTO_OPERADORA" data-column="VALOR_PREVISTO_OPERADORA" data-format="currency"></td>
				<td data-tb-section="VALOR_EXTRATO_BANCARIO" data-column="VALOR_EXTRATO_BANCARIO" data-format="currency"></td>
				<td data-tb-section="DIFERENCA" data-column="DIFERENCA" data-format="currency"></td>
				<td data-tb-section="STATUS" data-column="STATUS"></td>
				<td data-tb-section="DATA_IMPORTACAO" data-column="DATA_IMPORTACAO"></td>
				<td data-tb-section="HORA_IMPORTACAO" data-column="HORA_IMPORTACAO"></td>
				<td data-tb-section="USUARIO_IMPORTACAO" data-column="USUARIO_IMPORTACAO"></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td>Totais</td>
        @isset($actions)
          {{-- <td></td> --}}
        @endisset
				@if($isColumnVisible('ID_ERP'))
          <td data-tb-section="DESCRICAO_ERP"></td>
        @endif
        <td data-tb-section="DATA_PAGAMENTO"></td>
        <td data-tb-section="BANCO"></td>
				<td data-tb-section="AGENCIA"></td>
				<td data-tb-section="CONTA"></td>
				<td data-tb-section="ADQUIRENTE"></td>
				<td data-tb-section="VALOR_PREVISTO_OPERADORA" data-column="TOTAL_PREVISTO_OPERADORA" data-format="currency">R$ 0,00</td>
        <td data-tb-section="VALOR_EXTRATO_BANCARIO" data-column="TOTAL_EXTRATO_BANCARIO" data-format="currency">R$ 0,00</td>
        <td data-tb-section="DIFERENCA" data-column="TOTAL_DIFERENCA" data-format="currency">R$ 0,00</td>
				<td data-tb-section="STATUS"></td>
				<td data-tb-section="DATA_IMPORTACAO"></td>
				<td data-tb-section="HORA_IMPORTACAO"></td>
				<td data-tb-section="USUARIO_IMPORTACAO"></td>
      </tr>
    </tfoot>
  </table>
</div>
