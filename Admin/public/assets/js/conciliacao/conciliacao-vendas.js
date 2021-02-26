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

function updateSales(sales = [], newData = [], idKey = 'ID') {
  const newSales = Array.isArray(newData) ? newData : [newData];
  const data = [];

  newSales.forEach(newSale => {
    const index = sales.findIndex(sale => String(sale[idKey]) === String(newSale[idKey]));

    if(index !== -1) {
      sales.splice(index, 1, {
        ...sales[index],
        ...newSale
      });

      data.push({ ...sales[index], ...newSale });
    }
  });

  return {
    data: sales,
    updated: data,
  };
}

function removeSales(sales = [], ids = [], idKey = '') {
  const idArray = Array.isArray(ids) ? ids : [ids];
  const removed = [];

  idArray.forEach(id => {
    const index = sales.findIndex(sale => String(sale[idKey]) === String(id));

    if(index !== -1) {
      removed.push({ ...sales[index] });
      sales.splice(index, 1);
    }
  });

  return {
    data: sales,
    removed
  }
}

function updateTotals(totals, newData) {
  const newTotals = Object.keys(newData).reduce((updated, key) => {
    updated[key] = (Number(totals[key]) || 0) + (Number(newData[key]) || 0);
    return updated;
  }, {});

  return {
    ...totals,
    ...newTotals,
  };
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

    const salesErp = salesErpContainer.get('data');
    const sales = salesContainer.get('data');

    const updatedSalesErp = updateSales([...salesErp.get('sales')], {
      ...json.erp,
      ID_ERP: json.erp.ID,
      STATUS_CONCILIACAO: json.STATUS_CONCILIACAO,
      STATUS_CONCILIACAO_IMAGEM: json.STATUS_CONCILIACAO_IMAGEM,
    }, 'ID_ERP').data;

    const updatedSales = removeSales([...sales.get('sales')], json.operadora.ID, 'ID').data;

    const totalsOperadoras = updateTotals(sales.get('totals'), {
      TOTAL_BRUTO: (json.operadora.TOTAL_BRUTO * -1),
      TOTAL_LIQUIDO: (json.operadora.TOTAL_LIQUIDO * -1),
      TOTAL_TAXA: (json.operadora.TOTAL_TAXA * -1),
      TOTAL_PENDENCIAS_OPERADORAS: (json.operadora.TOTAL_BRUTO * -1),
    });

    const totalsErp = updateTotals(salesErp.get('totals'), {
      TOTAL_CONCILIADO_MANUAL: json.erp.TOTAL_BRUTO,
      TOTAL_NAO_CONCILIADO:  (json.erp.TOTAL_BRUTO * -1)
    });

    sales.set('sales', [...updatedSales]);
    sales.set('totals', { ...totalsOperadoras });
    salesErp.set('sales', [...updatedSalesErp]);
    salesErp.set('totals', { ...totalsErp });

    tableRenderErp.set('data', {
      body: salesErp.get('sales'),
      footer: totalsErp,
    });
    tableRenderErp.render();
    tableRender.set('data', {
      body: sales.get('sales'),
      footer: totalsOperadoras,
    });
    tableRender.render();

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

    const salesErp = salesErpContainer.get('data');
    const sales = salesContainer.get('data');
    
    const updatedSalesErp = updateSales([...salesErp.get('sales')], {
      ...json.erp,
      ID_ERP: json.erp.ID,
      STATUS_CONCILIACAO: json.STATUS_CONCILIACAO,
      STATUS_CONCILIACAO_IMAGEM: json.STATUS_CONCILIACAO_IMAGEM,
    }, 'ID_ERP').data;

    const totalsOperadoras = updateTotals(sales.get('totals'), {
      TOTAL_BRUTO: json.operadora.TOTAL_BRUTO,
      TOTAL_LIQUIDO: json.operadora.TOTAL_LIQUIDO,
      TOTAL_TAXA: json.operadora.TOTAL_TAXA,
      TOTAL_PENDENCIAS_OPERADORAS: json.operadora.TOTAL_BRUTO
    });

    const totalsErp = updateTotals(salesErp.get('totals'), {
      TOTAL_CONCILIADO_MANUAL: (json.erp.TOTAL_BRUTO * -1),
      TOTAL_NAO_CONCILIADO: json.erp.TOTAL_BRUTO
    });

    sales.set('totals', { ...totalsOperadoras });
    salesErp.set('sales', [...updatedSalesErp]);
    salesErp.set('totals', { ...totalsErp });

    tableRenderErp.set('data', {
      body: salesErp.get('sales'),
      footer: totalsErp,
    });
    tableRenderErp.render();
    tableRender.set('data', {
      body: sales.get('sales'),
      footer: totalsOperadoras,
    });
    tableRender.render();

    updateBoxes(boxes, {
      TOTAL_NAO_CONCILIADO: totalsErp.TOTAL_NAO_CONCILIADO,
      TOTAL_CONCILIADO_MANUAL: totalsErp.TOTAL_CONCILIADO_MANUAL,
      TOTAL_PENDENCIAS_OPERADORAS: totalsOperadoras.TOTAL_PENDENCIAS_OPERADORAS,
    });

    swal("Desconciliação realizada!", json.mensagem, "success");
  });
}

function openJustifyModal(event) {
  const buttonDOM = event.target;
  const isErp = buttonDOM.dataset.type === 'erp';

  if(isErp && selectedSales.erp.length < 1) {
    swal('Ooops...', 'Selecione ao menos uma venda ERP.', 'error');
    return;
  } else if(!isErp && selectedSales.operadoras.length < 1) {
    swal('Ooops...', 'Selecione ao menos uma venda operadora.', 'error');
    return;
  }

  const justifyButtonDOM = recreateNode('#js-justificar-modal #js-justificar');
  if(justifyButtonDOM) {
    justifyButtonDOM.addEventListener('click', isErp ? justifyErp : justifyOperadora);
  }

  $('#js-justificar-modal').modal('show');
}

function closeJustifyModal() {
  const justifyButtonDOM = recreateNode('#js-justificar-modal #js-justificar');
  if(justifyButtonDOM) {
    justifyButtonDOM.addEventListener('click', (e) => $('#js-justificar-modal').modal('hide'));
  }

  document.querySelector('select[name="justificativa"]').value = "";
  $('#js-justificar-modal').modal('hide');
}

function justifyErp() {
  const baseUrl = searchForm.get('form').dataset.urlJustificarErp;
  const justificativaDOM = document.querySelector('select[name="justificativa"]');
  const justificativa = justificativaDOM.value;
  toggleElementVisibility('#js-loader');
  api.post(baseUrl, {
    ...apiConfig,
    body: JSON.stringify({
      id: selectedSales.erp,
      justificativa,
    })
  })
  .then(json => {
    if(json.status !== 'sucesso' && json.mensagem) {
      swal('Ooops...', json.mensagem, 'error');
      return;
    }

    const salesErp = salesErpContainer.get('data');

    const updatedSalesErp = updateSales([ ...salesErp.get('sales') ], [...json.vendas], 'ID_ERP').data;
    const totalsErp = updateTotals({ ...salesErp.get('totals') }, {
      TOTAL_JUSTIFICADO: json.totais.TOTAL_BRUTO,
      TOTAL_NAO_CONCILIADO: (json.totais.TOTAL_BRUTO * -1)
    });

    salesErp.set('sales', [...updatedSalesErp]);
    salesErp.set('totals', { ...totalsErp });

    selectedSales.erp = [];

    updateBoxes(boxes, {
      TOTAL_JUSTIFICADO: totalsErp.TOTAL_JUSTIFICADO,
      TOTAL_NAO_CONCILIADO: totalsErp.TOTAL_NAO_CONCILIADO,
    });

    tableRenderErp.set('data', {
      body: ([...updatedSalesErp] || []),
      footer: ({ ...totalsErp } || {}),
    });

    tableRenderErp.render();
    swal('Justificativa realizada!', json.mensagem, 'success'); 
  })
  .finally(() => {
    justificativaDOM.value = "";
    toggleElementVisibility('#js-loader');
    closeJustifyModal();
  });
}

function justifyOperadora() {
  const baseUrl = searchForm.get('form').dataset.urlJustificarOperadoras;
  const justificativaDOM = document.querySelector('select[name="justificativa"]');
  const justificativa = justificativaDOM.value;

  toggleElementVisibility('#js-loader');
  api.post(baseUrl, {
    ...apiConfig,
    body: JSON.stringify({
      id: selectedSales.operadoras,
      justificativa,
    })
  })
  .then(json => {
    if(json.status !== 'sucesso' && json.mensagem) {
      swal('Ooops...', json.mensagem, 'error');
      return;
    }

    const sales = salesContainer.get('data');
    const ids = json.vendas.reduce((values, venda) => [...values, venda.ID], []);

    const updatedSales = removeSales([...sales.get('sales')], ids, 'ID').data;
    const totals = updateTotals({ ...sales.get('totals') }, {
      TOTAL_BRUTO: (json.totais.TOTAL_BRUTO * -1),
      TOTAL_LIQUIDO: (json.totais.TOTAL_LIQUIDO * -1),
      TOTAL_TAXA: (json.totais.TOTAL_TAXA * -1),
      TOTAL_PENDENCIAS_OPERADORAS: (json.totais.TOTAL_BRUTO * -1),
    })
    const totalRegister = updateTotals({
      total: sales.get('pagination').options.total,
    }, {
      total: (json.vendas.length * -1)
    }).total;

    sales.get('pagination').setOptions({
      total: totalRegister
    });

    selectedSales.operadoras = [];

    document.querySelector(`#js-quantidade-registros-operadoras`).textContent = `(${totalRegister || 0} registros)`;


    sales.set('sales', [...updatedSales]);
    sales.set('totals', { ...totals });

    updateBoxes(boxes, {
      TOTAL_PENDENCIAS_OPERADORAS: totals.TOTAL_PENDENCIAS_OPERADORAS,
    });

    tableRender.set('data', {
      body: ([...updatedSales] || []),
      footer: ({ ...totals } || {}),
    });
    tableRender.render();
    
    swal('Justificativa realizada!', json.mensagem, 'success'); 
  })
  .finally(() => {
    justificativaDOM.value = "";
    toggleElementVisibility('#js-loader');
    closeJustifyModal();
  });
}

function confirmUnjustify() {
  if(selectedSales.erp.length < 1) {
    swal('Ooops...', 'Selecione ao menos uma venda ERP.', 'error');
    return;
  }

  openConfirmDialog(
    'Tem certeza que deseja desfazer a justificativa?',
    (value) => {
      if(value) unjustify();
    }
  );
}

function unjustify() {
  const baseUrl = searchForm.get('form').dataset.urlDesjustificarErp;
  toggleElementVisibility('#js-loader');
  api.post(baseUrl, {
    ...apiConfig,
    body: JSON.stringify({
      id: selectedSales.erp,
    })
  })
  .then(json => {
    if(json.status !== 'sucesso' && json.mensagem) {
      swal('Ooops...', json.mensagem, 'error');
      return;
    }

    const salesErp = salesErpContainer.get('data');

    const updatedSalesErp = updateSales([ ...salesErp.get('sales') ], [...json.vendas], 'ID_ERP').data;
    const totalsErp = updateTotals({ ...salesErp.get('totals') }, {
      TOTAL_JUSTIFICADO: (json.totais.TOTAL_BRUTO * -1),
      TOTAL_NAO_CONCILIADO: json.totais.TOTAL_BRUTO
    });

    salesErp.set('sales', [...updatedSalesErp]);
    salesErp.set('totals', { ...totalsErp });

    selectedSales.erp = [];

    updateBoxes(boxes, {
      TOTAL_JUSTIFICADO: totalsErp.TOTAL_JUSTIFICADO,
      TOTAL_NAO_CONCILIADO: totalsErp.TOTAL_NAO_CONCILIADO,
    });

    tableRenderErp.set('data', {
      body: ([...updatedSalesErp] || []),
      footer: ({ ...totalsErp } || {}),
    });

    tableRenderErp.render();
    swal('Justificativa desfeita!', json.mensagem, 'success'); 
  })
  .finally(() => {
    toggleElementVisibility('#js-loader');
    closeJustifyModal();
  });
}

function exportar(event) {
  const isErp = event.target.dataset.type === 'erp';
  const baseUrl = searchForm.get('form').dataset[isErp ? 'urlExportarErp' : 'urlExportarOperadoras'];
  const currentTableRender = isErp ? tableRenderErp : tableRender;

  swal('Aguarde um momento...', 'A sua planilha está sendo gerada.', 'warning');
  setTimeout(() => {
    openUrl(baseUrl, {
      ...searchForm.serialize(),
      ...currentTableRender.serializeTableFilters(),
    });
  }, 500);
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
document.querySelector('#js-justificar-erp')
  .addEventListener('click', openJustifyModal);
document.querySelector('#js-justificar-operadora')
  .addEventListener('click', openJustifyModal);
document.querySelector('#js-desjustificar-erp').addEventListener('click', confirmUnjustify);
document.querySelector('#js-exportar-erp').addEventListener('click', exportar);
document.querySelector('#js-exportar-operadoras').addEventListener('click', exportar);

Array.from(document.querySelectorAll('#js-justificar-modal *[data-dismiss="modal"]'))
  .forEach(element => {
    element.addEventListener('click', closeJustifyModal);
  });