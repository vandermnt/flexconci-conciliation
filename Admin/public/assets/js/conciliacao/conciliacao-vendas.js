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
  checker
});
const boxes = getBoxes();
const apiConfig = {
  headers: {
    'X-CSRF-TOKEN': searchForm.getInput('_token').value,
    'Content-Type': 'application/json',
  }
};
const selectedSales = {
  erp: [],
  operadoras: [],
};

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

const _events = {
  salesContainer: {
    onFetch: (key, sales) => {
      document.querySelector(`#js-quantidade-registros-${key}`).textContent = `(${sales.get('pagination').options.total || 0} registros)`;
      const currentTableRender = key === 'erp' ? tableRenderErp : tableRender;
      currentTableRender.set('data', {
        body: (sales.get('sales') || []),
        footer: (sales.get('totals') || {}),
      });
      currentTableRender.render();
      sales.get('pagination').render();
    }
  },
  table: {
    onFilter: async (key, filters) => {
      toggleElementVisibility('#js-loader');
      const currentSalesContainer = key === 'erp' ? salesErpContainer : salesContainer;
    
      const params = {
        por_pagina: document.querySelector(`#js-por-pagina-${key}`).value,
      };
    
      currentSalesContainer.toggleActiveData('filter');
      if (Object.keys(filters).length === 0) {
        currentSalesContainer.toggleActiveData('search');
        params.page = 1;
      }
    
      await buildRequest(key, params).get();
      
      toggleElementVisibility('#js-loader');
    },
    shouldSelectRow: (elementDOM) => {
      let shouldSelect = _defaultEvents.table.shouldSelectRow(elementDOM);
      if (['i', 'input'].includes(elementDOM.tagName.toLowerCase())) {
        shouldSelect = false;
      } else {
        shouldSelect = true;
      }

      return shouldSelect;
    },
    onRenderRow: (key, row, data) => {
      const checkboxDOM = row.querySelector('td input[data-value-key]');
      const value = data[checkboxDOM.dataset.valueKey];
      checkboxDOM.value = value;
      checkboxDOM.checked = selectedSales[key].includes(value);
      
      checkboxDOM.addEventListener('change', event => {
        const target = event.target;
        const value = event.target.value;
        
        if(target.checked && !selectedSales[key].includes(value)) {
          selectedSales[key].push(value);
        } else if(!target.checked && selectedSales[key].includes(value)) {
          selectedSales[key] = [...selectedSales[key].filter(selected => selected !== value)];
        }
      });
      _defaultEvents.table.onRenderRow(row, data);
    }
  }
}

checker.addGroups([
  { name: 'empresa', options: { inputName: 'grupos_clientes' } },
  { name: 'status-conciliacao', options: { inputName: 'status_conciliacao' } },
]);

modalFilter.addGroups([
  'empresa',
]);

function buildRequest(key = 'erp', params) {
  let requestHandler = () => {};

  const currentSalesContainer = key === 'erp' ? salesErpContainer : salesContainer;
  const currentTableRender = key === 'erp' ? tableRenderErp : tableRender;

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

function getPaginationConfig(key) {
  return {
    paginationContainer: document.querySelector(`#js-paginacao-${key}`),
    navigationHandler: async (page, pagination, event) => {
      toggleElementVisibility('#js-loader');
  
      await buildRequest(key, {
          page,
          por_pagina: pagination.options.perPage,
        })
        .get();
  
      toggleElementVisibility('#js-loader');
    }
  }
}

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

  tableRender.clearFilters();
  tableRenderErp.clearFilters();
  if (resultadosDOM.classList.contains('hidden')) {
    resultadosDOM.classList.remove('hidden');
  }
  window.scrollTo(0, document.querySelector('.resultados').offsetTop);

  toggleElementVisibility('#js-loader');
});

salesContainer.setupApi(apiConfig);
salesContainer.setPaginationConfig(
  { paginationContainer: getPaginationConfig('operadoras').paginationContainer },
  getPaginationConfig('operadoras').navigationHandler
);
salesErpContainer.setupApi(apiConfig);
salesErpContainer.setPaginationConfig(
  { paginationContainer: getPaginationConfig('erp').paginationContainer },
  getPaginationConfig('erp').navigationHandler
);

salesContainer.onEvent('fetch', (sales) => _events.salesContainer.onFetch('operadoras', sales));
salesErpContainer.onEvent('fetch', (sales) => _events.salesContainer.onFetch('erp', sales));

salesContainer.onEvent('search', (sales) => {
  const totals = sales.get('totals');
  updateBoxes(boxes, { 
    TOTAL_PENDENCIAS_OPERADORAS: totals.TOTAL_PENDENCIAS_OPERADORAS,
  });
});
salesErpContainer.onEvent('search', (sales) => {
  const totals = sales.get('totals');
  updateBoxes(boxes, { 
    ...totals,
  });
});

tableRenderErp.onFilter(async (filters) => await _events.table.onFilter('erp', filters));
tableRender.onFilter(async (filters) => await _events.table.onFilter('operadoras', filters));

tableRender.onRenderRow((row, data) => _events.table.onRenderRow('operadoras', row, data));
tableRenderErp.onRenderRow((row, data) => _events.table.onRenderRow('erp', row, data));

tableRender.shouldSelectRow(_events.table.shouldSelectRow);
tableRenderErp.shouldSelectRow(_events.table.shouldSelectRow);

async function onPerPageChanged(key = 'erp', event) {
  const currentSalesContainer = key === 'erp' ? salesErpContainer : salesContainer;
  currentSalesContainer.get('search').get('pagination').setOptions({ perPage: event.target.value });
  currentSalesContainer.get('filtered').get('pagination').setOptions({ perPage: event.target.value });

  toggleElementVisibility('#js-loader');

  await buildRequest(key, {
      page: 1,
      por_pagina: event.target.value,
    })
    .get();

  toggleElementVisibility('#js-loader');
}

function confirmConciliacao() {
  if(selectedSales.erp.length !== 1 || selectedSales.operadoras.length !== 1) {
    swal('Ooops...', 'Selecione apenas uma venda ERP e uma operadora para realizar a conciliação.', 'error');
    return;
  }
  
  openConfirmDialog(
    'Tem certeza que deseja realizar a conciliação?', 
    (value) => {
      if(value) conciliar();
    }
  );
}

function conciliar() {
  toggleElementVisibility('#js-loader');
  const baseUrl = searchForm.get('form').dataset.urlConciliarManualmente;
  api.post(baseUrl, {
    ...apiConfig,
    body: JSON.stringify({
      _token: searchForm.getInput('_token').value,
      id_erp: selectedSales.erp,
      id_operadora: selectedSales.operadoras,
    })
  }).then(json => {
    toggleElementVisibility('#js-loader');
    if(json.status === 'erro' && json.mensagem) {
      swal('Ooops...', json.mensagem, 'error');
      return;
    }

    selectedSales.erp = [];
    selectedSales.operadoras = [];
    const salesErp = salesErpContainer.get('data').get('sales');
    const sales = salesContainer.get('data').get('sales');
    const totalsOperadoras = {
      ...salesContainer.get('data').get('totals'),
      TOTAL_PENDENCIAS_OPERADORAS: Number(salesContainer.get('data').get('totals').TOTAL_PENDENCIAS_OPERADORAS) - Number(json.operadora.TOTAL_BRUTO),
    };
    const totalsErp = {
      ...salesErpContainer.get('data').get('totals'),
      TOTAL_CONCILIADO_MANUAL: Number(salesErpContainer.get('data').get('totals').TOTAL_CONCILIADO_MANUAL) + Number(json.erp.TOTAL_BRUTO),
      TOTAL_NAO_CONCILIADO: Number(salesErpContainer.get('data').get('totals').TOTAL_NAO_CONCILIADO) - Number(json.erp.TOTAL_BRUTO),
    };
    const index = sales.findIndex(item => String(item.ID) === String(json.operadora.ID));
    const indexErp = salesErp.findIndex(item => String(item.ID_ERP) === String(json.erp.ID));

    salesContainer.get('data').set('totals', { ...totalsOperadoras });
    salesErpContainer.get('data').set('totals', { ...totalsErp });

    if(index !== -1) {
      const sale = {
        ...sales[index],
        DESCRICAO_ERP: json.operadora.DESCRICAO_ERP,
        STATUS_CONCILIACAO: json.STATUS_CONCILIACAO,
        STATUS_CONCILIACAO_IMAGEM: json.STATUS_CONCILIACAO_IMAGEM,
      }
      sales.splice(index, 1, sale);
      tableRender.set('data', {
        body: sales,
        footer: totalsOperadoras
      });
      tableRender.render();
    }
    
    if(indexErp !== -1) {
      const saleErp = {
        ...salesErp[indexErp],
        DATA_CONCILIACAO: json.erp.DATA_CONCILIACAO,
        HORA_CONCILIACAO: json.erp.HORA_CONCILIACAO,
        STATUS_CONCILIACAO: json.STATUS_CONCILIACAO,
        STATUS_CONCILIACAO_IMAGEM: json.STATUS_CONCILIACAO_IMAGEM,
      }
      salesErp.splice(indexErp, 1, saleErp);

      tableRenderErp.set('data', {
        body: salesErp,
        footer: totalsErp,
      });
      tableRenderErp.render();
    }

    updateBoxes(boxes, {
      TOTAL_NAO_CONCILIADO: totalsErp.TOTAL_NAO_CONCILIADO,
      TOTAL_CONCILIADO_MANUAL: totalsErp.TOTAL_CONCILIADO_MANUAL,
      TOTAL_PENDENCIAS_OPERADORAS: totalsOperadoras.TOTAL_PENDENCIAS_OPERADORAS,
    });

    swal("Conciliação realizada!", json.mensagem, "success");
  });
}

function confirmDesconciliacao() {
  if(selectedSales.erp.length !== 1) {
    swal('Ooops...', 'Para realizar a desconciliação selecione apenas uma venda ERP.', 'error');
    return;
  }
  
  openConfirmDialog(
    'Tem certeza que deseja desconciliar a venda?', 
    (value) => {
      if(value) desconciliar();
    }
  );
}

function desconciliar() {
  toggleElementVisibility('#js-loader');
  const baseUrl = searchForm.get('form').dataset.urlDesconciliarManualmente;
  api.post(baseUrl, {
    ...apiConfig,
    body: JSON.stringify({
      _token: searchForm.getInput('_token').value,
      id_erp: selectedSales.erp,
    })
  }).then(json => {
    toggleElementVisibility('#js-loader');
    if(json.status === 'erro' && json.mensagem) {
      swal('Ooops...', json.mensagem, 'error');
      return;
    }

    selectedSales.erp = [];
    selectedSales.operadoras = [];
    const salesErp = salesErpContainer.get('data').get('sales');
    const sales = salesContainer.get('data').get('sales');
    const totalsOperadoras = {
      ...salesContainer.get('data').get('totals'),
      TOTAL_PENDENCIAS_OPERADORAS: Number(salesContainer.get('data').get('totals').TOTAL_PENDENCIAS_OPERADORAS) + Number(json.operadora.TOTAL_BRUTO),
    };
    const totalsErp = {
      ...salesErpContainer.get('data').get('totals'),
      TOTAL_CONCILIADO_MANUAL: Number(salesErpContainer.get('data').get('totals').TOTAL_CONCILIADO_MANUAL) - Number(json.erp.TOTAL_BRUTO),
      TOTAL_NAO_CONCILIADO: Number(salesErpContainer.get('data').get('totals').TOTAL_NAO_CONCILIADO) + Number(json.erp.TOTAL_BRUTO),
    };

    const index = sales.findIndex(item => String(item.ID) === String(json.operadora.ID));
    const indexErp = salesErp.findIndex(item => String(item.ID_ERP) === String(json.erp.ID));

    salesContainer.get('data').set('totals', { ...totalsOperadoras });
    salesErpContainer.get('data').set('totals', { ...totalsErp });

    if(index !== -1) {
      const sale = {
        ...sales[index],
        DESCRICAO_ERP: json.operadora.DESCRICAO_ERP,
        STATUS_CONCILIACAO: json.STATUS_CONCILIACAO,
        STATUS_CONCILIACAO_IMAGEM: json.STATUS_CONCILIACAO_IMAGEM,
      }
      sales.splice(index, 1, sale);
      tableRender.set('data', {
        body: sales,
        footer: { ...totalsOperadoras },
      });
      tableRender.render();
    }
    
    if(indexErp !== -1) {
      const saleErp = {
        ...salesErp[indexErp],
        DATA_CONCILIACAO: json.erp.DATA_CONCILIACAO,
        HORA_CONCILIACAO: json.erp.HORA_CONCILIACAO,
        STATUS_CONCILIACAO: json.STATUS_CONCILIACAO,
        STATUS_CONCILIACAO_IMAGEM: json.STATUS_CONCILIACAO_IMAGEM,
      }
      salesErp.splice(indexErp, 1, saleErp);

      tableRenderErp.set('data', {
        body: salesErp,
        footer: totalsErp,
      });
      tableRenderErp.render();
    }

    updateBoxes(boxes, {
      TOTAL_NAO_CONCILIADO: totalsErp.TOTAL_NAO_CONCILIADO,
      TOTAL_CONCILIADO_MANUAL: totalsErp.TOTAL_CONCILIADO_MANUAL,
      TOTAL_PENDENCIAS_OPERADORAS: totalsOperadoras.TOTAL_PENDENCIAS_OPERADORAS,
    });

    swal("Desconciliação realizada!", json.mensagem, "success");
  });
}

searchForm.get('form').querySelector('button[data-form-action="submit"')
  .addEventListener('click', searchForm.get('onSubmitHandler'));

document.querySelector('#js-por-pagina-erp')
  .addEventListener('change', (event) => onPerPageChanged('erp', event));
document.querySelector('#js-por-pagina-operadoras')
  .addEventListener('change', (event) => onPerPageChanged('operadoras', event));

document.querySelector('#js-conciliar')
  .addEventListener('click', confirmConciliacao);
document.querySelector('#js-desconciliar')
  .addEventListener('click', confirmDesconciliacao);