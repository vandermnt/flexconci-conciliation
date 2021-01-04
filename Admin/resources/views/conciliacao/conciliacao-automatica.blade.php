@extends('layouts.analytics-master')

@section('headerStyle')
  <link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/conciliacao/pagina-conciliacao-automatica.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
  <main id="pagina-conciliacao" class="container-fluid hidden">
    <header>
      @component('common-components.breadcrumb')
        @slot('title') Conciliação Automática de Vendas @endslot
        @slot('item1') Conciliação @endslot
      @endcomponent
    </header>

    <form 
      id="js-form-pesquisar"
      data-url-erp="{{ route('conciliacao-automatica.busca.erp') }}"
      data-url-operadoras="{{ route('conciliacao-automatica.busca.operadoras') }}"
      data-url-filtrar-erp="{{ route('conciliacao-automatica.filtrar.erp') }}"
      data-url-filtrar-operadoras="{{ route('conciliacao-automatica.filtrar.operadoras') }}"
      data-url-conciliar-manualmente="{{ route('conciliacao-automatica.conciliar.manualmente') }}"
      data-url-justificar="{{ route('conciliacao-automatica.conciliar.justificar') }}"
      data-url-exportar-erp="{{ route('conciliacao-automatica.exportar.erp') }}"
      data-url-exportar-operadoras="{{ route('conciliacao-automatica.exportar.operadoras') }}"
      class="card" method="POST"
    >
      <div class="card-body">
        @csrf
        <div class="row">
          <div class="col-12 col-sm-6">
            <div class="d-flex align-items-center justify-content-between filtro-datas">
              <div class="form-group flex-grow-1">
                <label for="data-inicial">Data Inicial:</label>
                <input
                  type="date"
                  id="data-inicial"
                  name="data_inicial"
                  class="form-control"
                  value="{{ date("Y-m-01") }}"
                  required
                >
              </div>
              <div class="form-group flex-grow-1">
                <label for="data-final">Data Final:</label>
                <input
                  type="date"
                  id="data-final"
                  name="data_final"
                  class="form-control"
                  value="{{ date("Y-m-d") }}"
                  required
                >
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="empresa">Empresa:</label>
          <div class="input-group m-0">
            <div class="col-sm-6 d-flex align-items-center pl-0 form-input">
              <input
                type="text"
                id="empresa"
                class="form-control"
                data-group="empresa"
                data-checker="to-text-element"
              >
            </div>
            <div class="col-12 col-sm-4 col-md-2 d-flex align-items-center pr-0 form-button">
              <button
                type="button"
                class="btn btn-sm"
                data-toggle="modal"
                data-target="#empresas-modal"
              >
                Selecionar
              </button>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12 col-sm-8">
            <div class="form-group">
              <label for="status-conciliacao">
                Status Conciliação:
              </label>
              <div class="d-flex align-items-center flex-wrap">
                @foreach($status_conciliacao as $status)
                  <div class="check-group">
                    <input
                      id="status-conciliacao-{{ $status->CODIGO }}"
                      type="checkbox"
                      name="status_conciliacao[]"
                      value="{{ $status->CODIGO }}"
                      data-group="status-conciliacao"
                      data-checker="checkbox"
                      data-status="{{ mb_strtolower($status->STATUS_CONCILIACAO, 'UTF-8') }}"
                      checked
                    >
                    <label
                      for="status-conciliacao-{{ $status->CODIGO }}"
                    >
                      {{ $status->STATUS_CONCILIACAO }}
                    </label>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        
        <div class="d-flex justify-content-end align-items-center acoes flex-wrap">
          <button
            id="js-reset-form"
            class="btn btn-sm"
            type="button"
          >
            <i class="far fa-trash-alt"></i>
            Limpar Campos
          </button>


          <button
            id="js-pesquisar"
            class="btn btn-sm ml-1"
            type="submit"
          >
            <i class="fas fa-search"></i>
            Pesquisar
          </button>
        </div>
      </div>

      <div class="modais">
        <div
          id="empresas-modal"
          class="modal fade"
          role="dialog"
          tabindex="-1"
          data-backdrop="static"
          data-keyboard="false"
          aria-labelledby="empresas-label"
          aria-hidden="true"
        >
          <div class="modal-dialog">
            <div class="modal-content">
              <header class="modal-header d-flex align-items-center">
                <h5 class="modal-title" id="empresas-label">Empresa</h5>
                <button
                  class="close"
                  type="button"
                  data-dismiss="modal"
                  data-acao="cancelar"
                  data-group="empresa"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </header>
              <main class="modal-body">
                <div class="form-group">
                  <h6>Pesquisar</h6>
                  <input
                    data-filter-group="empresa"
                    data-filter-fields="cnpj,empresa"
                    class="form-control"
                    type="text"
                  >
                </div>
                <div class="modal-checkboxes">
                  <div class="row">
                    <div class="col-sm-6 pl-0">
                      <p>Empresa</p>
                    </div>
                    <div class="col-sm-4 px-0">
                      <p>CNPJ</p>
                    </div>
                    <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
                      <input
                        type="checkbox"
                        data-group="empresa"
                        data-checker="global"
                      >
                    </div>
                  </div>
                  @isset($empresas)
                    @foreach($empresas as $empresa)
                      <div
                        class="row"
                        data-filter-item-container="empresa"
                        data-filter-empresa="{{ $empresa->NOME_EMPRESA }}"
                        data-filter-cnpj="{{ $empresa->CNPJ }}"   
                      >
                        <div class="col-sm-6 pl-0">
                          <p>{{ $empresa->NOME_EMPRESA }}</p>
                        </div>
                        <div class="col-sm-4 px-0">
                          <p>{{ $empresa->CNPJ }}</p>
                        </div>
                        <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
                          <input
                            type="checkbox"
                            name="grupos_clientes[]"
                            value="{{ $empresa->CODIGO }}"
                            data-checker="checkbox"
                            data-group="empresa"
                            data-descricao="{{ $empresa->NOME_EMPRESA }}"
                          >
                        </div>
                      </div>
                    @endforeach
                  @endisset
                </div>
              </main>
              <footer class="modal-footer">
                <button
                  type="button"
                  class="btn btn-danger font-weight-bold"
                  data-acao="cancelar"
                  data-group="empresa"
                  data-dismiss="modal"
                >
                  Cancelar
                </button>

                <button
                  type="button"
                  class="btn btn-success font-weight-bold"
                  data-acao="confirmar"
                  data-group="empresa"
                  data-dismiss="modal"
                >
                  Confirmar
                </button>
              </footer>
            </div>
          </div>
        </div>
      </div>
    </form>

    <section class="resultados hidden" id="js-resultados">
      <div class="boxes">
        <div class="card" data-status="*">
          <div class="card-body">
            <h6 class="text-dark text-center font-weight-semibold font-12">VENDAS ERP</h6>
            <div class="d-flex align-items-center justify-content-between">
              <p data-total="EPR_TOTAL_BRUTO">0</p>
              <img src="assets/images/conciliacao/vendaserp.png" alt="Vendas ERP">
            </div>
          </div>
        </div>
        <div class="card" data-status="conciliada">
          <div class="card-body">
            <h6 class="text-dark text-center font-weight-semibold font-12">CONCILIADO</h6>
            <div class="d-flex align-items-center justify-content-between">
              <p data-total="TOTAL_CONCILIADA">0</p>
              <img src="assets/images/conciliacao/conciliado.png" alt="Conciliado">
            </div>
          </div>
        </div>
        <div class="card" data-status="divergente">
          <div class="card-body">
            <h6 class="text-dark text-center font-weight-semibold font-12">DIVERGENTE</h6>
            <div class="d-flex align-items-center justify-content-between">
              <p data-total="TOTAL_DIVERGENTE">0</p>
              <img src="assets/images/conciliacao/conciliadodiv.png" alt="Divergente">
            </div>
          </div>
        </div>
        <div class="card" data-status="conciliada manualmente">
          <div class="card-body">
            <h6 class="text-dark text-center font-weight-semibold font-12">CONC. MANUAL</h6>
            <div class="d-flex align-items-center justify-content-between">
              <p data-total="TOTAL_MANUAL">0</p>
              <img src="assets/images/conciliacao/conciliadomanualmente.png" alt="Conciliado Manualmente">
            </div>
          </div>
        </div>
        <div class="card" data-status="justificada">
          <div class="card-body">
            <h6 class="text-dark text-center font-weight-semibold font-12">JUSTIFICADO</h6>
            <div class="d-flex align-items-center justify-content-between">
              <p data-total="TOTAL_JUSTIFICADA">0</p>
              <img src="assets/images/conciliacao/justificado.png" alt="Justificado">
            </div>
          </div>
        </div>
        <div class="card" data-status="não conciliada">
          <div class="card-body">
            <h6 class="text-dark text-center font-weight-semibold font-12">PENDÊNCIAS ERP</h6>
            <div class="d-flex align-items-center justify-content-between">
              <p data-total="TOTAL_NAO_CONCILIADA">0</p>
              <img src="assets/images/conciliacao/vendaserpnotconc.png" alt="Pendências ERP">
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <h6 class="text-dark text-center font-weight-semibold font-12">PENDÊNCIAS OPER.</h6>
            <div class="d-flex align-items-center justify-content-between">
              <p data-total="OPERADORAS_TOTAL_BRUTO">0</p>
              <img src="assets/images/conciliacao/vendasoperadoranotconc.png" alt="Pendências Operadoras">
            </div>
          </div>
        </div>
      </div>
      <div class="vendas-erp">
        <div class="tabela-info d-flex align-items-center justify-content-between">
          <h4>Vendas {{ $erp }}</h4>
          <div class="acoes d-flex align-items-center justify-content-end">
            <button id="js-conciliar" class="btn mr-1">
              <i class="far fa-handshake"></i>
              Conciliar
            </button>
            <button
              class="btn mr-1"
              data-target="#js-abrir-justificar-modal"
            >
              <i class="far fa-flag"></i>
              Justificar
            </button>
            <button
              id="js-abrir-justificar-modal"
              class="hidden"
              type="button"
              data-toggle="modal"
              data-target="#js-justificar-modal"
            ></button>
            <button id="js-exportar-erp" class="btn">
              <i class="fas fa-file-download"></i>
              Exportar
            </button>
          </div>
        </div>
        <div class="tabela-wrapper">
          <table id="js-tabela-erp" class="table" data-modalidade="erp">
            <thead>
              <tr>
                <th>
                  <div class="d-flex flex-column justify-content-end">
                    <p class="m-0">Ações</p>
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Empresa</p>
                    <input type="text" class="form-control" name="NOME_EMPRESA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>CNPJ</p>
                    <input type="text" class="form-control" name="CNPJ">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Venda</p>
                    <input type="date" class="form-control" name="DATA_VENDA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Previsão</p>
                    <input type="date" class="form-control" name="DATA_VENCIMENTO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Operadora</p>
                    <input type="text" class="form-control" name="ADQUIRENTE">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Bandeira</p>
                    <input type="text" class="form-control" name="BANDEIRA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Forma de Pagamento</p>
                    <input type="text" class="form-control" name="MODALIDADE">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>NSU</p>
                    <input type="text" class="form-control" name="NSU">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Autorização</p>
                    <input type="text" class="form-control" name="CODIGO_AUTORIZACAO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>TID</p>
                    <input type="text" class="form-control" name="TID">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Cartão</p>
                    <input type="text" class="form-control" name="">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Valor Bruto</p>
                    <input type="number" min="0" step="0.01" class="form-control" name="TOTAL_VENDA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Taxa %</p>
                    <input type="number" min="0" step="0.01" class="form-control" name="TAXA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Taxa R$</p>
                    <input type="number" min="0" step="0.01" class="form-control" name="VALOR_TAXA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Valor Líquido</p>
                    <input type="number" min="0" step="0.01" class="form-control" name="VALOR_LIQUIDO_PARCELA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Parcela</p>
                    <input type="text" class="form-control" name="PARCELA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Total Parc.</p>
                    <input type="text" class="form-control" name="TOTAL_PARCELAS">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Hora</p>
                    <input type="text" class="form-control" name="">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Estabelecimento</p>
                    <input type="text" class="form-control" name="">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Banco</p>
                    <input type="text" class="form-control" name="BANCO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Agência</p>
                    <input type="text" class="form-control" name="AGENCIA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Conta</p>
                    <input type="text" class="form-control" name="CONTA_CORRENTE">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Produto</p>
                    <input type="text" class="form-control" name="PRODUTO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Meio de Captura</p>
                    <input type="text" class="form-control" name="MEIOCAPTURA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Status Conciliação</p>
                    <input type="text" class="form-control" name="STATUS_CONCILIACAO">
                  </div>
                 </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Status Financeiro</p>
                    <input type="text" class="form-control" name="STATUS_FINANCEIRO">
                  </div>
                 </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Justificativa</p>
                    <input type="text" class="form-control" name="JUSTIFICATIVA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Campo 1</p>
                    <input type="text" class="form-control" name="CAMPO1">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Campo 2</p>
                    <input type="text" class="form-control" name="CAMPO2">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Campo 3</p>
                    <input type="text" class="form-control" name="CAMPO3">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Data Importação</p>
                    <input type="text" class="form-control" name="">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Hora Importação</p>
                    <input type="text" class="form-control" name="">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Data Conciliação</p>
                    <input type="text" class="form-control" name="">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Hora Conciliação</p>
                    <input type="text" class="form-control" name="">
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr data-id="ID_ERP" class="hidden">
                <td>
                  <div class="d-flex align-items-center justify-content-between">
                    <input
                      name="id_erp[]"
                      type="checkbox"
                      data-campo="ID_ERP"
                    >
                    <img data-image="STATUS_CONCILIACAO_IMAGEM" data-text="STATUS_CONCILIACAO">
                  </div>
                </td>
                <td data-campo="NOME_EMPRESA"></td>
                <td data-campo="CNPJ"></td>
                <td data-campo="DATA_VENDA" data-format="date"></td>
                <td data-campo="DATA_VENCIMENTO" data-format="date"></td>
                <td>
                  <img data-image="ADQUIRENTE_IMAGEM" data-text="ADQUIRENTE">
                </td>
                <td>
                  <img data-image="BANDEIRA_IMAGEM" data-text="BANDEIRA">
                </td>
                <td data-campo="MODALIDADE"></td>
                <td data-campo="NSU"></td>
                <td data-campo="CODIGO_AUTORIZACAO"></td>
                <td data-campo="TID"></td>
                <td></td>
                <td data-campo="TOTAL_VENDA" data-format="currency"></td>
                <td data-campo="TAXA" data-format="decimal"></td>
                <td class="text-danger" data-campo="VALOR_TAXA" data-format="currency"></td>
                <td data-campo="VALOR_LIQUIDO_PARCELA" data-format="currency"></td>
                <td data-campo="PARCELA"></td>
                <td data-campo="TOTAL_PARCELAS"></td>
                <td></td>
                <td></td>
                <td data-campo="BANCO"></td>
                <td data-campo="AGENCIA"></td>
                <td data-campo="CONTA_CORRENTE"></td>
                <td data-campo="PRODUTO"></td>
                <td data-campo="MEIOCAPTURA"></td>
                <td data-campo="STATUS_CONCILIACAO"></td>
                <td data-campo="STATUS_FINANCEIRO"></td>
                <td data-campo="JUSTIFICATIVA"></td>
                <td data-campo="CAMPO1"></td>
                <td data-campo="CAMPO2"></td>
                <td data-campo="CAMPO3"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td>Totais</td>
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
                <td data-chave="TOTAL_BRUTO"></td>
                <td></td>
                <td data-chave="TOTAL_TAXA" class="text-danger"></td>
                <td data-chave="TOTAL_LIQUIDO"></td>
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
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <footer class="d-flex justify-content-between align-items-end flex-wrap">
          <nav class="nav-paginacao">
            <ul class="pagination" id="js-paginacao-erp">
              <li class="page-item active">
                <a href="" class="page-link">1</a>
              </li>
            </ul>
          </nav>
  
          <div class="form-group">
            <label for="js-porpagina-erp">Quantidade por página</label>
            <select data-vendas-tipo="erp" name="por_pagina" id="js-porpagina-erp" class="form-control">
              <option value="5" selected>5</option>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
              <option value="100">100</option>
              <option value="200">200</option>
            </select>
          </div>
        </footer>
      </div>

      <div class="pendencias-operadoras">
        <div class="tabela-info d-flex align-items-center justify-content-between">
          <h4>Pendências Operadoras</h4>
          <div class="acoes d-flex align-items-center justify-content-end">
            <button id="js-exportar-operadoras" class="btn">
              <i class="fas fa-file-download"></i>
              Exportar
            </button>
          </div>
        </div>
        <div class="tabela-wrapper">
          <table id="js-tabela-operadoras" class="table" data-modalidade="operadoras">
            <thead>
              <tr>
                <th>
                  <div class="d-flex flex-column justify-content-end">
                    <p class="m-0">Ações</p>
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Empresa</p>
                    <input type="text" class="form-control" name="NOME_EMPRESA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>CNPJ</p>
                    <input type="text" class="form-control" name="CNPJ">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Venda</p>
                    <input type="date" class="form-control" name="DATA_VENDA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Previsão</p>
                    <input type="date" class="form-control" name="DATA_PREVISAO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Operadora</p>
                    <input type="text" class="form-control" name="ADQUIRENTE">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Bandeira</p>
                    <input type="text" class="form-control" name="BANDEIRA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Forma de Pagamento</p>
                    <input type="text" class="form-control" name="MODALIDADE">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>NSU</p>
                    <input type="text" class="form-control" name="NSU">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Autorização</p>
                    <input type="text" class="form-control" name="AUTORIZACAO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Valor Bruto</p>
                    <input type="number" min="0" step="0.01" class="form-control" name="VALOR_BRUTO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Taxa %</p>
                    <input type="number" min="0" step="0.01" class="form-control" name="PERCENTUAL_TAXA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Taxa R$</p>
                    <input type="number" min="0" step="0.01" class="form-control" name="VALOR_TAXA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Valor Líquido</p>
                    <input type="number" min="0" step="0.01" class="form-control" name="VALOR_LIQUIDO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Parcela</p>
                    <input type="text" class="form-control" name="PARCELA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Total Parc.</p>
                    <input type="text" class="form-control" name="TOTAL_PARCELAS">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Hora</p>
                    <input type="text" class="form-control" name="HORA_TRANSACAO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Estabelecimento</p>
                    <input type="text" class="form-control" name="ESTABELECIMENTO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Banco</p>
                    <input type="text" class="form-control" name="BANCO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Agência</p>
                    <input type="text" class="form-control" name="AGENCIA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Conta</p>
                    <input type="text" class="form-control" name="CONTA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Observação</p>
                    <input type="text" class="form-control" name="OBSERVACOES">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Produto</p>
                    <input type="text" class="form-control" name="PRODUTO">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Meio de Captura</p>
                    <input type="text" class="form-control" name="MEIOCAPTURA">
                  </div>
                </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Status Conciliação</p>
                    <input type="text" class="form-control" name="STATUS_CONCILIACAO">
                  </div>
                 </th>
                <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Status Financeiro</p>
                    <input type="text" class="form-control" name="STATUS_FINANCEIRO">
                  </div>
                 </th>
                 <th>
                  <div class="d-flex flex-column align-items-center">
                    <p>Justificativa</p>
                    <input type="text" class="form-control" name="JUSTIFICATIVA">
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr data-id="ID" class="hidden">
                <td>
                  <div class="d-flex align-items-center justify-content-between">
                    <input
                      name="id_operadora[]"
                      type="checkbox"
                      data-campo="ID"
                    >
                    <img data-image="STATUS_CONCILIACAO_IMAGEM" data-text="STATUS_CONCILIACAO">
                  </div>
                </td>
                <td data-campo="NOME_EMPRESA"></td>
                <td data-campo="CNPJ"></td>
                <td data-campo="DATA_VENDA" data-format="date"></td>
                <td data-campo="DATA_PREVISAO" data-format="date"></td>
                <td>
                  <img data-image="ADQUIRENTE_IMAGEM" data-text="ADQUIRENTE">
                </td>
                <td>
                  <img data-image="BANDEIRA_IMAGEM" data-text="BANDEIRA">
                </td>
                <td data-campo="MODALIDADE"></td>
                <td data-campo="NSU"></td>
                <td data-campo="AUTORIZACAO"></td>
                <td data-campo="VALOR_BRUTO" data-format="currency"></td>
                <td data-campo="PERCENTUAL_TAXA" data-format="decimal"></td>
                <td class="text-danger" data-campo="VALOR_TAXA" data-format="currency"></td>
                <td data-campo="VALOR_LIQUIDO" data-format="currency"></td>
                <td data-campo="PARCELA"></td>
                <td data-campo="TOTAL_PARCELAS"></td>
                <td data-campo="HORA_TRANSACAO" data-format="time"></td>
                <td data-campo="ESTABELECIMENTO"></td>
                <td>
                  <img data-image="BANCO_IMAGEM" data-text="BANCO" src="" alt="">
                </td>
                <td data-campo="AGENCIA"></td>
                <td data-campo="CONTA"></td>
                <td data-campo="OBSERVACOES"></td>
                <td data-campo="PRODUTO"></td>
                <td data-campo="MEIOCAPTURA"></td>
                <td data-campo="STATUS_CONCILIACAO"></td>
                <td data-campo="STATUS_FINANCEIRO"></td>
                <td data-campo="JUSTIFICATIVA"></td>
              </tr>      
            </tbody>
            <tfoot>
              <tr>
                <td>Totais</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td data-chave="TOTAL_BRUTO"></td>
                <td></td>
                <td data-chave="TOTAL_TAXA" class="text-danger"></td>
                <td data-chave="TOTAL_LIQUIDO"></td>
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
        <footer class="d-flex justify-content-between align-items-end flex-wrap">
          <nav class="nav-paginacao">
            <ul class="pagination" id="js-paginacao-operadoras">
              <li class="page-item active">
                <a href="" class="page-link">1</a>
              </li>
            </ul>
          </nav>
  
          <div class="form-group">
            <label for="js-porpagina-operadoras">Quantidade por página</label>
            <select data-vendas-tipo="operadoras" name="por_pagina" id="js-porpagina-operadoras" class="form-control">
              <option value="5" selected>5</option>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
              <option value="100">100</option>
              <option value="200">200</option>
            </select>
          </div>
        </footer>
      </div>
      
      <div class="modais">
        <form
          id="js-justificar-modal"
          class="modal fade"
          role="dialog"
          tabindex="-1"
          data-backdrop="static"
          data-keyboard="false"
          aria-labelledby="justificar-label"
          aria-hidden="true"
        >
          <div class="modal-dialog">
            <div class="modal-content">
              <header class="modal-header d-flex align-items-center">
                <h5 class="modal-title" id="justificar-label">Justificar</h5>
                <button
                  class="close"
                  type="button"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </header>
              <main class="modal-body">
                <div class="form-group">
                  <h6>Justificativa</h6>
                  <input
                    name="justificativa"
                    class="form-control"
                    type="text"
                  >
                  <ul id="js-justificativas-lista" class="modal-options list-group mt-3">
                    @foreach ($justificativas as $justificativa)
                      <li class="list-group-item">{{ $justificativa->JUSTIFICATIVA }}</li>
                    @endforeach
                  </ul>
                </div>
              </main>
              <footer class="modal-footer">
                <button
                  id="js-cancelar-justificar"
                  type="reset"
                  class="btn btn-danger font-weight-bold"
                  data-dismiss="modal"
                >
                  Cancelar
                </button>
                <button
                  id="js-justificar"
                  type="button"
                  class="btn btn-success font-weight-bold"
                  data-dismiss="modal"
                >
                  Confirmar
                </button>
              </footer>
            </div>
          </div>
        </form>
      </div>

    </section>

  </main>

  <div id="js-loader" class="loader hidden"></div>
@endsection

@section('footerScript')
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="assets/js/lib/api.js"></script>
  <script src="assets/js/lib/pagination.js"></script>
  <script src="assets/js/lib/checker.js"></script>
  <script src="assets/js/lib/modal-filters.js"></script>
  <script src="assets/js/conciliacao/conciliacao-automatica.js"></script>
@endsection