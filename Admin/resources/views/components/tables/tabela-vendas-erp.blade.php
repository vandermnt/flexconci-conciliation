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
                <p class="m-0">{{ $getHeader('actions', 'Ações') }}</p>
              </div>
            </th>
          @endisset
          @if($isColumnVisible('ID_ERP'))
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
          <th class="draggable" data-tb-section="DATA_VENCIMENTO" data-th-title="Previsão">
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="DATA_VENCIMENTO"
              >
                <p class="m-0">Previsão</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="date" class="form-control" name="DATA_VENCIMENTO">
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
          <th class="draggable" data-tb-section="CODIGO_AUTORIZACAO" data-th-title="Autorização">
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="CODIGO_AUTORIZACAO"
              >
                <p class="m-0">Autorização</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="text" class="form-control" name="CODIGO_AUTORIZACAO">
            </div>
          </th>
          @if($isColumnVisible('TID'))
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
          @endif
          @if($isColumnVisible('CARTAO'))
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
          @endif
          <th class="draggable" data-tb-section="VALOR_VENDA" data-th-title="Valor Bruto">
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="VALOR_VENDA"
              >
                <p class="m-0">Valor Bruto</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="number" min="0" step="0.01" class="form-control" name="VALOR_VENDA">
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
          <th class="draggable" data-tb-section="TAXA" data-th-title="{{ $getHeader('TAXA', 'Taxa %') }}">
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="TAXA"
              >
                <p class="m-0">
                  {{ $getHeader('TAXA', 'Taxa %') }}
                </p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="number" min="0" step="0.01" class="form-control" name="TAXA">
            </div>
          </th>
          @if($isColumnVisible('TAXA_OPERADORA'))
          <th class="draggable" data-tb-section="TAXA_OPERADORA" data-th-title="Taxa Op. %">
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="TAXA_OPERADORA"
              >
                <p class="m-0">Taxa Op. %</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="number" min="0" step="0.01" class="form-control" name="TAXA_OPERADORA">
            </div>
          </th>
          @endif
          @if($isColumnVisible('TAXA_DIFERENCA'))
            <th class="draggable" data-tb-section="TAXA_DIFERENCA" data-th-title="Dif. Taxa %">
              <div class="d-flex flex-column align-items-center">
                <div
                  class="d-flex align-items-center justify-content-center table-sorter mb-2"
                  data-tbsort-by="TAXA_DIFERENCA"
                >
                  <p class="m-0">Dif. Taxa %</p>
                  <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
                </div>
                <input type="number" step="0.01" class="form-control" name="TAXA_DIFERENCA">
              </div>
            </th>
          @endif
          <th class="draggable" data-tb-section="VALOR_LIQUIDO_PARCELA" data-th-title="{{ $getHeader('VALOR_LIQUIDO', 'Valor Líquido') }}">
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="VALOR_LIQUIDO_PARCELA"
              >
                <p class="m-0">
                  {{ $getHeader('VALOR_LIQUIDO', 'Valor Líquido') }}
                </p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="number" min="0" step="0.01" class="form-control" name="VALOR_LIQUIDO_PARCELA">
            </div>
          </th>
          @if($isColumnVisible('VALOR_LIQUIDO_OPERADORA'))
            <th class="draggable" data-tb-section="VALOR_LIQUIDO_OPERADORA" data-th-title="Valor Líquido Op.">
              <div class="d-flex flex-column align-items-center">
                <div
                  class="d-flex align-items-center justify-content-center table-sorter mb-2"
                  data-tbsort-by="VALOR_LIQUIDO_OPERADORA"
                >
                  <p class="m-0">Valor Líquido Op.</p>
                  <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
                </div>
                <input type="number" min="0" step="0.01" class="form-control" name="VALOR_LIQUIDO_OPERADORA">
              </div>
            </th>
          @endif
          @if($isColumnVisible('DIFERENCA_LIQUIDO'))
            <th class="draggable" data-tb-section="DIFERENCA_LIQUIDO" data-th-title="Dif. Líquido R$">
              <div class="d-flex flex-column align-items-center">
                <div
                  class="d-flex align-items-center justify-content-center table-sorter mb-2"
                  data-tbsort-by="DIFERENCA_LIQUIDO"
                >
                  <p class="m-0">Dif. Líquido R$</p>
                  <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
                </div>
                <input type="number" step="0.01" class="form-control" name="DIFERENCA_LIQUIDO">
              </div>
            </th>
          @endif
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
          @if($isColumnVisible('HORA'))
            <th class="draggable" data-tb-section="HORA" data-th-title="Hora">
              <div class="d-flex flex-column align-items-center">
                <div
                  class="d-flex align-items-center justify-content-center table-sorter mb-2"
                  data-tbsort-by="HORA"
                >
                  <p class="m-0">Hora</p>
                  <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
                </div>
                <input type="text" class="form-control" name="HORA">
              </div>
            </th>
          @endif
          @if($isColumnVisible('ESTABELECIMENTO'))
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
          @endif
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
          <th class="draggable" data-tb-section="CONTA_CORRENTE" data-th-title="Conta">
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="CONTA_CORRENTE"
              >
                <p class="m-0">Conta</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="text" class="form-control" name="CONTA_CORRENTE">
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
           <th class="draggable" data-tb-section="CAMPO1" data-th-title="{{ $getHeader('TITULO_CAMPO1', 'Campo 1') }}">
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="CAMPO1"
              >
                <p class="m-0">
                  {{ $getHeader('TITULO_CAMPO1', 'Campo 1') }}
                </p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="text" class="form-control" name="CAMPO1">
            </div>
          </th>
          <th class="draggable" data-tb-section="CAMPO2" data-th-title="{{ $getHeader('TITULO_CAMPO2', 'Campo 2') }}">
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="CAMPO2"
              >
                <p class="m-0">
                  {{ $getHeader('TITULO_CAMPO2', 'Campo 2') }}
                </p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="text" class="form-control" name="CAMPO2">
            </div>
          </th>
          <th class="draggable" data-tb-section="CAMPO3" data-th-title="{{ $getHeader('TITULO_CAMPO3', 'Campo 3') }}">
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="CAMPO3"
              >
                <p class="m-0">
                  {{ $getHeader('TITULO_CAMPO3', 'Campo 3') }}
                </p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="text" class="form-control" name="CAMPO3">
            </div>
          </th>
          @if($isColumnVisible('RETORNO_ERP'))

            <th class="draggable" data-tb-section="RETORNO_ERP" data-th-title="{{ $getHeader('RETORNO_ERP', 'Retorno Venda '.($erp->ERP ?? 'ERP')) }}">
              <div class="d-flex flex-column align-items-center">
                <div
                  class="d-flex align-items-center justify-content-center table-sorter mb-2"
                  data-tbsort-by="RETORNO_ERP"
                >
                  <p class="m-0">{{ $getHeader('RETORNO_ERP', 'Retorno Venda '.($erp->ERP ?? 'ERP')) }}</p>
                  <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
                </div>
                <input type="text" class="form-control" name="RETORNO_ERP">
              </div>
            </th>
          @endif
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
          <th class="draggable" data-tb-section="DATA_CONCILIACAO" data-th-title="Data Conciliação">
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="DATA_CONCILIACAO"
              >
                <p class="m-0">Data Conciliação</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="date" class="form-control" name="DATA_CONCILIACAO">
            </div>
          </th>
          <th class="draggable" data-tb-section="HORA_CONCILIACAO" data-th-title="Hora Conciliação">
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="HORA_CONCILIACAO"
              >
                <p class="m-0">Hora Conciliação</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="text" class="form-control" name="HORA_CONCILIACAO">
            </div>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr data-id="ID_ERP" class="table-row-template hidden">
          @isset($actions)
            {{ $actions }}
          @endisset
          @if($isColumnVisible('ID_ERP'))
            <td data-tb-section="DESCRICAO_ERP" data-column="DESCRICAO_ERP"></td>
          @endif
          <td data-tb-section="NOME_EMPRESA" data-column="NOME_EMPRESA"></td>
          <td data-tb-section="CNPJ" data-column="CNPJ"></td>
          <td data-tb-section="DATA_VENDA" data-column="DATA_VENDA" data-format="date"></td>
          <td data-tb-section="DATA_VENCIMENTO" data-column="DATA_VENCIMENTO" data-format="date"></td>
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
          <td data-tb-section="CODIGO_AUTORIZACAO" data-column="CODIGO_AUTORIZACAO"></td>
          @if($isColumnVisible('TID'))
            <td data-tb-section="TID" data-column="TID"></td>
          @endif
          @if($isColumnVisible('CARTAO'))
            <td data-tb-section="CARTAO" data-column="CARTAO"></td>
          @endif
          <td
            data-tb-section="VALOR_VENDA"
            data-column="VALOR_VENDA"
            data-format="currency"
          >
          </td>
          <td
            data-tb-section="VALOR_TAXA"
            class="text-danger"
            data-reverse-value="true"
            data-column="VALOR_TAXA"
            data-format="currency"
          >
          </td>
          <td
            data-tb-section="TAXA"
            class="text-danger"
            data-column="TAXA"
            data-format="number"
          >
          </td>
          @if($isColumnVisible('TAXA_OPERADORA'))
            <td
              data-tb-section="TAXA_OPERADORA"
              class="text-danger"
              data-column="TAXA_OPERADORA"
              data-format="number"
            >
            </td>
          @endif
          @if($isColumnVisible('TAXA_DIFERENCA'))
            <td
              data-tb-section="TAXA_DIFERENCA"
              class="text-danger"
              data-column="TAXA_DIFERENCA"
              data-format="number"
            >
            </td>
          @endif
          <td
            data-tb-section="VALOR_LIQUIDO_PARCELA"
            data-column="VALOR_LIQUIDO_PARCELA"
            data-format="currency"
          >
          </td>
          @if($isColumnVisible('VALOR_LIQUIDO_OPERADORA'))
            <td
              data-tb-section="VALOR_LIQUIDO_OPERADORA"
              data-column="VALOR_LIQUIDO_OPERADORA"
              data-format="currency"
            >
            </td>
          @endif
          @if($isColumnVisible('DIFERENCA_LIQUIDO'))
            <td
              data-tb-section="DIFERENCA_LIQUIDO"
              data-column="DIFERENCA_LIQUIDO"
              data-format="currency"
            >
            </td>
          @endif
          <td data-tb-section="PARCELA" data-column="PARCELA"></td>
          <td data-tb-section="TOTAL_PARCELAS" data-column="TOTAL_PARCELAS"></td>
          @if($isColumnVisible('HORA'))
            <td data-tb-section="HORA" data-column="HORA"></td>
          @endif
          @if($isColumnVisible('ESTABELECIMENTO'))
            <td data-tb-section="ESTABELECIMENTO" data-column="ESTABELECIMENTO"></td>
          @endif
          <td data-tb-section="BANCO"
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
          <td data-tb-section="CONTA_CORRENTE" data-column="CONTA_CORRENTE"></td>
          <td data-tb-section="PRODUTO" data-column="PRODUTO"></td>
          <td data-tb-section="MEIOCAPTURA" data-column="MEIOCAPTURA"></td>
          <td data-tb-section="STATUS_CONCILIACAO" data-column="STATUS_CONCILIACAO"></td>
          <td data-tb-section="DIVERGENCIA" data-column="DIVERGENCIA"></td>
          @if($isColumnVisible('STATUS_FINANCEIRO'))
            <td data-tb-section="STATUS_FINANCEIRO" data-column="STATUS_FINANCEIRO"></td>
          @endif
          <td data-tb-section="JUSTIFICATIVA" data-column="JUSTIFICATIVA"></td>
          <td data-tb-section="CAMPO1" data-column="CAMPO1"></td>
          <td data-tb-section="CAMPO2" data-column="CAMPO2"></td>
          <td data-tb-section="CAMPO3" data-column="CAMPO3"></td>
          @if($isColumnVisible('RETORNO_ERP'))
            <td data-tb-section="RETORNO_ERP" data-column="RETORNO_ERP"></td>
          @endif
          <td data-tb-section="DATA_IMPORTACAO" data-column="DATA_IMPORTACAO" data-format="date"></td>
          <td data-tb-section="HORA_IMPORTACAO" data-column="HORA_IMPORTACAO"></td>
          <td data-tb-section="DATA_CONCILIACAO" data-column="DATA_CONCILIACAO" data-format="date"></td>
          <td data-tb-section="HORA_CONCILIACAO" data-column="HORA_CONCILIACAO"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td>Totais</td>
          @isset($actions)
          @endisset
          @if($isColumnVisible('ID_ERP'))
            <td data-tb-section="DESCRICAO_ERP"></td>
          @endif
          <td data-tb-section="NOME_EMPRESA"></td>
          <td data-tb-section="CNPJ"></td>
          <td data-tb-section="DATA_VENDA"></td>
          <td data-tb-section="DATA_VENCIMENTO"></td>
          <td data-tb-section="ADQUIRENTE"></td>
          <td data-tb-section="BANDEIRA"></td>
          <td data-tb-section="MODALIDADE"></td>
          <td data-tb-section="NSU"></td>
          <td data-tb-section="CODIGO_AUTORIZACAO"></td>
          @if($isColumnVisible('TID'))
            <td data-tb-section="TID"></td>
          @endif
          @if($isColumnVisible('CARTAO'))
            <td data-tb-section="CARTAO"></td>
          @endif
          <td data-tb-section="VALOR_VENDA" data-column="TOTAL_BRUTO" data-format="currency"></td>
          <td data-tb-section="VALOR_TAXA" data-column="TOTAL_TAXA" data-reverse-value="true" data-format="currency" class="text-danger"></td>
          <td data-tb-section="TAXA"></td>
          @if($isColumnVisible('TAXA_OPERADORA'))
            <td data-tb-section="TAXA_OPERADORA"></td>
          @endif
          @if($isColumnVisible('TAXA_DIFERENCA'))
            <td data-tb-section="TAXA_DIFERENCA"></td>
            @endif
          <td data-tb-section="VALOR_LIQUIDO_PARCELA" data-column="TOTAL_LIQUIDO" data-format="currency"></td>
          @if($isColumnVisible('VALOR_LIQUIDO_OPERADORA'))
            <td data-tb-section="VALOR_LIQUIDO_OPERADORA" data-column="TOTAL_LIQUIDO_OPERADORA" data-format="currency"></td>
          @endif
          @if($isColumnVisible('DIFERENCA_LIQUIDO'))
            <td data-tb-section="DIFERENCA_LIQUIDO" data-column="TOTAL_DIFERENCA_LIQUIDO" data-format="currency"></td>
          @endif
          <td data-tb-section="PARCELA"></td>
          <td data-tb-section="TOTAL_PARCELAS"></td>
          @if($isColumnVisible('HORA'))
            <td data-tb-section="HORA"></td>
          @endif
          @if($isColumnVisible('ESTABELECIMENTO'))
            <td data-tb-section="ESTABELECIMENTO"></td>
          @endif
          <td data-tb-section="BANCO"></td>
          <td data-tb-section="AGENCIA"></td>
          <td data-tb-section="CONTA_CORRENTE"></td>
          <td data-tb-section="PRODUTO"></td>
          <td data-tb-section="MEIOCAPTURA"></td>
          <td data-tb-section="STATUS_CONCILIACAO"></td>
          <td data-tb-section="DIVERGENCIA"></td>
          @if($isColumnVisible('STATUS_FINANCEIRO'))
            <td data-tb-section="STATUS_FINANCEIRO"></td>
          @endif
          <td data-tb-section="JUSTIFICATIVA"></td>
          <td data-tb-section="CAMPO1"></td>
          <td data-tb-section="CAMPO2"></td>
          <td data-tb-section="CAMPO3"></td>
          @if($isColumnVisible('RETORNO_ERP'))
            <td data-tb-section="RETORNO_ERP"></td>
          @endif
          <td data-tb-section="DATA_IMPORTACAO"></td>
          <td data-tb-section="HORA_IMPORTACAO"></td>
          <td data-tb-section="DATA_CONCILIACAO"></td>
          <td data-tb-section="HORA_CONCILIACAO"></td>
        </tr>
      </tfoot>
    </table>
  </div>
