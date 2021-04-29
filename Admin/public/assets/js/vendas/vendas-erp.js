const checker = new Checker();
const modalFilter = new ModalFilter();
const formatter = new Formatter({
  locale: 'pt-BR',
  currencyOptions: {
    type: 'BRL'
  }
});
const searchForm = createSearchForm({
  form: '#js-form-pesquisa',
  inputs: ['_token', 'data_inicial', 'data_final', 'descricao_erp'],
  checker
});
const salesContainer = new SalesContainerProxy({
  id: 'vendas-erp',
  links: {
    search: searchForm.get('form').dataset.urlErp,
    filter: searchForm.get('form').dataset.urlFiltrarErp,
  }
});
const tableRender = createTableRender({
  table: '#js-tabela-erp',
  locale: 'pt-br',
  formatter,
});
const scrollableDragger = createScrollableTableDragger({
  wrapper: '.table-responsive',
  table: '.table-responsive > table#js-tabela-erp',
  slider: '.draggable',
  draggerConfig: {
    mode: 'column',
    dragHandler: '.draggable',
    onlyBody: false,
    animation: 300
  },
  rows: ['#js-tabela-erp tbody tr'],
  elementsToIgnore: ['.draggable input']
});
const tableConfig = new TableConfig({
  tableSelector: '#js-tabela-erp',
  rootElement: '#js-table-config',
});
const boxes = getBoxes();

checker.addGroups([
  { name: 'empresa', options: { inputName: 'grupos_clientes' } },
  { name: 'adquirente', options: { inputName: 'adquirentes' } },
  { name: 'bandeira', options: { inputName: 'bandeiras' } },
  { name: 'modalidade', options: { inputName: 'modalidades' } },
  { name: 'status-conciliacao', options: { inputName: 'status_conciliacao' } },
  { name: 'status-financeiro', options: { inputName: 'status_financeiro' } },
]);

modalFilter.addGroups([
  'empresa',
  'adquirente',
  'bandeira',
  'modalidade',
]);

salesContainer.setupApi({
  headers: {
    'X-CSRF-TOKEN': searchForm.getInput('_token').value,
    'Content-Type': 'application/json',
  }
});

salesContainer.onEvent('beforeFetch', () => {
  toggleElementVisibility('#js-loader');
});

salesContainer.onEvent('fetch', (sales) => {
  toggleElementVisibility('#js-loader');
  document.querySelector('#js-quantidade-registros').textContent = `(${sales.get('pagination').options.total || 0} registros)`;

  tableRender.set('data', {
    body: (sales.get('sales') || []),
    footer: (sales.get('totals') || {}),
  });
  tableRender.render();
  sales.get('pagination').render();
});

salesContainer.onEvent('search', (sales) => {
  const resultadosDOM = document.querySelector('.resultados');

  const totals = sales.get('totals');
  updateBoxes(boxes, {
    ...totals,
    TOTAL_TAXA: totals.TOTAL_TAXA * -1,
  });

  if (resultadosDOM.classList.contains('hidden')) {
    resultadosDOM.classList.remove('hidden');
  }
});

salesContainer.onEvent('fail', (err) => {
  document.querySelector('#js-loader').classList.remove('hidden');
  document.querySelector('#js-loader').classList.add('hidden');
});

salesContainer.setPaginationConfig({
  paginationContainer: document.querySelector('#js-paginacao-erp')
},
  async (page, pagination, event) => {
    await buildRequest({
        page,
        por_pagina: pagination.options.perPage,
      })
      .get();
  }
)

function buildRequest(params) {
  let requestHandler = () => {};

  const isSearchActive = salesContainer.get('active') === 'search';
  const sendRequest = isSearchActive ? salesContainer.search.bind(salesContainer) : salesContainer.filter.bind(salesContainer);

  const filters = { ...searchForm.serialize(), ...tableRender.serializeSortFilter() };
  const bodyPayload = isSearchActive ?
    { ...filters }
    : {
      filters: { ...filters },
      subfilters: { ...tableRender.serializeTableFilters() }
    }

  const requestPayload = {
    params: {},
    body: bodyPayload,
  }

  requestHandler = async (params) => {
    requestPayload.params = {
      por_pagina: salesContainer.get('search').get('pagination').options.perPage,
      ...params
    };

    await sendRequest(requestPayload)
  }

  return {
    requestHandler,
    params,
    get: async function() {
      await this.requestHandler(this.params);
    }
  }
}

searchForm.onSubmit(async (event) => {
  await salesContainer.search({
    params: {
      por_pagina: document.querySelector('#js-por-pagina').value,
    },
    body: { ...searchForm.serialize() },
  });

  tableRender.clearFilters();
  tableRender.clearSortFilter();
  window.scrollTo(0, document.querySelector('.resultados').offsetTop);
});

tableRender.onSort(async (elementDOM, tableInstance) => {
  const params = {
    por_pagina: document.querySelector('#js-por-pagina').value,
  };

  _defaultEvents.table.onSort(elementDOM, tableInstance);
  await buildRequest(params).get();
});

tableRender.onFilter(async (filters) => {
  const params = {
    por_pagina: document.querySelector('#js-por-pagina').value,
  };

  salesContainer.toggleActiveData('filter');
  if (Object.keys(filters).length === 0) {
    salesContainer.toggleActiveData('search');
    params.page = 1;
  }

  await buildRequest(params).get();
});

async function onPerPageChanged(event) {
  salesContainer.get('search').get('pagination').setOptions({ perPage: event.target.value });
  salesContainer.get('filtered').get('pagination').setOptions({ perPage: event.target.value });
  await buildRequest({
      page: 1,
      por_pagina: event.target.value,
    })
    .get();
}

function exportar() {
  swal('Aguarde um momento...', 'A sua planilha está sendo gerada.', 'warning');
  setTimeout(() => {
    openUrl(searchForm.get('form').dataset.urlExportar, {
      ...searchForm.serialize(),
      ...tableRender.serializeTableFilters(),
      ...serializeTableSortToExport(tableRender.serializeSortFilter()),
      hidden: tableConfig.get('hiddenSections'),
    });
  }, 500);
}

searchForm.get('form').querySelector('button[data-form-action="submit"')
  .addEventListener('click', searchForm.get('onSubmitHandler'));

document.querySelector('#js-por-pagina')
  .addEventListener('change', onPerPageChanged);

document.querySelector('#js-exportar')
  .addEventListener('click', exportar);

window.addEventListener('load', () => {
  tableConfig.init();
  tableRender.afterRender((tableInstance) => {
    tableConfig.get('sectionContainer').refreshAll();
    scrollableDragger.fixator.update();
  });
});
