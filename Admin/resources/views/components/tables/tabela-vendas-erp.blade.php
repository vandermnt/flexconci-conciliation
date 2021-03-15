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
                data-tbsort-by="DATA_VENCIMENTO"
              >
                <p class="m-0">Previsão</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="date" class="form-control" name="DATA_VENCIMENTO">
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
                data-tbsort-by="CODIGO_AUTORIZACAO"
              >
                <p class="m-0">Autorização</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="text" class="form-control" name="CODIGO_AUTORIZACAO">
            </div>
          </th>
          @if($isColumnVisible('TID'))
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
          @endif
          @if($isColumnVisible('CARTAO'))
            <th>
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
          <th>
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
          <th>
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
          <th>
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="TAXA"
              >
                <p class="m-0">
                  {{ is_null($getHeader('TAXA')) ?
                  'Taxa %' :
                  ucwords(mb_strtolower($getHeader('TAXA'), 'utf-8')) }}
                </p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="number" min="0" step="0.01" class="form-control" name="TAXA">
            </div>
          </th>
          @if($isColumnVisible('TAXA_OPERADORA'))
          <th>
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
            <th>
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
          <th>
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="VALOR_LIQUIDO_PARCELA"
              >
                <p class="m-0">
                  {{ is_null($getHeader('VALOR_LIQUIDO')) ?
                    'Valor Líquido' :
                    ucwords(mb_strtolower($getHeader('VALOR_LIQUIDO'), 'utf-8')) }}
                </p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="number" min="0" step="0.01" class="form-control" name="VALOR_LIQUIDO_PARCELA">
            </div>
          </th>
          @if($isColumnVisible('VALOR_LIQUIDO_OPERADORA'))
            <th>
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
            <th>
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
          @if($isColumnVisible('HORA'))
            <th>
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
          @endif
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
                <p class="m-0">Agência</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="text" class="form-control" name="AGENCIA">
            </div>
          </th>
          <th>
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
                data-tbsort-by="MEIOCAPTURA"
              >
                <p class="m-0">Meio de Captura</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="text" class="form-control" name="MEIOCAPTURA">
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
           <th>
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="CAMPO1"
              >
                <p class="m-0">
                  {{ is_null($getHeader('TITULO_CAMPO1')) ?
                    'Campo 1' :
                    ucwords(mb_strtolower($getHeader('TITULO_CAMPO1'), 'utf-8')) }}
                </p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="text" class="form-control" name="CAMPO1">
            </div>
          </th>
          <th>
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="CAMPO2"
              >
                <p class="m-0">
                  {{ is_null($getHeader('TITULO_CAMPO2')) ?
                    'Campo 2' :
                    ucwords(mb_strtolower($getHeader('TITULO_CAMPO2'), 'utf-8')) }}
                </p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="text" class="form-control" name="CAMPO2">
            </div>
          </th>
          <th>
            <div class="d-flex flex-column align-items-center">
              <div
                class="d-flex align-items-center justify-content-center table-sorter mb-2"
                data-tbsort-by="CAMPO3"
              >
                <p class="m-0">
                  {{ is_null($getHeader('TITULO_CAMPO3')) ?
                    'Campo 3' :
                    ucwords(mb_strtolower($getHeader('TITULO_CAMPO3'), 'utf-8')) }}
                </p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
              </div>
              <input type="text" class="form-control" name="CAMPO3">
            </div>
          </th>
          @if($isColumnVisible('RETORNO_ERP'))
            <th>
              <div class="d-flex flex-column align-items-center">
                <div
                  class="d-flex align-items-center justify-content-center table-sorter mb-2"
                  data-tbsort-by="RETORNO_ERP"
                >
                  <p class="m-0">Retorno Venda {{ $erp->ERP ?? 'ERP' }}</p>
                  <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
                </div>
                <input type="text" class="form-control" name="RETORNO_ERP">
              </div>
            </th>
          @endif
          <th>
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
          <th>
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
          <th>
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
          <th>
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
            <td data-column="DESCRICAO_ERP"></td>
          @endif
          <td data-column="NOME_EMPRESA"></td>
          <td data-column="CNPJ"></td>
          <td data-column="DATA_VENDA" data-format="date"></td>
          <td data-column="DATA_VENCIMENTO" data-format="date"></td>
          <td
            data-image="ADQUIRENTE_IMAGEM"
            data-default-image="assets/images/widgets/cards.svg"
            data-column="ADQUIRENTE"
            data-default-value="Sem identificação"
          >
            <div
              class="icon-image tooltip-hint"
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
              class="icon-image tooltip-hint"
              data-title="BANDEIRA"
              data-default-title="Sem identificação"
            >
            </div>
          </td>
          <td data-column="MODALIDADE"></td>
          <td data-column="NSU"></td>
          <td data-column="CODIGO_AUTORIZACAO"></td>
          @if($isColumnVisible('TID'))
            <td data-column="TID"></td>
          @endif
          @if($isColumnVisible('CARTAO'))
            <td data-column="CARTAO"></td>
          @endif
          <td data-column="VALOR_VENDA" data-format="currency"></td>
          <td class="text-danger" data-reverse-value="true" data-column="VALOR_TAXA" data-format="currency"></td>
          <td class="text-danger" data-column="TAXA" data-format="number"></td>
          @if($isColumnVisible('TAXA_OPERADORA'))
            <td class="text-danger" data-column="TAXA_OPERADORA" data-format="number"></td>
          @endif
          @if($isColumnVisible('TAXA_DIFERENCA'))
            <td class="text-danger" data-column="TAXA_DIFERENCA" data-format="number"></td>
          @endif
          <td data-column="VALOR_LIQUIDO_PARCELA" data-format="currency"></td>
          @if($isColumnVisible('VALOR_LIQUIDO_OPERADORA'))
            <td data-column="VALOR_LIQUIDO_OPERADORA" data-format="currency"></td>
          @endif
          @if($isColumnVisible('DIFERENCA_LIQUIDO'))
            <td data-column="DIFERENCA_LIQUIDO" data-format="currency"></td>
          @endif
          <td data-column="PARCELA"></td>
          <td data-column="TOTAL_PARCELAS"></td>
          @if($isColumnVisible('HORA'))
            <td data-column="HORA"></td>
          @endif
          @if($isColumnVisible('ESTABELECIMENTO'))
            <td data-column="ESTABELECIMENTO"></td>
          @endif
          <td
            data-image="BANCO_IMAGEM"
            data-default-image="assets/images/widgets/cards.svg"
            data-column="BANCO"
            data-default-value="Sem identificação"
          >
            <div
              class="icon-image tooltip-hint"
              data-title="BANCO"
              data-default-title="Sem identificação"
            >
            </div>
          </td>
          <td data-column="AGENCIA"></td>
          <td data-column="CONTA_CORRENTE"></td>
          <td data-column="PRODUTO"></td>
          <td data-column="MEIOCAPTURA"></td>
          <td data-column="STATUS_CONCILIACAO"></td>
          <td data-column="DIVERGENCIA"></td>
          <td data-column="STATUS_FINANCEIRO"></td>
          <td data-column="JUSTIFICATIVA"></td>
          <td data-column="CAMPO1"></td>
          <td data-column="CAMPO2"></td>
          <td data-column="CAMPO3"></td>
          @if($isColumnVisible('RETORNO_ERP'))
            <td data-column="RETORNO_ERP"></td>
          @endif
          <td data-column="DATA_IMPORTACAO" data-format="date"></td>
          <td data-column="HORA_IMPORTACAO"></td>
          <td data-column="DATA_CONCILIACAO" data-format="date"></td>
          <td data-column="HORA_CONCILIACAO"></td>
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
          @if($isColumnVisible('TID'))
            <td></td>
          @endif
          @if($isColumnVisible('CARTAO'))
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
          <td data-column="TOTAL_TAXA" data-reverse-value="true" data-format="currency" class="text-danger"></td>
          <td></td>
          @if($isColumnVisible('TAXA_OPERADORA'))
            <td></td>
          @endif
          @if($isColumnVisible('TAXA_DIFERENCA'))
            <td></td>
          @endif
          <td data-column="TOTAL_LIQUIDO" data-format="currency"></td>
          @if($isColumnVisible('VALOR_LIQUIDO_OPERADORA'))
            <td data-column="TOTAL_LIQUIDO_OPERADORA" data-format="currency"></td>
          @endif
          @if($isColumnVisible('DIFERENCA_LIQUIDO'))
            <td data-column="TOTAL_DIFERENCA_LIQUIDO" data-format="currency"></td>
          @endif
          @if($isColumnVisible('HORA'))
            <td></td>
          @endif
          @if($isColumnVisible('ESTABELECIMENTO'))
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
          @if($isColumnVisible('RETORNO_ERP'))
            <td></td>
          @endif
        </tr>
      </tfoot>
    </table>
  </div>
