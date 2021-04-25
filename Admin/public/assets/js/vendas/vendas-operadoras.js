const scrollableDragger = createScrollableTableDragger({
  wrapper: '.table-responsive',
  table: '.table-responsive > table#js-tabela-operadoras',
  draggerConfig: {
    mode: 'column',
    dragHandler: '.draggable',
    onlyBody: false,
    animation: 300
  },
  rows: ['#js-tabela-operadoras tbody tr']
});

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
const tableConfig = new TableConfig({
  tableSelector: '#js-tabela-operadoras',
  rootElement: '#js-table-config',
});
const boxes = getBoxes();
const apiConfig = {
    headers: {
        'X-CSRF-TOKEN': searchForm.getInput('_token').value,
        'Content-Type': 'application/json',
    }
};
let selectedSales = [];

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

salesContainer.setupApi(apiConfig);

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
    TOTAL_TARIFA_MINIMA: totals.TOTAL_TARIFA_MINIMA * -1,
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

tableRender.shouldSelectRow(elementDOM => {
    let shouldSelect = _defaultEvents.table.shouldSelectRow(elementDOM);
    if (['i', 'input'].includes(elementDOM.tagName.toLowerCase())) {
        shouldSelect = false;
    } else {
        shouldSelect = true;
    }

    return shouldSelect;
});

tableRender.onRenderRow((row, data, tableRenderInstance) => {
    const checkboxDOM = row.querySelector('td input[data-value-key]');
    const value = data[checkboxDOM.dataset.valueKey];
    checkboxDOM.value = value;
    checkboxDOM.checked = selectedSales.includes(value);

    checkboxDOM.addEventListener('change', event => {
        const target = event.target;
        const value = event.target.value;

        if (target.checked && !selectedSales.includes(value)) {
            selectedSales.push(value);
        } else if (!target.checked && selectedSales.includes(value)) {
            selectedSales = [...selectedSales.filter(selected => selected !== value)];
        }
    });

    const showDetailsDOM = row.querySelector('td .js-show-details');

    showDetailsDOM.addEventListener('click', event => {
        showTicket(row.dataset.id);
    });

    _defaultEvents.table.onRenderRow(row, data, tableRenderInstance);
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

tableRender.onSort(async (elementDOM, tableInstance) => {
  const params = {
    por_pagina: document.querySelector('#js-por-pagina').value,
  };

  _defaultEvents.table.onSort(elementDOM, tableInstance);
  await buildRequest(params).get();
})

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
    });
  }, 500);
}

function retornoCsv() {
  swal('Aguarde um momento...', 'A sua planilha está sendo gerada.', 'warning');
  setTimeout(() => {
    openUrl(searchForm.get('form').dataset.urlRetornoCsv, {
      ...searchForm.serialize(),
      ...tableRender.serializeTableFilters(),
      ...serializeTableSortToExport(tableRender.serializeSortFilter()),
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

function confirmUnjustify() {
    if (selectedSales.length < 1) {
        swal('Ooops...', 'Selecione ao menos uma venda operadora.', 'error');
        return;
    }

    openConfirmDialog(
        'Tem certeza que deseja desfazer a justificativa?',
        (value) => {
            if (value) unjustify();
        }
    );
}

function unjustify() {
    const baseUrl = searchForm.get('form').dataset.urlDesjustificar;
    toggleElementVisibility('#js-loader');
    api.post(baseUrl, {
        ...apiConfig,
        body: JSON.stringify({
            id: selectedSales,
        })
    })
        .then(json => {
            if (json.status !== 'sucesso' && json.mensagem) {
                swal('Ooops...', json.mensagem, 'error');
                return;
            }

            const sales = salesContainer.get('data');

            const updatedSales = updateData([...sales.get('sales')], [...json.vendas], 'ID').data;

            sales.set('sales', [...updatedSales]);

            selectedSales = [];

            tableRender.set('data', {
                body: ([...updatedSales] || []),
                footer: (sales.get('totals') || {}),
            });

            tableRender.render();
            swal('Justificativa desfeita!', json.mensagem, 'success');
        })
        .finally(() => {
            toggleElementVisibility('#js-loader');
        });
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

document.querySelector('#js-retorno-csv')
  .addEventListener('click', retornoCsv);

document.querySelector('#js-desjustificar')
  .addEventListener('click', confirmUnjustify);

window.addEventListener('load', () => {
  tableConfig.init();
  tableRender.afterRender((tableInstance) => {
    tableConfig.get('sectionContainer').refreshAll();
    scrollableDragger.fixator.update();
  });
});
