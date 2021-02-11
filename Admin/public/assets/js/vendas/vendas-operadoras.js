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
  inputs: ['_token', 'data_inicial', 'data_final'],
  checker,
});
const salesContainer = new SalesContainerProxy({
  id: 'operadoras',
  links: {
    search: searchForm.get('form').dataset.urlOperadoras,
    filter: searchForm.get('form').dataset.urlFiltrarOperadoras,
  }
});
const tableRender = createTableRender({
  table: '#js-tabela-operadoras',
  locale: 'pt-br',
  formatter,
});
const boxes = getBoxes();

checker.addGroups([
  { name: 'empresa', options: { inputName: 'grupos_clientes' } },
  { name: 'adquirente', options: { inputName: 'adquirentes' } },
  { name: 'bandeira', options: { inputName: 'bandeiras' } },
  { name: 'modalidade', options: { inputName: 'modalidades' } },
  { name: 'estabelecimento', options: { inputName: 'estabelecimentos' } },
  { name: 'status-conciliacao', options: { inputName: 'status_conciliacao' } },
  { name: 'status-financeiro', options: { inputName: 'status_financeiro' } },
]);

modalFilter.addGroups([
  'empresa',
  'adquirente',
  'bandeira',
  'modalidade',
  'estabelecimento'
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
  updateBoxes(boxes, { ...totals });

  if (resultadosDOM.classList.contains('hidden')) {
    resultadosDOM.classList.remove('hidden');
  }
});

salesContainer.onEvent('fail', (err) => {
  document.querySelector('#js-loader').classList.remove('hidden');
  document.querySelector('#js-loader').classList.add('hidden');
});

salesContainer.setPaginationConfig({
  paginationContainer: document.querySelector('#js-paginacao-operadoras')
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

  if(salesContainer.get('active') === 'search') {
    requestHandler = async (params) => {
      await salesContainer.search({
        params: {
          por_pagina: salesContainer.get('search').get('pagination').options.perPage,
          ...params
        },
        body: { ...searchForm.serialize() }
      });
    }
  } else {
    requestHandler = async (params) => {
      await salesContainer.filter({
        params: {
          por_pagina: salesContainer.get('search').get('pagination').options.perPage,
          ...params,
        },
        body: {
          filters: { ...searchForm.serialize() },
          subfilters: { ...tableRender.serializeTableFilters() }
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

searchForm.onSubmit(async (event) => {
  await salesContainer.search({
    params: {
      por_pagina: document.querySelector('#js-por-pagina').value,
    },
    body: { ...searchForm.serialize() },
  });

  tableRender.clearFilters();
  window.scrollTo(0, document.querySelector('.resultados').offsetTop);
});

tableRender.onRenderRow((row, data) => {
  const selectedRows = tableRender.get('selectedRows');
  row.classList.remove('marcada');
  if (selectedRows.includes(row.dataset.id)) {
    row.classList.add('marcada');
  }

  Array.from(row.querySelectorAll('.actions-cell .tooltip-hint')).forEach((element) => {
    const title = data[element.dataset.title];
    const defaultTitle = element.dataset.defaultTitle;

    element.dataset.title = tableRender.formatCell(title, 'text', defaultTitle);
  });
  
  Array.from(row.querySelectorAll('.actions-cell img[data-image]')).forEach((element) => {
    const image = data[element.dataset.image];
    const defaultImage = element.dataset.defaultImage;

    const src = image || defaultImage;

    if(src) {
      element.dataset.image = src;
      element.src = src;
    }
  });

  const printActionDOM = row.querySelector('td a.link-impressao');

  printActionDOM.addEventListener('click', event => {
    showTicket(row.dataset.id);
  });
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
    });
  }, 500);
}

function showTicket(id) {
  const sale = salesContainer.get('data').get('sales').find(sale => sale.ID === id);
  Array.from(
    document.querySelectorAll('#comprovante-modal *[data-key]')
  ).forEach(element => {
    element.textContent = formatter.format((element.dataset.format || 'text'), sale[element.dataset.key], '');
  });

  document.querySelector('#comprovante-modal').dataset.saleId = id;
}

searchForm.get('form').querySelector('button[data-form-action="submit"')
  .addEventListener('click', searchForm.get('onSubmitHandler'));

Array.from(
  document.querySelectorAll('.modal button[data-action="print"]')
).forEach(buttonDOM => {
  buttonDOM.addEventListener('click', (e) => {
    const id = document.querySelector('#comprovante-modal').dataset.saleId || 0;
    openUrl(searchForm.get('form').dataset.urlImprimir.replace(':id', id));
  });
});

document.querySelector('#js-por-pagina')
  .addEventListener('change', onPerPageChanged);

document.querySelector('#js-exportar')
  .addEventListener('click', exportar);