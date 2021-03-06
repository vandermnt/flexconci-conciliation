@extends('layouts.analytics-master')

@section('headerStyle')
  <link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/globals/global.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="{{ URL::asset('assets/css/conciliacao/pagina-conciliacao-vendas.css') }}" type="text/css">
@endsection

@section('content')
  <main id="pagina-conciliacao-vendas" class="container-fluid">
    <header>
      @component('common-components.breadcrumb')
        @slot('title') Conciliação Automática de Vendas @endslot
        @slot('item1') Conciliação @endslot
      @endcomponent
    </header>

    <div class="card">
      <div class="card-body">
        <x-forms.search-form
          id="js-form-pesquisa"
          :urls="[
            ['buscar-erp' => route('conciliacao-vendas.buscarErp')],
            ['filtrar-erp' => route('conciliacao-vendas.filtrarErp')],
            ['exportar-erp' => route('conciliacao-vendas.exportar.erp')],
            ['buscar-operadoras' => route('conciliacao-vendas.buscarOperadoras')],
            ['filtrar-operadoras' => route('conciliacao-vendas.filtrarOperadoras')],
            ['exportar-operadoras' => route('conciliacao-vendas.exportar.operadoras')],
            ['conciliar-manualmente' => route('conciliacao-vendas.conciliarManualmente')],
            ['desconciliar-manualmente' => route('conciliacao-vendas.desconciliarManualmente')],
            ['justificar-erp' => route('vendas-erp.justify')],
            ['retorno-erp' => route('vendas-erp.retorno-erp')],
            ['desjustificar-erp' => route('vendas-erp.unjustify')],
            ['justificar-operadoras' => route('vendas-operadoras.justify')],
          ]"
          :hidden-fields="[
            'bandeiras',
            'modalidades',
            'estabelecimentos',
            'domicilios-bancarios',
            'descricao-erp',
            'status-financeiro',
          ]"
          :form-data="[
            'empresas' => $empresas,
						'adquirentes' => $adquirentes,
            'status_conciliacao' => $status_conciliacao,
          ]"
        />
      </div>
    </div>

    <div class="resultados hidden">
      <div class="boxes">
        <x-box
          class="tooltip-hint"
          :title="'VENDAS '.($erp->ERP ? mb_strtoupper($erp->ERP, 'utf-8') : 'SISTEMA')"
          content="R$ 18.434,51"
          data-format="currency"
          data-key="TOTAL_BRUTO"
          icon-path="assets/images/widgets/notebook.svg"
          icon-description="Vendas ERP"
          :dataset="[
            'hint' => 'Total de vendas do seu sistema.',
            'status' => '*'
          ]"
        />
        <x-box
          class="tooltip-hint"
          title="CONCILIADAS"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_CONCILIADO"
          icon-path="assets/images/widgets/check.svg"
          icon-description="Conciliado"
          :dataset="[
            'hint' => 'Total de vendas do seu sistema que foram conciliadas sem nenhuma divergência.',
            'status' => '1',
          ]"
        />
        <x-box
          class="tooltip-hint"
          title="DIVERGENTES"
          content="R$ 16.518,46"
          data-format="currency"
          data-key="TOTAL_DIVERGENTE"
          icon-path="assets/images/widgets/x.svg"
          icon-description="Divergente"
          :dataset="[
            'hint' => 'Total de vendas do seu sistema que foram conciliadas com divergência. Procure no grid abaixo a coluna Divergência e veja o motivo.',
            'status' => '5',
          ]"
        />
        <x-box
          class="tooltip-hint"
          title="CONC. MANUAL"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_CONCILIADO_MANUAL"
          icon-path="assets/images/widgets/handshake.svg"
          icon-description="Conciliacao Manual"
          :dataset="[
            'hint' => 'Total de vendas do seu sistema que foram conciliadas manualmente.',
            'status' => '6',
          ]"
        />
        <x-box
          class="tooltip-hint"
          title="JUSTIFICADAS"
          content="R$ 0,00"
          data-format="currency"
          data-key="TOTAL_JUSTIFICADO"
          icon-path="assets/images/widgets/flag.svg"
          icon-description="Justificado"
          :dataset="[
            'hint' => 'Total de vendas do seu sistema que foram justificadas. Procure no grid abaixo a coluna Justificativa e veja o motivo.',
            'status' => '3',
          ]"
        />
        <x-box
          class="tooltip-hint"
          :title="'PENDÊNCIAS '.($erp->ERP ? mb_strtoupper($erp->ERP, 'utf-8') : 'ERP')"
          content="R$ 1.916,05"
          data-format="currency"
          data-key="TOTAL_NAO_CONCILIADO"
          icon-path="assets/images/widgets/exclamation-mark.svg"
          icon-description="Pendências"
          :dataset="[
            'hint' => 'Total de vendas do seu sistema que ficaram sem conciliar. Principais motivos: arquivo da operadora ainda não processado, valor errado, bandeira errada, data da venda errada, NSU ou autorização errado.',
            'status' => '2',
          ]"
        />
        <x-box
          class="tooltip-hint"
          title="PENDÊNCIAS OPERADORAS"
          content="R$ 39.716,97"
          data-format="currency"
          data-key="TOTAL_PENDENCIAS_OPERADORAS"
          icon-path="assets/images/widgets/exclamation-mark.svg"
          icon-description="Pendências"
          :dataset="[
            'hint' => 'Total de vendas das operadoras que ficaram sem conciliar. Principais motivos: venda não lançada no seu sistema, valor errado, bandeira errada, data da venda errada, NSU ou autorização errado.',
            'status' => '2',
          ]"
					resumo="resumo"
        />
      </div>

      <div class="vendas" data-table-type="erp">
        <div class="tabela-info d-flex align-items-center justify-content-between">
          <div class="table-description d-flex align-items-center justify-content-end">
            <h4>Vendas {{ $erp->ERP ?? 'ERP' }} <span id="js-quantidade-registros-erp">(0 registros)</span></h4>
            <img src="assets/images/widgets/arrow-down.svg" alt="Vendas ERP">
          </div>
          <div class="acoes d-flex flex-fill align-items-center justify-content-end">
            <button id="js-redefinir-colunas-erp" class="btn button no-hover mr-1">
              <i class="fas fa-columns"></i>
              <span>Redefinir colunas</span>
            </button>
            <x-table-config-dropdown id="js-table-config-erp" class="mr-1" checker-group="tb-config-erp" />
            <div
              class="retorno-erp tooltip-hint font-weight-bold"
              data-title="Clicando aqui vamos efetuar a correção no seu sistema dos campos &quot;data de vencimento&quot;, &quot;taxa&quot; e &quot;valor líquido&quot;."
            >
						@if(auth()->user()->USUARIO_GLOBAL == 'S')
              <button
                  class="btn mr-1 button no-hover"
                  id="js-abrir-modal-retorno-erp"
              >
                  <i class="fas fa-undo"></i>
                  <span>Corrigir Venda {{ $erp->ERP ?? 'ERP' }}</span>
              </button>
						@endif
            </div>
            <button id="js-conciliar" class="btn mr-1 button no-hover">
              <i class="far fa-handshake"></i>
              <span>Conciliar</span>
            </button>
            <button id="js-desconciliar" class="btn mr-1 button no-hover">
              <i class="fas fa-handshake-slash"></i>
              <span>Desconciliar</span>
            </button>
            <button
              id="js-justificar-erp"
              class="btn mr-1 button no-hover"
              data-type="erp"
            >
              <i class="far fa-flag"></i>
              <span>Justificar</span>
            </button>
            <button id="js-desjustificar-erp" class="btn mr-1 button no-hover">
              <i class="fas fa-comment-slash"></i>
              <span>Desfazer Justificativa</span>
            </button>
            <button
              id="js-exportar-erp"
              class="btn button no-hover"
              data-type="erp"
            >
              <div class="conciflex-icon icon-md">
                <img src="assets/images/widgets/excel-file.svg" alt="Excel">
              </div>
              <span>Exportar</span>
            </button>
          </div>
        </div>

        <x-tables.tabela-vendas-erp
          id="js-tabela-erp"
          class="mt-3"
          :headers="[
            'TAXA' => $erp->ERP ? 'Taxa '.$erp->ERP.' %' : null,
            'VALOR_LIQUIDO' => $erp->ERP ? 'Valor Líquido '.$erp->ERP : null,
            'TITULO_CAMPO1' => $erp->TITULO_CAMPO1,
            'TITULO_CAMPO2' => $erp->TITULO_CAMPO2,
            'TITULO_CAMPO3' => $erp->TITULO_CAMPO3,
            'RETORNO_ERP' => 'Venda Corrigida '.($erp->ERP ?? 'ERP'),
            'actions' => 'Ações | Status'
          ]"
          :hidden-columns="[
            'CARTAO',
            'HORA',
            'ESTABELECIMENTO',
            'STATUS_FINANCEIRO'
          ]"
        >
          <x-slot name="actions">
            <td>
              <div class="actions-cell d-flex align-items-center justify-content-between">
                <input
                  name="id_erp[]"
                  type="checkbox"
                  data-value-key="ID_ERP"
                >
                <div class="tooltip-hint tooltip-left d-flex align-items-center" data-default-title="Visualizar Detalhes">
                  <i class="fas fa-eye"></i>
                </div>
                <div class="tooltip-hint tooltip-left" data-title="STATUS_CONCILIACAO">
                  <img data-image="STATUS_CONCILIACAO_IMAGEM">
                </div>
              </div>
            </td>
          </x-slot>
        </x-tables.tabela-vendas-erp>

        <x-tables.table-navigation
          pagination-id="js-paginacao-erp"
          per-page-select-id="js-por-pagina-erp"
          :options="['5', '10', '20', '50', '100', '200']"
        />
      </div>

      <div class="vendas" data-table-type="operadoras">
        <div class="tabela-info d-flex align-items-center justify-content-between">
          <div class="table-description d-flex align-items-center justify-content-end">
            <h4>Vendas Operadoras Não Conciliadas <span id="js-quantidade-registros-operadoras">(0 registros)</span></h4>
            <img src="assets/images/widgets/arrow-down.svg" alt="Vendas Operadoras">
          </div>
          <div class="d-flex flex-fill align-items-center justify-content-end">
            <button id="js-redefinir-colunas-operadoras" class="btn button no-hover mr-1">
              <i class="fas fa-columns"></i>
              <span>Redefinir colunas</span>
            </button>
            <x-table-config-dropdown id="js-table-config-operadoras" class="mr-1" checker-group="tb-config-operadoras" />
            <button
              id="js-justificar-operadora"
              class="btn mr-1 button no-hover"
              data-type="operadoras"
            >
              <i class="far fa-flag"></i>
              <span>Justificar</span>
            </button>
            <button
              id="js-exportar-operadoras"
              class="btn button no-hover"
              data-type="operadoras"
            >
              <div class="conciflex-icon icon-md">
                <img src="assets/images/widgets/excel-file.svg" alt="Excel">
              </div>
              <span>Exportar</span>
            </button>
          </div>
        </div>

        <x-tables.tabela-vendas-operadoras
          id="js-tabela-operadoras"
          class="mt-3"
          :headers="[
            'actions' => 'Ações | Status',
          ]"
          :hidden-columns="[
            'ID_ERP',
            'DIVERGENCIA'
          ]"
        >
          <x-slot name="actions">
            <td>
              <div class="actions-cell d-flex align-items-center justify-content-between">
                <input
                  name="id_operadoras[]"
                  type="checkbox"
                  data-value-key="ID"
                >
                <div class="tooltip-hint tooltip-left d-flex align-items-center" data-default-title="Visualizar Detalhes">
                  <i class="fas fa-eye"></i>
                </div>
                <div class="tooltip-hint tooltip-left" data-title="STATUS_CONCILIACAO">
                  <img data-image="STATUS_CONCILIACAO_IMAGEM">
                </div>
              </div>
            </td>
          </x-slot>
        </x-tables.tabela-vendas-operadoras>

        <x-tables.table-navigation
          pagination-id="js-paginacao-operadoras"
          per-page-select-id="js-por-pagina-operadoras"
          :options="['5', '10', '20', '50', '100', '200']"
        />
      </div>
    </div>
  </main>

  <div class="modais">
    <x-modal
        id="js-retorno-erp-modal"
        modal-label-id="modal-retorno-label"
        :modal-label="'Corrigir Venda '.($erp->ERP ?? 'ERP')"
    >
        <x-slot name="content">
            <div class="form-group">
                <label for="js-data-inicial">Data Inicial:</label>
                <input id="js-data-inicial" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label for="js-data-final">Data Final:</label>
                <input id="js-data-final" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
        </x-slot>

        <x-slot name="footer">
            <button
                id="js-cancelar-retorno-erp"
                type="button"
                class="btn btn-danger font-weight-bold"
            >
                Cancelar
            </button>

            <button
                id="js-retorno-erp"
                type="button"
                class="btn btn-success font-weight-bold"
            >
                Confirmar
            </button>
        </x-slot>
    </x-modal>
    <x-modal
      id="js-justificar-modal"
      modal-label="Justificar"
      modal-label-id="justificar-label"
    >
      <x-slot name="content">
        <form id="js-justificar-form" action="">
          <h6>Justificativa</h6>
          <select
            id="justificativa"
            name="justificativa"
            class="form-control"
          >
            <option value="" selected disabled>Selecione uma justificativa</option>
            @foreach (($justificativas ?? []) as $justificativa)
              <option value="{{ $justificativa->CODIGO }}">{{ $justificativa->JUSTIFICATIVA }}</option>
            @endforeach
          </select>
        </form>
      </x-slot>
      <x-slot name="footer">
        <button
          type="button"
          class="btn btn-danger font-weight-bold"
          data-dismiss="modal"
        >
          Cancelar
        </button>
        <button
          id="js-justificar"
          type="button"
          class="btn btn-success font-weight-bold"
        >
          Confirmar
        </button>
      </x-slot>
    </x-modal>
  </div>

  <div id="js-loader" class="loader hidden"></div>
@endsection

@section('footerScript')
  <script defer src="{{ URL::asset('assets/js/lib/api.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/formatter.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/pagination.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/modal-filters.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/checker.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/ui/table-render.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/ui/box.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/ui/index.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/proxy/SalesProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/proxy/SalesContainerProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/proxy/SearchFormProxy.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/ui/table-section.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/ui/table-config.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/lib/table-dragger-wrapper.js') }}"></script>
  <script defer src="{{ URL::asset('assets/js/conciliacao/conciliacao-vendas.js') }}"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection
