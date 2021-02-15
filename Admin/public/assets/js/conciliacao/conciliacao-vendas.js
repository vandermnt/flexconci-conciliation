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
  id: 'vendas-operadoras',
  links: {
    search: searchForm.get('form').dataset.urlBuscarOperadoras,
    filter: searchForm.get('form').dataset.urlFiltrarOperadoras,
  }
});
const salesErpContainer = new SalesContainerProxy({
  id: 'vendas-erp',
  links: {
    search: searchForm.get('form').dataset.urlBuscarErp,
    filter: searchForm.get('form').dataset.urlFiltrarErp,
  }
});
const tableRender = createTableRender({
  table: '#js-tabela-operadoras',
  locale: 'pt-br',
  formatter,
});
const tableRenderErp = createTableRender({
  table: '#js-tabela-erp',
  locale: 'pt-br',
  formatter,
});
const boxes = getBoxes();
const apiConfig = {
  headers: {
    'X-CSRF-TOKEN': searchForm.getInput('_token').value,
    'Content-Type': 'application/json',
  }
};

checker.addGroups([
  { name: 'empresa', options: { inputName: 'grupos_clientes' } },
  { name: 'status-conciliacao', options: { inputName: 'status_conciliacao' } },
]);

modalFilter.addGroups([
  'empresa',
]);

salesContainer.setupApi(apiConfig);
salesContainer.setPaginationConfig({
  paginationContainer: document.querySelector('#js-paginacao-operadoras')
},
  async (page, pagination, event) => {
    toggleElementVisibility('#js-loader');

    await buildRequest('operadoras', {
        page,
        por_pagina: pagination.options.perPage,
      })
      .get();

    toggleElementVisibility('#js-loader');
  }
)
salesErpContainer.setupApi(apiConfig);
salesErpContainer.setPaginationConfig({
  paginationContainer: document.querySelector('#js-paginacao-erp')
},
  async (page, pagination, event) => {
    toggleElementVisibility('#js-loader');

    await buildRequest('erp', {
        page,
        por_pagina: pagination.options.perPage,
      })
      .get();

    toggleElementVisibility('#js-loader');
  }
)

searchForm.onSubmit(async (event) => {
  const resultadosDOM = document.querySelector('.resultados');
  toggleElementVisibility('#js-loader');
  
  const responses = await Promise.all([
    await salesContainer.search({
      params: {
        por_pagina: document.querySelector('#js-por-pagina-operadoras').value,
      },
      body: { ...searchForm.serialize() },
    }),
    await salesErpContainer.search({
      params: {
        por_pagina: document.querySelector('#js-por-pagina-erp').value,
      },
      body: { ...searchForm.serialize() },
    }),
  ]);

  toggleElementVisibility('#js-loader');

  if (resultadosDOM.classList.contains('hidden')) {
    resultadosDOM.classList.remove('hidden');
  }

  tableRender.clearFilters();
  tableRenderErp.clearFilters();
  window.scrollTo(0, document.querySelector('.resultados').offsetTop);
  console.log(responses);
});

tableRender.onFilter(async (filters) => {
  toggleElementVisibility('#js-loader');

  const params = {
    por_pagina: document.querySelector('#js-por-pagina-operadoras').value,
  };

  salesContainer.toggleActiveData('filter');
  if (Object.keys(filters).length === 0) {
    salesContainer.toggleActiveData('search');
    params.page = 1;
  }

  await buildRequest('operadoras', params).get();
  
  toggleElementVisibility('#js-loader');
});

tableRenderErp.onFilter(async (filters) => {
  toggleElementVisibility('#js-loader');

  const params = {
    por_pagina: document.querySelector('#js-por-pagina-erp').value,
  };

  salesErpContainer.toggleActiveData('filter');
  if (Object.keys(filters).length === 0) {
    salesErpContainer.toggleActiveData('search');
    params.page = 1;
  }

  await buildRequest('erp', params).get();

  toggleElementVisibility('#js-loader');
});

salesContainer.onEvent('fetch', (sales) => {
  document.querySelector('#js-quantidade-registros-operadoras').textContent = `(${sales.get('pagination').options.total || 0} registros)`;

  tableRender.set('data', {
    body: (sales.get('sales') || []),
    footer: (sales.get('totals') || {}),
  });
  tableRender.render();
  sales.get('pagination').render();
});
salesContainer.onEvent('search', (sales) => {
  const totals = sales.get('totals');
  updateBoxes(boxes, { 
    TOTAL_PENDENCIAS_OPERADORAS: totals.TOTAL_PENDENCIAS_OPERADORAS,
  });
});

salesErpContainer.onEvent('fetch', (sales) => {
  document.querySelector('#js-quantidade-registros-erp').textContent = `(${sales.get('pagination').options.total || 0} registros)`;

  tableRenderErp.set('data', {
    body: (sales.get('sales') || []),
    footer: (sales.get('totals') || {}),
  });
  tableRenderErp.render();
  sales.get('pagination').render();
});
salesErpContainer.onEvent('search', (sales) => {
  const totals = sales.get('totals');
  updateBoxes(boxes, { 
    ...totals,
    TOTAL_TAXA: totals.TOTAL_TAXA * -1,
  });
});

function buildRequest(type = 'erp', params) {
  let requestHandler = () => {};

  const currentSalesContainer = type === 'erp' ? salesErpContainer : salesContainer;
  const currentTableRender = type === 'erp' ? tableRenderErp : tableRender;

  if(currentSalesContainer.get('active') === 'search') {
    requestHandler = async (params) => {
      await currentSalesContainer.search({
        params: {
          por_pagina: currentSalesContainer.get('search').get('pagination').options.perPage,
          ...params
        },
        body: { ...searchForm.serialize() }
      });
    }
  } else {
    requestHandler = async (params) => {
      await currentSalesContainer.filter({
        params: {
          por_pagina: currentSalesContainer.get('search').get('pagination').options.perPage,
          ...params,
        },
        body: {
          filters: { ...searchForm.serialize() },
          subfilters: { ...currentTableRender.serializeTableFilters() }
        }
      });
    }
  }

  return {
    requestHandler,
    params,
    get: async function() {
      await this.requestHandler(this.params);
    }
  }
}

async function onPerPageChanged(type = 'erp', event) {
  const currentSalesContainer = type === 'erp' ? salesErpContainer : salesContainer;
  currentSalesContainer.get('search').get('pagination').setOptions({ perPage: event.target.value });
  currentSalesContainer.get('filtered').get('pagination').setOptions({ perPage: event.target.value });

  toggleElementVisibility('#js-loader');

  await buildRequest(type, {
      page: 1,
      por_pagina: event.target.value,
    })
    .get();

  toggleElementVisibility('#js-loader');
}

searchForm.get('form').querySelector('button[data-form-action="submit"')
  .addEventListener('click', searchForm.get('onSubmitHandler'));

document.querySelector('#js-por-pagina-erp')
  .addEventListener('change', (event) => onPerPageChanged('erp', event));
document.querySelector('#js-por-pagina-operadoras')
  .addEventListener('change', (event) => onPerPageChanged('operadoras', event));