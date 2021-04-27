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
        <th class="draggable" data-tb-section="NOME_EMPRESA" data-th-title="Empresa">
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
        <th class="draggable" data-tb-section="CNPJ" data-th-title="CNPJ">
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
        <th class="draggable" data-tb-section="DATA_VENDA" data-th-title="Venda">
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
        <th class="draggable" data-tb-section="BANDEIRA" data-th-title="Bandeira">
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
        <th class="draggable" data-tb-section="MODALIDADE" data-th-title="Forma de Pagamento">
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
        <th class="draggable" data-tb-section="NSU" data-th-title="NSU">
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
        <th class="draggable" data-tb-section="AUTORIZACAO" data-th-title="Autorização">
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
        <th class="draggable" data-tb-section="TID" data-th-title="TID">
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
        <th class="draggable" data-tb-section="VALOR_BRUTO" data-th-title="Valor Bruto">
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
				<th class="draggable" data-tb-section="PERCENTUAL_TAXA_ACORDADA" data-th-title="Taxa Acordada %">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="VALOR_BRUTO"
            >
              <p class="m-0">Taxa Acordada %</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="PERCENTUAL_TAXA_ACORDADA">
          </div>
        </th>
        <th class="draggable" data-tb-section="PERCENTUAL_TAXA" data-th-title="Taxa Praticada %">
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
				<th class="draggable" data-tb-section="PERCENTUAL_DIF_TAXA" data-th-title="Dif Taxa %">
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
				<th class="draggable" data-tb-section="VALOR_LIQUIDO_ACORDADO" data-th-title="Valor Líquido Acordado R$">
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
				<th class="draggable" data-tb-section="VALOR_LIQUIDO_PRATICADO" data-th-title="Valor Líquido Praticado R$">
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
				<th class="draggable" data-tb-section="DIF_LIQUIDO" data-th-title="Dif. Líquido R$">
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
        <th class="draggable" data-tb-section="POSSUI_TAXA_MINIMA" data-th-title="Possui Tarifa Mínima">
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
        <th class="draggable" data-tb-section="PARCELA" data-th-title="Parcela">
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
        <th class="draggable" data-tb-section="TOTAL_PARCELAS" data-th-title="Total Parc.">
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
        <th class="draggable" data-tb-section="ESTABELECIMENTO" data-th-title="Estabelecimento">
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
        <th class="draggable" data-tb-section="OBSERVACOES" data-th-title="Observação">
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
        <th class="draggable" data-tb-section="PRODUTO" data-th-title="Produto">
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
        <th class="draggable" data-tb-section="STATUS_CONCILIACAO" data-th-title="Status Conciliação">
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
          <th class="draggable" data-tb-section="DIVERGENCIA" data-th-title="Divergência">
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
					<th class="draggable" data-tb-section="STATUS_FINANCEIRO" data-th-title="Status Financeiro">
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
					<th class="draggable" data-tb-section="JUSTIFICATIVA" data-th-title="Justificativa">
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
          <td data-tb-section="DESCRICAO_ERP" data-column="DESCRICAO_ERP"></td>
        @endif
        <td data-tb-section="NOME_EMPRESA" data-column="NOME_EMPRESA"></td>
        <td data-tb-section="CNPJ" data-column="CNPJ"></td>
        <td data-tb-section="DATA_VENDA" data-column="DATA_VENDA" data-format="date"></td>
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
        <td
          data-tb-section="BANDEIRA"
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
        <td data-tb-section="MODALIDADE" data-column="MODALIDADE"></td>
        <td data-tb-section="NSU" data-column="NSU"></td>
        <td data-tb-section="AUTORIZACAO" data-column="AUTORIZACAO"></td>
        <td data-tb-section="TID" data-column="TID"></td>
        <td data-tb-section="VALOR_BRUTO" data-column="VALOR_BRUTO" data-format="currency"></td>
				<td data-tb-section="PERCENTUAL_TAXA_ACORDADA" data-column="PERCENTUAL_TAXA_ACORDADA" data-format="number"></td>
        <td data-tb-section="PERCENTUAL_TAXA" data-column="PERCENTUAL_TAXA" data-format="number"></td>
				<td data-tb-section="PERCENTUAL_DIF_TAXA" data-column="PERCENTUAL_DIF_TAXA" data-format="number" data-color="diff"></td>
				<td data-tb-section="VALOR_LIQUIDO_ACORDADO" data-column="VALOR_LIQUIDO_ACORDADO" data-format="currency"></td>
				<td data-tb-section="VALOR_LIQUIDO_PRATICADO" data-column="VALOR_LIQUIDO_PRATICADO" data-format="currency"></td>
				<td data-tb-section="DIF_LIQUIDO" data-column="DIF_LIQUIDO" data-format="currency" data-color="diff"></td>
        <td data-tb-section="POSSUI_TAXA_MINIMA" data-column="POSSUI_TAXA_MINIMA"></td>
        <td data-tb-section="PARCELA" data-column="PARCELA"></td>
        <td data-tb-section="TOTAL_PARCELAS" data-column="TOTAL_PARCELAS"></td>
        <td data-tb-section="ESTABELECIMENTO" data-column="ESTABELECIMENTO"></td>
        <td data-tb-section="OBSERVACOES" data-column="OBSERVACOES"></td>
        <td data-tb-section="PRODUTO" data-column="PRODUTO"></td>
        <td data-tb-section="STATUS_CONCILIACAO" data-column="STATUS_CONCILIACAO"></td>
        @if($isColumnVisible('DIVERGENCIA'))
          <td data-tb-section="DIVERGENCIA" data-column="DIVERGENCIA"></td>
        @endif
				@if($isColumnVisible('STATUS_FINANCEIRO'))
        	<td data-tb-section="STATUS_FINANCEIRO" data-column="STATUS_FINANCEIRO"></td>
				@endif
				@if($isColumnVisible('JUSTIFICATIVA'))
        	<td data-tb-section="JUSTIFICATIVA" data-column="JUSTIFICATIVA"></td>
				@endif
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
        <td data-tb-section="NOME_EMPRESA"></td>
        <td data-tb-section="CNPJ"></td>
        <td data-tb-section="DATA_VENDA"></td>
        <td data-tb-section="ADQUIRENTE"></td>
        <td data-tb-section="BANDEIRA"></td>
        <td data-tb-section="MODALIDADE"></td>
        <td data-tb-section="NSU"></td>
        <td data-tb-section="AUTORIZACAO"></td>
        <td data-tb-section="TID"></td>
        <td data-tb-section="VALOR_BRUTO" data-column="TOTAL_BRUTO" data-format="currency"></td>
        <td data-tb-section="PERCENTUAL_TAXA_ACORDADA"></td>
        <td data-tb-section="PERCENTUAL_TAXA"></td>
        <td data-tb-section="PERCENTUAL_DIF_TAXA"></td>
				<td data-tb-section="VALOR_LIQUIDO_ACORDADO" data-column="TOTAL_TAXA_ACORDADA" data-format="currency">R$ 0</td>
        <td data-tb-section="VALOR_LIQUIDO_PRATICADO" data-column="TOTAL_LIQUIDO" data-format="currency"></td>
				<td data-tb-section="DIF_LIQUIDO" data-column="DIF_LIQUIDO" data-format="currency"></td>
        <td data-tb-section="POSSUI_TAXA_MINIMA"></td>
        <td data-tb-section="PARCELA"></td>
        <td data-tb-section="TOTAL_PARCELAS"></td>
        <td data-tb-section="ESTABELECIMENTO"></td>
        <td data-tb-section="OBSERVACOES"></td>
        <td data-tb-section="PRODUTO"></td>
        <td data-tb-section="STATUS_CONCILIACAO"></td>
        @if($isColumnVisible('DIVERGENCIA'))
          <td data-tb-section="DIVERGENCIA"></td>
        @endif
        @if($isColumnVisible('STATUS_FINANCEIRO'))
          <td data-tb-section="STATUS_FINANCEIRO"></td>
        @endif
				@if($isColumnVisible('JUSTIFICATIVA'))
				  <td data-tb-section="JUSTIFICATIVA"></td>
        @endif
      </tr>
    </tfoot>
  </table>
</div>
