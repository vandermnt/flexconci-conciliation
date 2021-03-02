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
const paymentsContainer = new PaymentsContainerProxy({
  id: 'recebimentos-operadoras',
  links: {
    search: searchForm.get('form').dataset.urlBuscarRecebimentos,
    filter: searchForm.get('form').dataset.urlFiltrarRecebimentos,
  }
});
const tableRender = createTableRender({
  table: '#js-tabela-recebimentos',
  locale: 'pt-br',
  formatter,
});
const boxes = getBoxes();

checker.addGroups([
  { name: 'empresa', options: { inputName: 'grupos_clientes' } },
  { name: 'adquirente', options: { inputName: 'adquirentes' } },
  { name: 'status-conciliacao', options: { inputName: 'status_conciliacao' } },
  { name: 'recebimento-conciliado-erp', options: { inputName: 'recebimento_conciliado_erp' } },
]);

modalFilter.addGroups([
  'empresa',
  'adquirente',
  'estabelecimento',
]);

searchForm.onSubmit(async (event) => {
  const resultadosDOM = document.querySelector('.resultados');

  await paymentsContainer.search({
    params: {
      por_pagina: paymentsContainer.get('search').get('pagination').options.perPage,
    },
    body: { ...searchForm.serialize() },
  });

  tableRender.clearFilters();

  if (resultadosDOM.classList.contains('hidden')) {
    resultadosDOM.classList.remove('hidden');
  }
  window.scrollTo(0, document.querySelector('.resultados').offsetTop);
});

paymentsContainer.setupApi({
  headers: {
    'X-CSRF-TOKEN': searchForm.getInput('_token').value,
    'Content-Type': 'application/json',
  }
});

paymentsContainer.onEvent('beforeFetch', () => {
  toggleElementVisibility('#js-loader');
});

paymentsContainer.onEvent('fetch', (payments) => {
  toggleElementVisibility('#js-loader');
  document.querySelector('#js-quantidade-registros').textContent = `(${payments.get('pagination').options.total || 0} registros)`;

  tableRender.set('data', {
    body: (payments.get('payments') || []),
    footer: (payments.get('totals') || {}),
  });
  tableRender.render();
  payments.get('pagination').render();
});

paymentsContainer.onEvent('search', (payments) => {
  const totals = payments.get('totals');
  updateBoxes(boxes, {
    ...totals,
    TOTAL_TAXA: (totals.TOTAL_TAXA || 0) * -1,
    TOTAL_ANTECIPACAO: (totals.TOTAL_ANTECIPACAO || 0) * -1,
    TOTAL_DESPESAS: (totals.TOTAL_DESPESAS || 0) * -1,
  });
});

paymentsContainer.onEvent('fail', (err) => {
  document.querySelector('#js-loader').classList.remove('hidden');
  document.querySelector('#js-loader').classList.add('hidden');
});

paymentsContainer.setPaginationConfig({
  paginationContainer: document.querySelector('#js-paginacao-recebimentos')
},
  async (page, pagination, event) => {
    await buildRequest({
      page,
      por_pagina: pagination.options.perPage,
    })
    .get();
  }
);

function buildRequest(params) {
  let requestHandler = () => {};

  if(paymentsContainer.get('active') === 'search') {
    requestHandler = async (params) => {
      await paymentsContainer.search({
        params: {
          por_pagina: paymentsContainer.get('search').get('pagination').options.perPage,
          ...params
        },
        body: { ...searchForm.serialize() }
      });
    }
  } else {
    requestHandler = async (params) => {
      await paymentsContainer.filter({
        params: {
          por_pagina: paymentsContainer.get('search').get('pagination').options.perPage,
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

tableRender.onFilter(async (filters) => {
  const params = {
    por_pagina: document.querySelector('#js-por-pagina').value,
  };

  paymentsContainer.toggleActiveData('filter');
  if (Object.keys(filters).length === 0) {
    paymentsContainer.toggleActiveData('search');
    params.page = 1;
  }

  await buildRequest(params).get();
});

async function onPerPageChanged(event) {
  paymentsContainer.get('search').get('pagination').setOptions({ perPage: event.target.value });
  paymentsContainer.get('filtered').get('pagination').setOptions({ perPage: event.target.value });
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

function closeRetornoModal() {
  document.querySelector('#js-retorno-recebimento-modal #js-data-inicial').valueAsDate = new Date();
  document.querySelector('#js-retorno-recebimento-modal #js-data-final').valueAsDate = new Date();

  $('#js-retorno-recebimento-modal').modal('hide');
}

function updatePayments(payments, newPayments, idKey = 'ID') {
  const updated = updateData([...payments], [...newPayments], idKey);
  const updatedPayments = updated.data;
  const affectedRows = updated.updated.reduce((ids, value) => [...ids, value.ID], []);

  paymentsContainer.get('data').set('payments', [...updatedPayments]);
  console.log(affectedRows);

  tableRender.set('selectedRows', affectedRows);
  tableRender.set('data', {
    body: ([...updatedPayments] || []),
    footer: ({ ...paymentsContainer.get('data').get('totals') } || {}),
  });
  tableRender.render();
}

function retornoRecebimentoErp() {
  const dataInicial = document.querySelector('#js-retorno-recebimento-modal #js-data-inicial').value;
  const dataFinal = document.querySelector('#js-retorno-recebimento-modal #js-data-final').value;

  if(!dataInicial || !dataFinal) {
    swal('Ooops...', 'A data inicial e final devem ser informadas!', 'error');
  }

  swal('Aguarde um momento...', 'O processo pode levar alguns segundos.', 'warning');
  toggleElementVisibility('#js-loader');
  api.get(searchForm.get('form').dataset.urlRetornoRecebimento, {
    params: {
      'data-inicial': dataInicial,
      'data-final': dataFinal,
    },
  })
    .then(res => {
      if(res.status === 'erro' && res.mensagem) {
        swal('Ooops...', res.mensagem, 'error');
        return;
      }

      const updatedPayments = res.pagamentos.reduce((values, id) => {
        return [
          ...values,
          {
            ID: id,
            RETORNO_ERP_BAIXA: 'Sim',
          }
        ];
      }, []);

      updatePayments(paymentsContainer.get('data').get('payments'), [...updatedPayments], 'ID');

      swal('Retorno Recebimento realizado!', `${res.vendas.length} de ${res.total} registros atualizados!`, 'success');
    })
    .catch((err) => {
        swal("Ooops...", 'Um erro inesperado ocorreu. Tente novamente mais tarde!', 'error');
    })
    .finally(() => {
        toggleElementVisibility('#js-loader');
    });

  closeRetornoModal();
}

document.querySelector('#js-por-pagina')
  .addEventListener('change', onPerPageChanged);

searchForm.get('form').querySelector('button[data-form-action="submit"')
  .addEventListener('click', searchForm.get('onSubmitHandler'));

document.querySelector('#js-exportar')
  .addEventListener('click', exportar);

document.querySelector('#js-abrir-modal-retorno')
  .addEventListener('click', () => $('#js-retorno-recebimento-modal').modal('show'));

document.querySelector('#js-cancelar-retorno-recebimento')
  .addEventListener('click', closeRetornoModal);

document.querySelector('#js-retorno-recebimento')
  .addEventListener('click', retornoRecebimentoErp);

document.querySelector('#js-retorno-recebimento-modal *[data-dismiss]')
  .addEventListener('click', closeRetornoModal);

document.querySelector('#dropdownUserSettings').addEventListener('click', (e) => {
    $('#dropdownUserSettings').dropdown('toggle');
});
