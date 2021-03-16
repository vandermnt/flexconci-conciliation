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
                data-tbsort-by="DATA_PREVISAO"
            >
                <p class="m-0">Previsão</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="date" class="form-control" name="DATA_PREVISAO">
          </div>
        </th>
        <th>
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
                data-tbsort-by="TIPO_PAGAMENTO"
            >
                <p class="m-0">Tipo Recebimento</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="TIPO_PAGAMENTO">
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
                data-tbsort-by="CARTAO"
            >
                <p class="m-0">Cartão</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="CARTAO">
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
                data-tbsort-by="TAXA_PERCENTUAL"
            >
                <p class="m-0">Taxa %</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="TAXA_PERCENTUAL">
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
                data-tbsort-by="TAXA_ANTECIPACAO_PERCENTUAL"
            >
                <p class="m-0">Taxa Antec. %</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="number" min="0" step="0.01" class="form-control" name="TAXA_ANTECIPACAO_PERCENTUAL">
          </div>
        </th>
        <th>
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
                data-tbsort-by="TERMINAL"
            >
                <p class="m-0">Núm. Máquina</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="TERMINAL">
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
                <p class="m-0">Divergência Venda</p>
                <img class="ml-2 table-sort-icon" alt="Arrows" data-sort-order="none">
            </div>
            <input type="text" class="form-control" name="DIVERGENCIA">
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
        <td data-column="DESCRICAO_ERP"></td>
        <td data-column="NOME_EMPRESA"></td>
        <td data-column="CNPJ"></td>
        <td data-column="DATA_VENDA" data-format="date"></td>
        <td data-column="DATA_PREVISAO" data-format="date"></td>
        <td data-column="DATA_PAGAMENTO" data-format="date"></td>
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
        <td data-column="TIPO_PAGAMENTO"></td>
        <td data-column="NSU"></td>
        <td data-column="AUTORIZACAO"></td>
        <td data-column="TID"></td>
        <td data-column="CARTAO"></td>
        <td data-column="VALOR_BRUTO" data-format="currency"></td>
        <td class="text-danger" data-column="TAXA_PERCENTUAL" data-format="number"></td>
        <td class="text-danger" data-reverse-value="true" data-column="VALOR_TAXA" data-format="currency"></td>
        <td class="text-danger" data-reverse-value="true" data-column="TAXA_ANTECIPACAO_PERCENTUAL" data-format="number"></td>
        <td data-column="VALOR_LIQUIDO" data-format="currency"></td>
        <td data-column="POSSUI_TAXA_MINIMA"></td>
        <td data-column="PARCELA"></td>
        <td data-column="TOTAL_PARCELAS"></td>
        <td data-column="ESTABELECIMENTO"></td>
        <td data-column="TERMINAL"></td>
        <td
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
        <td data-column="AGENCIA"></td>
        <td data-column="CONTA"></td>
        <td data-column="OBSERVACOES"></td>
        <td data-column="PRODUTO"></td>
        <td data-column="MEIOCAPTURA"></td>
        <td data-column="STATUS_CONCILIACAO"></td>
        <td data-column="DIVERGENCIA"></td>
        <td data-column="JUSTIFICATIVA"></td>
        <td data-column="RETORNO_ERP_BAIXA"></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td>Totais</td>
        @isset($actions)
          <td></td>
        @endisset
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
        <td data-column="TOTAL_BRUTO" data-format="currency"></td>
        <td></td>
        <td data-column="TOTAL_TAXA" data-reverse-value="true" data-format="currency" class="text-danger"></td>
        <td></td>
        <td data-column="TOTAL_LIQUIDO" data-format="currency"></td>
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
      </tr>
    </tfoot>
  </table>
</div>
