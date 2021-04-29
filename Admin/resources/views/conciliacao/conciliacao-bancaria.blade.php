@extends('layouts.analytics-master')

@section('headerStyle')
  <link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
  <link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('assets/css/globals/global.css') }}" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="{{ URL::asset('assets/css/conciliacao/pagina-conciliacao-bancaria.css') }}" type="text/css">
@endsection

@section('content')
  <main id="pagina-vendas-operadoras" class="container-fluid">
    <header>
      @component('common-components.breadcrumb')
        @slot('title') Conciliação Bancária @endslot
        @slot('item1') Conciliação @endslot
      @endcomponent
    </header>

    <div class="card">
      <div class="card-body">
        <x-forms.search-form
          id="js-form-pesquisa"
          :urls="[
						['operadoras' => route('conciliacao-bancaria.search')],
            ['filtrar-operadoras' => route('conciliacao-bancaria.filter')],
            ['exportar' => route('vendas-operadoras.export')],
            ['imprimir' => route('vendas-operadoras.print', ['id' => ':id'])],
            ['desjustificar' => route('vendas-operadoras.unjustify')],
            ['retorno-csv' => route('vendas-operadoras.retorno-csv')],
						['comprovantes' => route('conciliacao-bancaria.searchComprovante')],
						['filtrar-comprovantes' => route('conciliacao-bancaria.filterComprovante')],
						['extratos' => route('conciliacao-bancaria.searchExtrato')],
						['filtrar-extratos' =>route('conciliacao-bancaria.filterExtrato')],
          ]"
          :hidden-fields="[
            'domicilios-bancarios',
            'descricao-erp',
						'bandeiras',
            'modalidades',
            'estabelecimentos',
						'status-conciliacao',
						'status-financeiro',
						'empresas'
          ]"
          :form-data="[
            {{-- 'empresas' => $empresas, --}}
            'adquirentes' => $adquirentes,
          ]"
        />
      </div>
    </div>

    <div class="resultados hidden">

      <div class="vendas">
        <div class="tabela-info d-flex align-items-center justify-content-between flex-wrap">
          <div class="table-description d-flex align-items-center justify-content-end">
            <h4>Conciliação Bancária <span id="js-quantidade-registros">(0 registros)</span></h4>
            <img src="assets/images/widgets/arrow-down.svg" alt="Vendas Operadoras">
          </div>
          <div class="d-flex flex-fill align-items-center justify-content-end">
            <x-table-config-dropdown id="js-table-config" class="mr-1" checker-group="tb-config-columns" />
						<button id="js-exportar" class="btn button no-hover">
              <div class="conciflex-icon icon-md">
                  <img src="assets/images/widgets/excel-file.svg" alt="Excel">
              </div>
              Exportar
            </button>
          </div>
        </div>

        <x-tables.tabela-conciliacao-bancaria
					id="js-tabela-bancaria"
          class="mt-3"
          :headers="[
            'actions' => 'Ações',
          ]"
					:hiddenColumns="[
						'ID_ERP'
					]"
        >
          <x-slot name="actions">
            <td>
              <div class="actions-cell d-flex align-items-center justify-content-beetwen">
                <input
                    class="mr-2"
                    name="id_operadora"
                    type="checkbox"
                    data-value-key="ID"
                >
                <div
                    class="tooltip-hint tooltip-left d-flex align-items-center js-show-comprovante"
                    data-default-title="Visualizar Detalhes"
                    data-toggle="modal"
                    data-target="#comprovante-modal"
                >
                    <i class="fas fa-eye"></i>
                </div>
              </div>
            </td>
          </x-slot>
        </x-tables.tabela-conciliacao-bancaria>

        <x-tables.table-navigation
          pagination-id="js-paginacao-bancaria"
          per-page-select-id="js-por-pagina"
          :options="['10', '20', '50', '100', '200']"
        />
      </div>
    </div>
    <div class="modais">
			<x-modal
        id="js-extrato-bancario"
        modal-label-id="modal-extrato-bancario-label"
        modal-label="Envie seus extratos bancários aqui"
    	>
        <x-slot name="content">
					<div class="modal-body">
						<input
						id="teste"
						type="file"
						class="dropify"
						name="extratos[]"
						multiple
						{{-- accept=".ofx" --}}
						>
						<button type="button" class="btn btn-primary w-100 mt-2" data-dismiss="modal">Enviar</button>
					</div>
        </x-slot>

        <x-slot name="footer">
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
					</div>
        </x-slot>
    </x-modal>
		<x-modal
        id="comprovante-modal"
        modal-label-id="comprovante-modal-label"
    	>
				<x-slot name="header">
					<div class="boxes">
						<x-box
							title="DATA RECEBIMENTO"
							content=""
							data-format="date"
							data-key="DATA_PAGAMENTO"
						/>
						<x-box
							title="BANCO"
							content=""
							data-key="BANCO_IMAGEM"
						/>
						<x-box
							title="AGÊNCIA"
							content=""
							data-format="text"
							data-key="AGENCIA"
						/>
						<x-box
							title="CONTA"
							content=""
							data-format="text"
							data-key="CONTA"
						/>
					</div>
					<button
						class="close"
						type="button"
						data-dismiss="modal"
						data-label="Close"
					>
						<span aria-hidden="true">&times;</span>
					</button>
				</x-slot>
        <x-slot name="content">
					<div class="modal-body">
						<div class="tabela-info d-flex align-items-center justify-content-between flex-wrap mt-2">
							<div class="w-50 mt-auto tabela-info">
								<div class="table-description d-flex align-items-center justify-content-start w-100">
									<h4 id="js-comprovante-table-title" class="text-center">Recebimentos</h4>
									<img src="assets/images/widgets/arrow-down.svg" alt="">
								</div>
							</div>
							<div class="w-50 mb-auto tabela-info">
								<div class="table-description d-flex align-items-center justify-content-start w-100">
									<h4 id="js-extrato-table-title" class="text-center">Lançamentos do seu Extrato Bancário</h4>
									<img src="assets/images/widgets/arrow-down.svg" alt="">
								</div>
							</div>
						<div class="d-flex align-items-center w-100">
							<div class="w-50 tabela-info">
								<div class="d-flex align-items-center justify-content-end mt-3 mb-2 w-100">
									<button id="js-exportar" class="btn button no-hover">
										<div class="conciflex-icon icon-md">
											<img src="assets/images/widgets/check.svg" alt="Excel">
										</div>
										Filtrar conciliadas
									</button>
									<button id="js-exportar" class="btn button no-hover ml-2">
										<div class="conciflex-icon icon-md">
											<img src="assets/images/widgets/x.svg" alt="Excel">
										</div>
										Filtrar não conciliadas
									</button>
								</div>
							</div>
							<div class="w-50 tabela-info">
								<div class="d-flex align-items-center justify-content-end mt-3 mb-2 w-100">
									<button id="js-exportar" class="btn button no-hover">
										<div class="conciflex-icon icon-md">
											<img src="assets/images/widgets/check.svg" alt="Excel">
										</div>
										Filtrar conciliadas
									</button>
									<button id="js-exportar" class="btn button no-hover ml-2">
										<div class="conciflex-icon icon-md">
											<img src="assets/images/widgets/x.svg" alt="Excel">
										</div>
										Filtrar não conciliadas
									</button>
								</div>
							</div>
						</div>
						<div class="row w-100">
							<div class="vendas col-6">
								<x-tables.tabela-conciliacao-bancaria-comprovante
									id="js-tabela-conciliacao-bancaria-comprovante"
									class="mt-2"
									:headers="[
										'actions' => 'Ações | Status',
									]"
									:hiddenColumns="[
										'ID_ERP'
									]"
								>
									<x-slot name="actions">
										<td>
											<div class="actions-cell d-flex align-items-center justify-content-beetwen">
												<input
														class="mr-2"
														name="id_operadora"
														type="checkbox"
														data-value-key="ID"
												>
											</div>
										</td>
									</x-slot>
								</x-tables.tabela-conciliacao-bancaria-comprovante>

								<x-tables.table-navigation
									pagination-id="js-paginacao-comprovante"
									per-page-select-id="js-por-pagina-comprovante"
									:options="['5', '10', '20', '50', '100', '200']"
								/>
							</div>
							<div class="vendas col-6">
								<x-tables.tabela-extrato-bancario
									id="js-tabela-extrato"
									class="mt-2"
									:headers="[
										'actions' => 'Ações | Status',
									]"
									:hiddenColumns="[
										'ID_ERP'
									]"
								>
									<x-slot name="actions">
										<td>
											<div class="actions-cell d-flex align-items-center justify-content-beetwen">
												<input
														class="mr-2"
														name="id_operadora"
														type="checkbox"
														data-value-key="ID"
												>
											</div>
										</td>
									</x-slot>
								</x-tables.tabela-extrato-bancario>

								<x-tables.table-navigation
									pagination-id="js-paginacao-extrato"
									per-page-select-id="js-por-pagina-extrato"
									:options="['5', '10', '20', '50', '100', '200']"
								/>
							</div>
						</div>
					</div>
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-modal>
    </div>
  </main>

  <div id="js-loader" class="loader hidden"></div>
@endsection

@section('footerScript')
	<script defer src="{{ URL::asset('assets/pages/jquery.form-upload.init.js')}}"></script>
	<script defer src="{{ URL::asset('plugins/dropify/js/dropify.min.js')}}"></script>
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
  <script defer src="{{ URL::asset('assets/js/conciliacao/conciliacao-bancaria.js') }}"></script>
	<script defer src="{{ URL::asset('assets/js/conciliacao/comprovante/conciliacao-bancaria-comprovante.js') }}"></script>
	<script defer src="{{ URL::asset('assets/js/conciliacao/comprovante/extrato-bancario.js') }}"></script>
@endsection
