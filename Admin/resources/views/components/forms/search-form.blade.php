<form
  id="{{ $attributes->get('id') ?? 'js-search-form' }}"
  class="form search-form {{ $attributes->get('class') }}"
  method="{{ $attributes->get('method') ?? 'GET' }}"
  {{ $renderUrls() }}
>
  @csrf
  @if($isFieldVisible('datas'))
		@if(Route::current()->getName() == 'conciliacao-bancaria')
			<div class="row conc-bancaria">
				<div class="col">
					<div class="input-group">
						<x-forms.form-group
							:label="$getLabel('data_inicial') ?? 'Data Inicial:'"
							id="data-inicial"
							type="date"
							name="data_inicial"
							:value="$getData('data_inicial') ?? date('Y-m-01')"
							required 
						/>
						<x-forms.form-group
							:label="$getLabel('data_final') ?? 'Data Final:'"
							id="data-final"
							type="date"
							name="data_final"
							:value="$getData('data_final') ?? date('Y-m-d')"
							required
						/>
					</div>
				</div>
				<div class="col d-flex align-bottom justify-content-end w-100">
					<button type="button" class="btn btn-lg extrato-bancario-button" data-target="#js-extrato-bancario" data-toggle="modal"><i class="fas fa-university mr-2"></i>Enviar extrato bancário</button>
				</div>
			</div>
		@else
			<div class="input-group">
				<x-forms.form-group
					:label="$getLabel('data_inicial') ?? 'Data Inicial:'"
					id="data-inicial"
					type="date"
					name="data_inicial"
					:value="$getData('data_inicial') ?? date('Y-m-01')"
					required 
				/>
				<x-forms.form-group
					:label="$getLabel('data_final') ?? 'Data Final:'"
					id="data-final"
					type="date"
					name="data_final"
					:value="$getData('data_final') ?? date('Y-m-d')"
					required
				/>
			</div>
		@endif
  @endif

  @if($isFieldVisible('empresas'))
    <div class="input-group">
      <x-forms.form-group
        :label="$getLabel('empresa') ?? 'Empresa:'"
        id="empresa"
        type="text"
        data-group="empresa"
        data-checker="to-text-element"
      />
      <button
        class="btn btn-sm form-button"
        type="button"
        data-toggle="modal"
        data-target="#empresas-modal"
      >
        Selecionar
      </button>
    </div>
  @endif

  @if($isFieldVisible('adquirentes'))
    <div class="input-group">
      <x-forms.form-group
        :label="$getLabel('adquirente') ?? 'Operadora:'"
        id="adquirente"
        type="text"
        data-group="adquirente"
        data-checker="to-text-element"
      />
      <button
        class="btn btn-sm form-button"
        type="button"
        data-toggle="modal"
        data-target="#adquirentes-modal"
      >
        Selecionar
      </button>
    </div>
  @endif

  @if($isFieldVisible('bandeiras'))
    <div class="input-group">
      <x-forms.form-group 
        :label="$getLabel('bandeira') ?? 'Bandeira:'"
        id="bandeira" 
        type="text"
        data-group="bandeira"
        data-checker="to-text-element"
      />
      <button 
        class="btn btn-sm form-button" type="button" 
        data-toggle="modal" 
        data-target="#bandeiras-modal"
      >
        Selecionar
      </button>
    </div>
  @endif

  @if($isFieldVisible('modalidades'))
    <div class="input-group">
      <x-forms.form-group
        :label="$getLabel('modalidade') ?? 'Forma de Pagamento:'"
        id="modalidade" 
        type="text"
        data-group="modalidade"
        data-checker="to-text-element"
      />
      <button 
        class="btn btn-sm form-button" 
        type="button" 
        data-toggle="modal" 
        data-target="#modalidades-modal"
      >
        Selecionar
      </button>
    </div>
  @endif

  @if($isFieldVisible('estabelecimentos'))
    <div class="input-group">
      <x-forms.form-group 
        :label="$getLabel('estabelecimento') ?? 'Código de Estabelecimento:'"
        id="estabelecimento" 
        type="text"
        data-group="estabelecimento"
        data-checker="to-text-element"
      />
      <button 
        class="btn btn-sm form-button" 
        type="button" 
        data-toggle="modal" 
        data-target="#estabelecimentos-modal"
      >
        Selecionar
      </button>
    </div>
  @endif
  
  @if($isFieldVisible('domicilios-bancarios'))
    <div class="input-group">
      <x-forms.form-group 
        :label="$getLabel('domicilio_bancario') ?? 'Domicílio Bancário:'"
        id="domicilio-bancario" 
        type="text"
        data-group="domicilio-bancario"
        data-checker="to-text-element"
      />
      <button 
        class="btn btn-sm form-button" 
        type="button" 
        data-toggle="modal" 
        data-target="#domicilios-bancarios-modal"
      >
        Selecionar
      </button>
    </div>
  @endif

  @if($isFieldVisible('descricao-erp'))
    <div class="input-group">
      <x-forms.form-group
        :label="$getLabel('descricao_erp') ?? 'ID. ERP:'"
        id="descricao-erp"
        type="text"
        name="descricao_erp"
      />
    </div>
  @endif
  
  @if($isFieldVisible('status-conciliacao'))
    <x-forms.check-group 
      id="status-conciliacao"
      :label="$getLabel('status_conciliacao') ?? 'Status Conciliação:'"
      name="status_conciliacao[]"
      item-value-key="CODIGO"
      item-description-key="STATUS_CONCILIACAO" 
      :options="$getData('status_conciliacao')"
      data-group="status-conciliacao" 
      data-checker="checkbox" 
      checked
    />
  @endif

  @if($isFieldVisible('status-financeiro'))
    <x-forms.check-group
      id="status-financeiro"
      :label="$getLabel('status_financeiro') ?? 'Status Financeiro:'"
      name="status_financeiro[]"
      item-value-key="CODIGO"
      item-description-key="STATUS_FINANCEIRO"
      :options="$getData('status_financeiro')"
      data-group="status-financeiro" 
      data-checker="checkbox"
      checked
    />
  @endif
  
  {{ $fields }}

  <div class="button-group">
    <button data-form-action="clear" class="btn btn-sm" type="button">
      <i class="far fa-trash-alt"></i>
      Limpar Campos
    </button>
    <button data-form-action="submit" class="btn btn-sm ml-1" type="button">
      <i class="fas fa-search"></i>
      Pesquisar
    </button>
  </div>

  <div class="modais">
    @if($isFieldVisible('empresas'))
      <x-selection-modal 
        id="empresas-modal" 
        modal-label-id="empresas-label" 
        modal-label="Empresa" 
        data-group="empresa"
        data-filter-group="empresa"
        data-filter-fields="cnpj,empresa"
      >
        <div class="modal-checkboxes">
          <div class="row">
            <div class="col-sm-6 pl-0">
              <p>Empresa</p>
            </div>
            <div class="col-sm-4 px-0">
              <p>CNPJ</p>
            </div>
            <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
              <input type="checkbox" data-group="empresa" data-checker="global">
            </div>
          </div>
          @foreach(($getData('empresas') ?? []) as $empresa)
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
                  type="checkbox" name="grupos_clientes[]"
                  value="{{ $empresa->CODIGO }}"
                  data-checker="checkbox"
                  data-group="empresa"
                  data-descricao="{{ $empresa->NOME_EMPRESA }}"
                >
              </div>
            </div>
          @endforeach
        </div>
      </x-selection-modal>
    @endif

    @if($isFieldVisible('adquirentes'))
      <x-selection-modal
        id="adquirentes-modal"
        modal-label-id="adquirentes-label"
        modal-label="Adquirente"
        data-group="adquirente"
        data-filter-group="adquirente"
        data-filter-fields="adquirente"
      >
        <div class="modal-checkboxes">
          <div class="row">
            <div class="col-sm-10 pl-0">
              <p>Adquirente</p>
            </div>
            <div class="col-sm-2 pl-0 d-flex align-items-start px-0 justify-content-end">
              <input
                type="checkbox"
                data-checker="global"
                data-group="adquirente"
              >
            </div>
          </div>
          @foreach(($getData('adquirentes') ?? []) as $adquirente)
            <div
              class="row"
              data-filter-item-container="adquirente"
              data-filter-adquirente="{{ $adquirente->ADQUIRENTE }}"
            >
              <div class="col-sm-10 pl-0">
                <p>{{ $adquirente->ADQUIRENTE }}</p>
              </div>
              <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
                <input
                  type="checkbox"
                  name="adquirentes[]"
                  value="{{ $adquirente->CODIGO }}"
                  data-checker="checkbox"
                  data-group="adquirente"
                  data-descricao="{{ $adquirente->ADQUIRENTE }}"
                >
              </div>
            </div>
          @endforeach
        </div>
      </x-selection-modal>
    @endif

    @if($isFieldVisible('bandeiras'))
      <x-selection-modal 
        id="bandeiras-modal" 
        modal-label-id="bandeiras-label" 
        modal-label="Bandeira"
        data-group="bandeira" 
        data-filter-group="bandeira" 
        data-filter-fields="bandeira"
      >
        <div class="modal-checkboxes">
          <div class="row">
            <div class="col-sm-10 pl-0">
              <p>Bandeira</p>
            </div>
            <div class="col-sm-2 pl-0 d-flex align-items-start px-0 justify-content-end">
              <input
                type="checkbox"
                data-checker="global"
                data-group="bandeira"
              >
            </div>
          </div>
          @foreach(($getData('bandeiras') ?? []) as $bandeira)
            <div
              class="row"
              data-filter-item-container="bandeira"
              data-filter-bandeira="{{ $bandeira->BANDEIRA }}"
            >
              <div class="col-sm-10 pl-0">
                <p>{{ $bandeira->BANDEIRA }}</p>
              </div>
              <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
                <input 
                  type="checkbox" 
                  name="bandeiras[]" 
                  value="{{ $bandeira->CODIGO }}" 
                  data-checker="checkbox"
                  data-group="bandeira" 
                  data-descricao="{{ $bandeira->BANDEIRA }}"
                >
              </div>
            </div>
          @endforeach
        </div>
      </x-selection-modal>
    @endif

    @if($isFieldVisible('modalidades'))
      <x-selection-modal 
        id="modalidades-modal" 
        modal-label-id="modalidades-label" 
        modal-label="Forma de Pagamento"
        data-group="modalidade" 
        data-filter-group="modalidade" 
        data-filter-fields="modalidade"
      >
        <div class="modal-checkboxes">
          <div class="row">
            <div class="col-sm-10 pl-0">
              <p>Forma de Pagamento</p>
            </div>
            <div class="col-sm-2 pl-0 d-flex align-items-start px-0 justify-content-end">
              <input
                type="checkbox"
                data-checker="global"
                data-group="modalidade"
              >
            </div>
          </div>
          @foreach(($getData('modalidades') ?? []) as $modalidade)
            <div
              class="row"
              data-filter-item-container="modalidade"
              data-filter-modalidade="{{ $modalidade->DESCRICAO }}"
            >
              <div class="col-sm-10 pl-0">
                <p>{{ $modalidade->DESCRICAO }}</p>
              </div>
              <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
                <input
                  type="checkbox"
                  name="modalidades[]"
                  value="{{ $modalidade->CODIGO }}"
                  data-checker="checkbox"
                  data-group="modalidade"
                  data-descricao="{{ $modalidade->DESCRICAO }}"
                >
              </div>
            </div>
          @endforeach
        </div>
      </x-selection-modal>
    @endif

    @if($isFieldVisible('estabelecimentos'))
      <x-selection-modal
        id="estabelecimentos-modal"
        modal-label-id="estabelecimentos-label"
        modal-label="Código de Estabelecimento"
        data-group="estabelecimento"
        data-filter-group="estabelecimento"
        data-filter-fields="estabelecimento,adquirente"
      >
        <div class="modal-checkboxes">
          <div class="row">
            <div class="col-sm-4 pl-0">
              <p>Operadora</p>
            </div>
            <div class="col-sm-6 pl-0">
              <p>Código de Estabelec.</p>
            </div>
            <div class="col-sm-2 pl-0 d-flex align-items-start px-0 justify-content-end">
              <input
                type="checkbox"
                data-checker="global"
                data-group="estabelecimento"
              >
            </div>
          </div>
          @foreach(($getData('estabelecimentos') ?? []) as $estabelecimento)
            <div
              class="row"
              data-filter-item-container="estabelecimento"
              data-filter-estabelecimento="{{ $estabelecimento->ESTABELECIMENTO }}"
              data-filter-adquirente="{{ $estabelecimento->ADQUIRENTE }}"
            >
              <div class="col-sm-4 pl-0">
                <p>{{ $estabelecimento->ADQUIRENTE }}</p>
              </div>
              <div class="col-sm-6 pl-0">
                <p>{{ $estabelecimento->ESTABELECIMENTO }}</p>
              </div>
              <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
                <input
                  type="checkbox"
                  name="estabelecimentos[]"
                  value="{{ $estabelecimento->ESTABELECIMENTO }}"
                  data-checker="checkbox"
                  data-group="estabelecimento"
                  data-descricao="{{ $estabelecimento->ESTABELECIMENTO }}"
                >
              </div>
            </div>
          @endforeach
        </div>
      </x-selection-modal>
    @endif
    @if($isFieldVisible('domicilios-bancarios'))
      <x-selection-modal
        id="domicilios-bancarios-modal"
        modal-label-id="domicilios-bancarios-label"
        modal-label="Domicílio Bancário"
        data-group="domicilio-bancario"
        data-filter-group="domicilio-bancario"
        data-filter-fields="banco,agencia,conta"
      >
        <div class="modal-checkboxes">
          <div class="row">
            <div class="col-sm-4 pl-0">
              <p>Banco</p>
            </div>
            <div class="col-sm-3 px-0">
              <p>Agência</p>
            </div>
            <div class="col-sm-3 px-0">
              <p>Conta</p>
            </div>
            <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
              <input type="checkbox" data-group="domicilio-bancario" data-checker="global">
            </div>
          </div>
          @foreach(($getData('domicilios_bancarios') ?? []) as $domicilio)
            <div 
              class="row" 
              data-filter-item-container="domicilio-bancario" 
              data-filter-banco="{{ $domicilio->BANCO }}"
              data-filter-agencia="{{ $domicilio->AGENCIA }}"
              data-filter-conta="{{ $domicilio->CONTA }}"
            >
              <div class="col-sm-4 pl-0">
                <p>{{ $domicilio->BANCO }}</p>
              </div>
              <div class="col-sm-3 px-0">
                <p>{{ $domicilio->AGENCIA }}</p>
              </div>
              <div class="col-sm-3 px-0">
                <p>{{ $domicilio->CONTA }}</p>
              </div>
              <div class="col-sm-2 d-flex align-items-start px-0 justify-content-end">
                <input
                  type="checkbox" name="domicilios_bancarios[]"
                  value="{{ $domicilio->CODIGO }}"
                  data-checker="checkbox"
                  data-group="domicilio-bancario"
                  data-descricao="{{ $domicilio->BANCO }}"
                >
              </div>
            </div>
          @endforeach
        </div>
      </x-selection-modal>
    @endif

    {{ $modals }}
  </div>
</form>