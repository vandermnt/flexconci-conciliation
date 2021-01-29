const checker = new Checker();
const modalFilter = new ModalFilter();
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
  locale: 'pt-br'
});

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
  document.querySelector('#js-loader').classList.toggle('hidden');
});

salesContainer.onEvent('fetch', (sales) => {
  document.querySelector('#js-loader').classList.toggle('hidden');
  document.querySelector('#js-quantidade-registros').textContent = `(${sales.get('pagination').options.total || 0} registros)`;

  tableRender.set('data', {
    body: (sales.get('sales') || []),
    footer: (sales.get('totals') || {}),
  });
  tableRender.render();
  sales.get('pagination').render();
  Array.from(
    document.querySelectorAll('table a.link-impressao')
  ).forEach(element => {
    element.addEventListener('click', e => {
      const id = e.target.closest('tr').dataset.id;
      showTicket(id);
    });
  });
});

salesContainer.onEvent('search', (sales) => {
  const resultadosDOM = document.querySelector('.resultados');

  updateBoxes();

  if (resultadosDOM.classList.contains('hidden')) {
    resultadosDOM.classList.remove('hidden');
    window.scrollTo(0, document.querySelector('.resultados').offsetTop);
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
    if (salesContainer.get('active') === 'search') {
      await salesContainer.search({
        params: {
          por_pagina: pagination.options.perPage,
          page,
        },
        body: { ...searchForm.serialize() }
      });
    } else {
      await salesContainer.filter({
        params: {
          por_pagina: pagination.options.perPage,
          page,
        },
        body: {
          filters: { ...searchForm.serialize() },
          subfilters: { ...tableRender.serializeTableFilters() }
        }
      });
    }
  }
)

searchForm.onSubmit(async (event) => {
  await salesContainer.search({
    params: {
      por_pagina: document.querySelector('#js-por-pagina').value,
    },
    body: { ...searchForm.serialize() },
  });

  window.scrollTo(0, document.querySelector('.resultados').offsetTop);
});

tableRender.onRenderRow(row => {
  const selectedRows = tableRender.get('selectedRows');

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
  if (Object.keys(filters).length === 0) {
    await salesContainer.search({
      params: {
        page: 1,
        por_pagina: document.querySelector('#js-por-pagina').value,
      },
      body: {
        ...searchForm.serialize()
      }
    });
    return;
  }

  await salesContainer.filter({
    params: {
      por_pagina: document.querySelector('#js-por-pagina').value,
    },
    body: {
      filters: { ...searchForm.serialize() },
      subfilters: { ...filters }
    }
  });
});

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

function updateBoxes() {
  const currencyFormatter = new Intl.NumberFormat('pt-br', {
    style: 'currency',
    currency: 'BRL'
  });

  const totalBruto = salesContainer.get('search').get('totals').TOTAL_BRUTO;
  const totalLiquido = salesContainer.get('search').get('totals').TOTAL_LIQUIDO;
  const totalTaxa = salesContainer.get('search').get('totals').TOTAL_TAXA;
  const totalTarifaMinima = salesContainer.get('search').get('totals').TOTAL_TARIFA_MINIMA;

  document.querySelector('#js-bruto-box').dataset.value = totalBruto;
  document.querySelector('#js-bruto-box').textContent = currencyFormatter.format(totalBruto);
  document.querySelector('#js-liquido-box').dataset.value = totalLiquido;
  document.querySelector('#js-liquido-box').textContent = currencyFormatter.format(totalLiquido);
  document.querySelector('#js-taxa-box').dataset.value = totalTaxa;
  document.querySelector('#js-taxa-box').textContent = currencyFormatter.format(totalTaxa);
  document.querySelector('#js-tarifa-box').dataset.value = totalTarifaMinima;
  document.querySelector('#js-tarifa-box').textContent = currencyFormatter.format(totalTarifaMinima);
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
    element.textContent = tableRender.formatCell(sale[element.dataset.key], (element.dataset.format || 'text'), '');
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
  .addEventListener('change', async event => {
    salesContainer.get('search').get('pagination').setOptions({ perPage: event.target.value });
    salesContainer.get('filtered').get('pagination').setOptions({ perPage: event.target.value });
    if (salesContainer.get('active') === 'search') {
      await salesContainer.search({
        params: {
          por_pagina: event.target.value,
          page: 1,
        },
        body: { ...searchForm.serialize() }
      });
    } else {
      await salesContainer.filter({
        params: {
          por_pagina: event.target.value,
          page: 1,
        },
        body: {
          filters: { ...searchForm.serialize() },
          subfilters: { ...tableRender.serializeTableFilters() }
        }
      });
    }
  });

document.querySelector('#js-exportar')
  .addEventListener('click', exportar);
