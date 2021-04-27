<div class="table-responsive {{ $attributes->get('class') }}">
  <table
    class="table table-striped"
    id="{{ $attributes->get('id') ?? 'js-table' }}"
  >
    <thead>
      <tr>
        @isset($actions)
          <th>
            <div class="d-flex flex-column justify-content-end">
              <p class="m-0">Ações</p>
            </div>
          </th>
        @endisset
        <th class="draggable" data-tb-section="DESCRICAO_ERP" data-th-title="ID. ERP">
          <div class="d-flex flex-column align-items-center">
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
				<th class="draggable" data-tb-section="TIPO_LANCAMENTO" data-th-title="Tipo Lançamento">
          <div class="d-flex flex-column align-items-center">
            <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="TIPO_LANCAMENTO"
            >
                <p class="m-0">Tipo Lançamento</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="TIPO_LANCAMENTO">
          </div>
        </th>
				<th class="draggable" data-tb-section="TIPO_PAGAMENTO" data-th-title="Tipo Recebimento">
          <div class="d-flex flex-column align-items-center">
            <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="TIPO_PAGAMENTO"
            >
                <p class="m-0">Tipo Recebimento</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="TIPO_PAGAMENTO">
          </div>
        </th>
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
        <th class="draggable" data-tb-section="DATA_PAGAMENTO" data-th-title="Pagamento">
          <div class="d-flex flex-column align-items-center">
            <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="DATA_PAGAMENTO"
            >
                <p class="m-0">Pagamento</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="date" class="form-control" name="DATA_PAGAMENTO">
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
				<th class="draggable" data-tb-section="NUMERO_RESUMO_VENDA" data-th-title="Resumo">
          <div class="d-flex flex-column align-items-center">
            <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="NUMERO_RESUMO_VENDA"
            >
                <p class="m-0">Resumo</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="NUMERO_RESUMO_VENDA">
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
        <th class="draggable" data-tb-section="TAXA_PERCENTUAL" data-th-title="Taxa %">
          <div class="d-flex flex-column align-items-center">
            <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="TAXA_PERCENTUAL"
            >
                <p class="m-0">Taxa %</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="TAXA_PERCENTUAL">
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
        <th class="draggable" data-tb-section="TAXA_ANTECIPACAO" data-th-title="Taxa Antec. %">
          <div class="d-flex flex-column align-items-center">
            <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="TAXA_ANTECIPACAO"
            >
                <p class="m-0">Taxa Antec. %</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="TAXA_ANTECIPACAO">
          </div>
        </th>
				<th class="draggable" data-tb-section="VALOR_TAXA_ANTECIPACAO" data-th-title="Taxa Antec. R$">
          <div class="d-flex flex-column align-items-center">
            <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="VALOR_TAXA_ANTECIPACAO"
            >
                <p class="m-0">Taxa Antec. R$</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="VALOR_TAXA_ANTECIPACAO">
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
				<th class="draggable" data-tb-section="NUMERO_OPERACAO_ANTECIPACAO" data-th-title="Op. Antecipação">
          <div class="d-flex flex-column align-items-center">
            <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="NUMERO_OPERACAO_ANTECIPACAO"
            >
                <p class="m-0">Op. Antecipação</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="NUMERO_OPERACAO_ANTECIPACAO">
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
				<th class="draggable" data-tb-section="COD_AJUSTE" data-th-title="Cód. Ajuste">
          <div class="d-flex flex-column align-items-center">
            <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="COD_AJUSTE"
            >
                <p class="m-0">Cód. Ajuste</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="COD_AJUSTE">
          </div>
        </th>
				<th class="draggable" data-tb-section="DESC_AJUSTE" data-th-title="Desc. Ajuste">
          <div class="d-flex flex-column align-items-center">
            <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="DESC_AJUSTE"
            >
                <p class="m-0">Desc. Ajuste</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="DESC_AJUSTE">
          </div>
        </th>
				<th class="draggable" data-tb-section="CLASSIFICACAO_AJUSTE" data-th-title="Classificação Ajuste">
          <div class="d-flex flex-column align-items-center">
            <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="CLASSIFICACAO_AJUSTE"
            >
                <p class="m-0">Classificação Ajuste</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="CLASSIFICACAO_AJUSTE">
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
				@if(Auth::user()->USUARIO_GLOBAL === 'S')
					<th class="draggable" data-tb-section="STATUS_CONCILIACAO" data-th-title="Status Conciliação Rec">
						<div class="d-flex flex-column align-items-center">
							<div
									class="d-flex align-items-center justify-content-center table-sorter mb-2"
									data-tbsort-by="STATUS_CONCILIACAO"
							>
									<p class="m-0">Status Conciliação Rec</p>
									<img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
							</div>
							<input type="text" class="form-control" name="STATUS_CONCILIACAO">
						</div>
					</th>
				@endif
        <th class="draggable" data-tb-section="DIVERGENCIA" data-th-title="Divergência Venda">
          <div class="d-flex flex-column align-items-center">
            <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2 tooltip-hint"
                data-tbsort-by="DIVERGENCIA" data-title="Aqui ficam os campos que deram divergência quando conciliamos a
								venda do Seta versus operadora. Dependendo do campo que deu divervência
								não faremos a baixa no Seta."
            >
                <p class="m-0">Divergência Venda</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="DIVERGENCIA">
          </div>
         </th>
         <th class="draggable" data-tb-section="RETORNO_ERP_BAIXA" data-th-title="{{ $getHeader('RETORNO_ERP_BAIXA') ?? 'Retorno Recebimento ERP' }}">
          <div class="d-flex flex-column align-items-center">
            <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="RETORNO_ERP_BAIXA"
            >
                <p class="m-0">{{ $getHeader('RETORNO_ERP_BAIXA') ?? 'Retorno Recebimento ERP' }}</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="RETORNO_ERP_BAIXA">
          </div>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr data-id="ID" class="table-row-template hidden">
        @isset($actions)
          {{ $actions }}
        @endisset
        <td data-tb-section="DESCRICAO_ERP" data-column="DESCRICAO_ERP"></td>
				<td data-tb-section="TIPO_LANCAMENTO" data-column="TIPO_LANCAMENTO"></td>
				<td data-tb-section="TIPO_PAGAMENTO" data-column="TIPO_PAGAMENTO"></td>
        <td data-tb-section="NOME_EMPRESA" data-column="NOME_EMPRESA"></td>
        <td data-tb-section="CNPJ" data-column="CNPJ"></td>
        <td data-tb-section="DATA_VENDA" data-column="DATA_VENDA" data-format="date"></td>
        <td data-tb-section="DATA_PREVISAO" data-column="DATA_PREVISAO" data-format="date"></td>
        <td data-tb-section="DATA_PAGAMENTO" data-column="DATA_PAGAMENTO" data-format="date"></td>
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
				<td data-tb-section="NUMERO_RESUMO_VENDA" data-column="NUMERO_RESUMO_VENDA"></td>
        <td
          data-tb-section="VALOR_BRUTO"
          data-column="VALOR_BRUTO"
          data-format="currency"
        >
        </td>
        <td
          data-tb-section="TAXA_PERCENTUAL"
          data-column="TAXA_PERCENTUAL"
          data-format="number"
        >
        </td>
        <td
          data-tb-section="VALOR_TAXA"
          data-column="VALOR_TAXA"
          data-reverse-value="true"
          data-format="currency"
          class="text-danger"
        >
        </td>
        <td
          data-tb-section="TAXA_ANTECIPACAO"
          data-column="TAXA_ANTECIPACAO"
          data-format="number"
        >
        </td>
				<td
          data-tb-section="VALOR_TAXA_ANTECIPACAO"
          data-column="VALOR_TAXA_ANTECIPACAO"
          data-reverse-value="true"
          data-format="currency"
          class="text-danger"
        >
        </td>
        <td
          data-tb-section="VALOR_LIQUIDO"
          data-column="VALOR_LIQUIDO"
          data-format="currency"
        >
        </td>
				<td data-tb-section="NUMERO_OPERACAO_ANTECIPACAO" data-column="NUMERO_OPERACAO_ANTECIPACAO"></td>
        <td data-tb-section="POSSUI_TAXA_MINIMA" data-column="POSSUI_TAXA_MINIMA"></td>
        <td data-tb-section="PARCELA" data-column="PARCELA"></td>
        <td data-tb-section="TOTAL_PARCELAS" data-column="TOTAL_PARCELAS"></td>
        <td data-tb-section="ESTABELECIMENTO" data-column="ESTABELECIMENTO"></td>
				<td data-tb-section="COD_AJUSTE" data-column="COD_AJUSTE"></td>
				<td data-tb-section="DESC_AJUSTE" data-column="DESC_AJUSTE"></td>
				<td data-tb-section="CLASSIFICACAO_AJUSTE" data-column="CLASSIFICACAO_AJUSTE"></td>
        <td
          data-tb-section="BANCO"
          data-column="BANCO"
          data-image="BANCO_IMAGEM"
          data-default-image="assets/images/widgets/cards.svg"
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
				@if(Auth::user()->USUARIO_GLOBAL === 'S')
					<td data-tb-section="STATUS_CONCILIACAO" data-column="STATUS_CONCILIACAO"></td>
				@endif
        <td data-tb-section="DIVERGENCIA" data-column="DIVERGENCIA"></td>
        <td data-tb-section="RETORNO_ERP_BAIXA" data-column="RETORNO_ERP_BAIXA"></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        @isset($actions)
          <td>Totais</td>
        @endisset
				<td data-tb-section="DESCRICAO_ERP"></td>
				<td data-tb-section="TIPO_LANCAMENTO"></td>
        <td data-tb-section="TIPO_PAGAMENTO"></td>
        <td data-tb-section="NOME_EMPRESA"></td>
        <td data-tb-section="CNPJ"></td>
        <td data-tb-section="DATA_VENDA"></td>
        <td data-tb-section="DATA_PREVISAO"></td>
        <td data-tb-section="DATA_PAGAMENTO"></td>
        <td data-tb-section="ADQUIRENTE"></td>
        <td data-tb-section="BANDEIRA"></td>
        <td data-tb-section="MODALIDADE"></td>
        <td data-tb-section="NSU"></td>
        <td data-tb-section="AUTORIZACAO"></td>
        <td data-tb-section="TID"></td>
				<td data-tb-section="CARTAO"></td>
				<td data-tb-section="NUMERO_RESUMO_VENDA"></td>
        <td data-tb-section="VALOR_BRUTO" data-column="TOTAL_BRUTO" data-format="currency"></td>
        <td data-tb-section="TAXA_PERCENTUAL"></td>
        <td data-tb-section="VALOR_TAXA" data-column="TOTAL_TAXA" data-reverse-value="true" data-format="currency" class="text-danger"></td>
        <td data-tb-section="TAXA_ANTECIPACAO"></td>
				<td data-tb-section="VALOR_TAXA_ANTECIPACAO" class="text-danger" data-column="TOTAL_VALOR_TAXA_ANTECIPACAO" data-reverse-value="true" data-format="currency"></td>
        <td data-tb-section="VALOR_LIQUIDO" data-column="TOTAL_LIQUIDO" data-format="currency"></td>
        <td data-tb-section="NUMERO_OPERACAO_ANTECIPACAO"></td>
        <td data-tb-section="POSSUI_TAXA_MINIMA"></td>
        <td data-tb-section="PARCELA"></td>
        <td data-tb-section="TOTAL_PARCELAS"></td>
        <td data-tb-section="ESTABELECIMENTO"></td>
        <td data-tb-section="COD_AJUSTE"></td>
        <td data-tb-section="DESC_AJUSTE"></td>
        <td data-tb-section="CLASSIFICACAO_AJUSTE"></td>
        <td data-tb-section="BANCO"></td>
        <td data-tb-section="AGENCIA"></td>
        <td data-tb-section="CONTA"></td>
        <td data-tb-section="OBSERVACOES"></td>
        <td data-tb-section="PRODUTO"></td>
        <td data-tb-section="MEIOCAPTURA"></td>
				@if(Auth::user()->USUARIO_GLOBAL === 'S')
        <td data-tb-section="STATUS_CONCILIACAO"></td>
				@endif
        <td data-tb-section="DIVERGENCIA"></td>
        <td data-tb-section="RETORNO_ERP_BAIXA"></td>
      </tr>
    </tfoot>
  </table>
</div>
