const checker = new Checker();
const modalFilter = new ModalFilter();
const formatter = new Formatter({
  locale: 'pt-BR',
  currencyOptions: {
    type: 'BRL'
  }
});
const searchForm = new SearchFormProxy({
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
const tableRender = new TableRender({
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

  updateBoxes(sales.get('totals'));

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

tableRender.onRenderRow(row => {
  const selectedRows = tableRender.get('selectedRows');
  const printActionDOM = row.querySelector('td a.link-impressao');

  printActionDOM.addEventListener('click', event => {
    showTicket(row.dataset.id);
  });
  row.classList.remove('marcada');
  if (selectedRows.includes(row.dataset.id)) {
    row.classList.add('marcada');
  }
});

tableRender.onRenderCell((cell, data) => {
  if (cell.classList.contains('tooltip-hint')) {
    const title = data[cell.dataset.title];
    const defaultTitle = cell.dataset.defaultTitle;

    cell.dataset.title = tableRender.formatCell(title, 'text', defaultTitle);
  }

  if (cell.dataset.image) {
    const iconContainer = cell.querySelector('.icon-image');
    const imageUrl = data[cell.dataset.image];
    const defaultImageUrl = cell.dataset.defaultImage;

    if (imageUrl || defaultImageUrl) {
      iconContainer.style.backgroundImage = `url("${imageUrl || defaultImageUrl}")`;
      const title = data[iconContainer.dataset.title];
      const defaultTitle = iconContainer.dataset.defaultTitle;

      iconContainer.dataset.title = tableRender.formatCell(title, 'text', defaultTitle);
      return;
    }
    iconContainer.classList.toggle('hidden');
  }

  const cellValue = data[cell.dataset.column];
  const defaultCellValue = data[cell.dataset.defaultValue];
  const format = cell.dataset.format || 'text';
  const value = tableRender.formatCell(cellValue, format, defaultCellValue);

  cell.textContent = value;
});

tableRender.onSelectRow((elementDOM, selectedRows) => {
  let tr = elementDOM;
  if (['a', 'i'].includes(elementDOM.tagName.toLowerCase())) {
    return;
  }

  if (elementDOM.tagName.toLowerCase() !== 'tr') {
    tr = elementDOM.closest('tr');
  }

  if (!tr) {
    return;
  }

  tr.classList.remove('marcada');
  if (selectedRows.includes(tr.dataset.id)) {
    tr.classList.add('marcada');
  } else {
    tr.classList.remove('marcada');
  }
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

function toggleElementVisibility(selector = '') {
  const element = document.querySelector(selector);

  if(element) {
    element.classList.toggle('hidden');
  }
}

function onCancelModalSelection(event) {
  const buttonDOM = event.target;
  const groupName = buttonDOM.dataset.group;

  checker.uncheckAll(groupName);
  checker.setValuesToTextElement(groupName, 'descricao');
}

function onConfirmModalSelection(event) {
  const buttonDOM = event.target;
  const groupName = buttonDOM.dataset.group;

  checker.setValuesToTextElement(groupName, 'descricao');
};

async function onPerPageChanged(event) {
  salesContainer.get('search').get('pagination').setOptions({ perPage: event.target.value });
  salesContainer.get('filtered').get('pagination').setOptions({ perPage: event.target.value });
  await buildRequest({
      page: 1,
      por_pagina: event.target.value,
    })
    .get();
}

function getBoxes() {
  const boxes = [];

  Array.from(document.querySelectorAll('.box')).forEach(boxDOM => {
    const box = new Box({
      element: boxDOM,
      defaultValue: 0,
      format: boxDOM.dataset.format,
      formatter,
    });
    boxes.push(box);
  });

  return boxes;
}

function updateBoxes(totals) {
  boxes.forEach(box => {
    box.set('value', totals[box.get('element').dataset.key]);
    box.render();
  });
}

function openUrl(baseUrl, params) {
  const url = api.urlBuilder(baseUrl, params);
  const a = document.createElement('a');

  a.href = url;
  a.target = '_blank';
  a.click();
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

Array.from(
  document.querySelectorAll('.modal button[data-action="confirm"]')
).forEach(buttonDOM => {
  buttonDOM.addEventListener('click', onConfirmModalSelection);
});

Array.from(
  document.querySelectorAll('.modal button[data-action="cancel"]')
).forEach(buttonDOM => {
  buttonDOM.addEventListener('click', onCancelModalSelection);
});

Array.from(
  document.querySelectorAll('form button[data-form-action="clear"]')
).forEach(buttonDOM => {
  buttonDOM.addEventListener('click', e => searchForm.clear());
});

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