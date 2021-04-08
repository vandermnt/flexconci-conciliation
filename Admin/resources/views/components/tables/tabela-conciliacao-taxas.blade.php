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
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="NOME_EMPRESA">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="CNPJ"
            >
              <p class="m-0">CNPJ</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="CNPJ">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="DATA_VENDA"
            >
              <p class="m-0">Venda</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="date" class="form-control" name="DATA_VENDA">
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
              data-tbsort-by="BANDEIRA"
            >
              <p class="m-0">Bandeira</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="BANDEIRA">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="MODALIDADE"
            >
              <p class="m-0">Forma de Pagamento</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="MODALIDADE">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="NSU"
            >
              <p class="m-0">NSU</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="NSU">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="AUTORIZACAO"
            >
              <p class="m-0">Autorização</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="AUTORIZACAO">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="TID"
            >
              <p class="m-0">TID</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="TID">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="VALOR_BRUTO"
            >
              <p class="m-0">Valor Bruto</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="VALOR_BRUTO">
          </div>
        </th>
				<th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="VALOR_BRUTO"
            >
              <p class="m-0">Taxa Acordada %</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="TAXA_ACORDADA%">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="PERCENTUAL_TAXA"
            >
              <p class="m-0">Taxa Praticada %</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="PERCENTUAL_TAXA">
          </div>
        </th>
				<th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="PERCENTUAL_TAXA"
            >
              <p class="m-0">Dif Taxa %</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="PERCENTUAL_TAXA">
          </div>
        </th>
				<th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="VALOR_LIQUIDO_ACORDADO"
            >
              <p class="m-0">Valor Líquido Acordado R$</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="VALOR_LIQUIDO_ACORDADO">
          </div>
        </th>
				<th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="VALOR_LIQUIDO_PRATICADO"
            >
              <p class="m-0">Valor Líquido Praticado R$</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="VALOR_LIQUIDO_PRATICADO">
          </div>
        </th>
				<th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="VALOR_LIQUIDO"
            >
              <p class="m-0">Dif. Líquido R$</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="VALOR_LIQUIDO">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="POSSUI_TAXA_MINIMA"
            >
              <p class="m-0">Possui Tarifa Mínima</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="POSSUI_TAXA_MINIMA">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="PARCELA"
            >
              <p class="m-0">Parcela</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="PARCELA">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="TOTAL_PARCELAS"
            >
              <p class="m-0">Total Parc.</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="TOTAL_PARCELAS">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="ESTABELECIMENTO"
            >
              <p class="m-0">Estabelecimento</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="ESTABELECIMENTO">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="OBSERVACOES"
            >
              <p class="m-0">Observação</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="OBSERVACOES">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="PRODUTO"
            >
              <p class="m-0">Produto</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="PRODUTO">
          </div>
        </th>
        <th>
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="STATUS_CONCILIACAO"
            >
              <p class="m-0">Status Conciliação</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="STATUS_CONCILIACAO">
          </div>
         </th>
         @if($isColumnVisible('DIVERGENCIA'))
          <th>
            <div class="d-flex flex-column align-items-center">
              <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="DIVERGENCIA"
              >
                <p class="m-0">Divergência</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="text" class="form-control" name="DIVERGENCIA">
            </div>
          </th>
         @endif
				 @if($isColumnVisible('STATUS_FINANCEIRO'))
					<th>
						<div class="d-flex flex-column align-items-center">
							<div
							class="d-flex align-items-center justify-content-center table-sorter mb-2"
							data-tbsort-by="STATUS_FINANCEIRO"
							>
								<p class="m-0">Status Financeiro</p>
								<img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
							</div>
							<input type="text" class="form-control" name="STATUS_FINANCEIRO">
						</div>
					</th>
					@endif
					@if($isColumnVisible('JUSTIFICATIVA'))
					<th>
						<div class="d-flex flex-column align-items-center">
							<div
								class="d-flex align-items-center justify-content-center table-sorter mb-2"
								data-tbsort-by="JUSTIFICATIVA"
							>
								<p class="m-0">Justificativa</p>
								<img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
							</div>
							<input type="text" class="form-control" name="JUSTIFICATIVA">
						</div>
					</th>
					@endif
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
        <td data-column="CNPJ"></td>
        <td data-column="DATA_VENDA" data-format="date"></td>
        <td
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
        <td
          data-image="BANDEIRA_IMAGEM"
          data-default-image="assets/images/widgets/cards.svg"
          data-column="BANDEIRA"
          data-default-value="Sem identificação"
        >
          <div
            class="icon-image tooltip-hint tooltip-left"
            data-title="BANDEIRA"
            data-default-title="Sem identificação"
          >
          </div>
        </td>
        <td data-column="MODALIDADE"></td>
        <td data-column="NSU"></td>
        <td data-column="AUTORIZACAO"></td>
        <td data-column="TID"></td>
        <td data-column="VALOR_BRUTO" data-format="currency"></td>
				<td data-column="PERCENTUAL_TAXA_ACORDADA" data-format="number"></td>
        <td data-column="PERCENTUAL_TAXA" data-format="number"></td>
				<td data-column="PERCENTUAL_DIF_TAXA" data-format="number" data-color="diff"></td>
				<td data-column="VALOR_LIQUIDO_ACORDADO" data-format="currency"></td>
				<td data-column="VALOR_LIQUIDO_PRATICADO" data-format="currency"></td>
				<td data-column="DIF_TAXA" data-format="currency" data-color="diff"></td>
        <td data-column="POSSUI_TAXA_MINIMA"></td>
        <td data-column="PARCELA"></td>
        <td data-column="TOTAL_PARCELAS"></td>
        <td data-column="ESTABELECIMENTO"></td>
        <td data-column="OBSERVACOES"></td>
        <td data-column="PRODUTO"></td>
        <td data-column="STATUS_CONCILIACAO"></td>
        @if($isColumnVisible('DIVERGENCIA'))
          <td data-column="DIVERGENCIA"></td>
        @endif
				@if($isColumnVisible('STATUS_FINANCEIRO'))
        	<td data-column="STATUS_FINANCEIRO"></td>
				@endif
				@if($isColumnVisible('JUSTIFICATIVA'))
        	<td data-column="JUSTIFICATIVA"></td>
				@endif
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td>Totais</td>
        @isset($actions)
          <td></td>
        @endisset
        @if($isColumnVisible('ID_ERP'))
          <td></td>
        @endif
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td data-column="TOTAL_BRUTO" data-format="currency"></td>
        <td></td>
        <td></td>
        <td></td>
				<td data-column="TOTAL_TAXA_ACORDADA" data-format="currency">R$ 0</td>
        <td data-column="TOTAL_LIQUIDO" data-format="currency"></td>
				<td data-column="DIF_TAXA" data-format="currency"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        @if($isColumnVisible('DIVERGENCIA'))
          <td></td>
        @endif
        @if($isColumnVisible('STATUS_FINANCEIRO'))
          <td></td>
        @endif
				@if($isColumnVisible('JUSTIFICATIVA'))
          <td></td>
        @endif
				<td></td>
      </tr>
    </tfoot>
  </table>
</div>