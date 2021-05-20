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
        <th class="draggable" data-tb-section="DATA_PREVISAO" data-th-title="Previsão">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="DATA_PREVISAO"
            >
              <p class="m-0">Previsão</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="date" class="form-control" name="DATA_PREVISAO">
          </div>
        </th>
        <th class="draggable" data-tb-section="DATA_CANCELAMENTO" data-th-title="Cancelamento">
          <div class="d-flex flex-column align-items-center">
            <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="DATA_CANCELAMENTO"
            >
                <p class="m-0">Cancelamento</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="date" class="form-control" name="DATA_CANCELAMENTO">
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
        <th class="draggable" data-tb-section="CARTAO" data-th-title="Cartão">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="CARTAO"
            >
              <p class="m-0">Cartão</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="CARTAO">
          </div>
        </th>
				@if($isColumnVisible('RESUMO'))
					<th class="draggable" data-tb-section="RESUMO" data-th-title="Resumo">
						<div class="d-flex flex-column align-items-center">
							<div
								class="d-flex align-items-center justify-content-center table-sorter mb-2"
								data-tbsort-by="RESUMO"
							>
								<p class="m-0">Resumo</p>
								<img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
							</div>
							<input type="text" class="form-control" name="RESUMO">
						</div>
					</th>
				@endif
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
        <th class="draggable" data-tb-section="PERCENTUAL_TAXA" data-th-title="Taxa %">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="PERCENTUAL_TAXA"
            >
              <p class="m-0">Taxa %</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="PERCENTUAL_TAXA">
          </div>
        </th>
        <th class="draggable" data-tb-section="VALOR_TAXA" data-th-title="Taxa R$">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="VALOR_TAXA"
            >
              <p class="m-0">Taxa R$</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="VALOR_TAXA">
          </div>
        </th>
        <th class="draggable" data-tb-section="VALOR_LIQUIDO" data-th-title="Valor Líquido">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="VALOR_LIQUIDO"
            >
              <p class="m-0">Valor Líquido</p>
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
        <th class="draggable" data-tb-section="HORA_TRANSACAO" data-th-title="Hora">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="HORA_TRANSACAO"
            >
              <p class="m-0">Hora</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="HORA_TRANSACAO">
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
        <th class="draggable" data-tb-section="TERMINAL" data-th-title="Núm. Máquina">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="TERMINAL"
            >
              <p class="m-0">Núm. Máquina</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="TERMINAL">
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
        <th class="draggable" data-tb-section="AGENCIA" data-th-title="Agência">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="AGENCIA"
            >
              <p class="m-0">Agência</p>
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
        <th class="draggable" data-tb-section="MEIOCAPTURA" data-th-title="Meio de Captura">
          <div class="d-flex flex-column align-items-center">
            <div
              class="d-flex align-items-center justify-content-center table-sorter mb-2"
              data-tbsort-by="MEIOCAPTURA"
            >
              <p class="m-0">Meio de Captura</p>
              <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="MEIOCAPTURA">
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
        <td data-tb-section="DATA_PREVISAO" data-column="DATA_PREVISAO" data-format="date"></td>
        <td data-tb-section="DATA_CANCELAMENTO" data-column="DATA_CANCELAMENTO" data-format="date"></td>
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
        <td data-tb-section="CARTAO" data-column="CARTAO"></td>
				@if($isColumnVisible('RESUMO'))
					<td data-tb-section="RESUMO" data-column="RESUMO"></td>
				@endif
        <td data-tb-section="VALOR_BRUTO" data-column="VALOR_BRUTO" data-format="currency"></td>
        <td data-tb-section="PERCENTUAL_TAXA" data-column="PERCENTUAL_TAXA" data-format="number" class="text-danger" ></td>
        <td data-tb-section="VALOR_TAXA" data-column="VALOR_TAXA" data-reverse-value="true" data-format="currency" class="text-danger" ></td>
        <td data-tb-section="VALOR_LIQUIDO" data-column="VALOR_LIQUIDO" data-format="currency"></td>
        <td data-tb-section="POSSUI_TAXA_MINIMA" data-column="POSSUI_TAXA_MINIMA"></td>
        <td data-tb-section="PARCELA" data-column="PARCELA"></td>
        <td data-tb-section="TOTAL_PARCELAS" data-column="TOTAL_PARCELAS"></td>
        <td data-tb-section="HORA_TRANSACAO" data-column="HORA_TRANSACAO" data-format="time"></td>
        <td data-tb-section="ESTABELECIMENTO" data-column="ESTABELECIMENTO"></td>
        <td data-tb-section="TERMINAL" data-column="TERMINAL"></td>
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
            data-default-title="Sem identificação"
          >
          </div>
        </td>
        <td data-tb-section="AGENCIA" data-column="AGENCIA"></td>
        <td data-tb-section="CONTA" data-column="CONTA"></td>
        <td data-tb-section="OBSERVACOES" data-column="OBSERVACOES"></td>
        <td data-tb-section="PRODUTO" data-column="PRODUTO"></td>
        <td data-tb-section="MEIOCAPTURA" data-column="MEIOCAPTURA"></td>
        <td data-tb-section="STATUS_CONCILIACAO" data-column="STATUS_CONCILIACAO"></td>
        @if($isColumnVisible('DIVERGENCIA'))
          <td data-tb-section="DIVERGENCIA" data-column="DIVERGENCIA"></td>
        @endif
        <td data-tb-section="STATUS_FINANCEIRO" data-column="STATUS_FINANCEIRO"></td>
        <td data-tb-section="JUSTIFICATIVA" data-column="JUSTIFICATIVA"></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        @isset($actions)
          <td>Totais</td>
        @endisset
        @if($isColumnVisible('ID_ERP'))
          <td data-tb-section="DESCRICAO_ERP"></td>
        @endif
        <td data-tb-section="NOME_EMPRESA"></td>
        <td data-tb-section="CNPJ"></td>
        <td data-tb-section="DATA_VENDA"></td>
        <td data-tb-section="DATA_PREVISAO"></td>
        <td data-tb-section="DATA_CANCELAMENTO"></td>
        <td data-tb-section="ADQUIRENTE"></td>
        <td data-tb-section="BANDEIRA"></td>
        <td data-tb-section="MODALIDADE"></td>
        <td data-tb-section="NSU"></td>
        <td data-tb-section="AUTORIZACAO"></td>
        <td data-tb-section="TID"></td>
        <td data-tb-section="CARTAO"></td>
				@if($isColumnVisible('RESUMO'))
					<td data-tb-section="RESUMO"></td>
				@endif
        <td
          data-tb-section="VALOR_BRUTO"
          data-column="TOTAL_BRUTO"
          data-format="currency"
        >
        </td>
        <td data-tb-section="PERCENTUAL_TAXA"></td>
        <td
          data-tb-section="VALOR_TAXA"
          data-column="TOTAL_TAXA"
          data-reverse-value="true"
          data-format="currency"
          class="text-danger"
        >
        </td>
        <td
          data-tb-section="VALOR_LIQUIDO"
          data-column="TOTAL_LIQUIDO"
          data-format="currency"
        >
        </td>
        <td data-tb-section="POSSUI_TAXA_MINIMA"></td>
        <td data-tb-section="PARCELA"></td>
        <td data-tb-section="TOTAL_PARCELAS"></td>
        <td data-tb-section="HORA_TRANSACAO"></td>
        <td data-tb-section="ESTABELECIMENTO"></td>
        <td data-tb-section="TERMINAL"></td>
        <td data-tb-section="BANCO"></td>
        <td data-tb-section="AGENCIA"></td>
        <td data-tb-section="CONTA"></td>
        <td data-tb-section="OBSERVACOES"></td>
        <td data-tb-section="PRODUTO"></td>
        <td data-tb-section="MEIOCAPTURA"></td>
        <td data-tb-section="STATUS_CONCILIACAO"></td>
        @if($isColumnVisible('DIVERGENCIA'))
          <td data-tb-section="DIVERGENCIA"></td>
         @endif
        <td data-tb-section="STATUS_FINANCEIRO"></td>
        <td data-tb-section="JUSTIFICATIVA"></td>
      </tr>
    </tfoot>
  </table>
</div>
