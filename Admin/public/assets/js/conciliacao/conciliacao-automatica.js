const formatadorMoeda = new Intl.NumberFormat('pt-br', {
  style: 'currency',
  currency: 'BRL'
});
const formatadorDecimal = new Intl.NumberFormat('pt-br', {
  style: 'decimal',
  maximumFractionDigits: 2,
});

function VendasProxy(id) {
  return new Proxy({
    id,
    porPagina: 5,
    marcacoes: [],
    selecionados: [],
    busca: { paginacao: new Pagination([], { perPage: 5 }), vendas: [], totais: {} },
    filtrados: { paginacao: new Pagination([], { perPage: 5 }), vendas: [], totais: {} },
    emExibicao: 'busca'
  }, {
    set: function(target, name, value) {
      if(name === 'porPagina') {
        target.porPagina = value
        target[target.emExibicao].paginacao.setOptions({
          perPage: value
        }).paginate()
        return
      }
      if(['busca', 'filtrados'].includes(name)) {
        target[name] = value.serializado ? value : serializarVendas(value, target.id)
        return
      }
      if(name === 'emExibicao') {
        if(typeof value === 'string') {
          target[name] = value
          return
        }
        vendasEmExibicao = target[name]
        target[vendasEmExibicao] = value.serializado ? value : serializarVendas(value, target.id)
        return
      }

      target[name] = value
    },
    get: function(target, name) {
      if(name === 'emExibicao') {
        vendasEmExibicao = target[name]
        return target[vendasEmExibicao]
      }

      return target[name]
    }
  })
}

const checker = new Checker();
const modalFilter = new ModalFilter();
const urls = getUrls();
const dados = new Proxy({
  filtros: {},
  subfiltros: {},
  erp: new VendasProxy('erp'),
  operadoras: new VendasProxy('operadoras'),
}, handler());

checker.addGroup('empresa');
checker.addGroup('status-conciliacao');
modalFilter.addGroup('empresa');

function handler() {
  return {
    set: function(target, name, value) {
      if(name === 'filtros') {
        target[name].data_inicial = value.data_inicial;
        target[name].data_final = value.data_final;
        target[name].grupos_clientes = value.grupos_clientes;
        target[name].status_conciliacao = value.status_conciliacao;
      }
    }
  }
}

function getUrls() {
  const form = document.querySelector('#js-form-pesquisar');
  return {
    erp: {
      buscar: form.dataset.urlErp,
      filtrar: form.dataset.urlFiltrarErp,
      exportar: form.dataset.urlExportarErp,
    },
    operadoras: {
      buscar: form.dataset.urlOperadoras,
      filtrar: form.dataset.urlFiltrarOperadoras,
      exportar: form.dataset.urlExportarOperadoras,
    },
    conciliar: form.dataset.urlConciliarManualmente,
    justificar: form.dataset.urlJustificar,
  }
}

function serializarVendas(resultado, id) {
  const vendas = resultado.vendas.data;
  const paginacao = resultado.vendas;
  paginacao.id = id;
  delete resultado.vendas.data;
  delete resultado.vendas;
  resultado.vendas = vendas;
  resultado.paginacao = criarPaginacao(paginacao, async (page, paginacao, event) => {
    const url = paginacao.options.baseUrl
    const body = url === urls[id].filtrar ? 
      { filtros: dados.filtros, subfiltros: dados.subfiltros[id] } : { ...dados.filtros }
    const params = {
      page,
      por_pagina: paginacao.options.perPage
    }

    iniciarRequisicao(async () => {
      const vendas = await requisitarVendas(url, body, params).catch(err => 
        swal("Ooops...", "Não foi possível realizar a consulta.", "error"));
      if(url === urls[id].filtrar) {
        dados[id].emExibicao = 'filtrados';
      } else {
        dados[id].emExibicao = 'busca';
      }

      dados[id].emExibicao = vendas
    });
  });
  resultado.serializado = true
  return { ...resultado }
}

function criarPaginacao(paginacao, navigateHandler = () => {}) {
  const novaPaginacao = new Pagination([], {
    currentPage: paginacao.current_page,
    lastPage: paginacao.last_page,
    perPage: paginacao.per_page,
    total: paginacao.total,
    paginationContainer: document.querySelector(`#js-paginacao-${paginacao.id}`),
    baseUrl: paginacao.path,
    id: paginacao.id,
  })
    .setNavigateHandler(navigateHandler);

  return novaPaginacao;
}

function atualizarInterface(modalidadeVendas, registros, paginacao = null) {
  const vendasErpInfoDOM = document.querySelector('#js-vendas-erp-info');
  const pendenciasOperadorasInfoDOM = document.querySelector('#js-pendencias-operadoras-info');

  vendasErpInfoDOM.textContent = `(${dados.erp.emExibicao.paginacao.options.total} registros)`;
  pendenciasOperadorasInfoDOM.textContent = `(${dados.operadoras.emExibicao.paginacao.options.total} registros)`;
  renderizarTabela(modalidadeVendas, registros.vendas, registros.totais);
  if(paginacao) {
    paginacao.render();
    document.querySelector(`#js-porpagina-${modalidadeVendas}`).value = paginacao.options.perPage;
  }
}

function limpar() {
  const form = document.querySelector('#js-form-pesquisar');
  const dataInputs = document.querySelectorAll('#js-form-pesquisar input[type=date]');
  
  form.reset();
  [...dataInputs].forEach(input => {
    input.value = "";
  });
}

function limparFiltros(tipo = "*") {
  const seletor = `${tipo === '*' ? 'table' : `#js-tabela-${tipo}`} input:not([type="checkbox"]):not([name=""])`;
  [...document.querySelectorAll(seletor)].forEach(input => {
    input.value = "";
  });

  dados.subFiltros = { erp: {}, operadoras: {} };
}

function confirmarSelecao(event) {
  const botaoDOM = event.target;
  const acao = botaoDOM.dataset.acao;
  const nomeGrupo = botaoDOM.dataset.group;

  if(String(acao).toLowerCase() === 'cancelar') {
    checker.uncheckAll(nomeGrupo);
  }

  checker.setValuesToTextElement(nomeGrupo, 'descricao');
}

function selecionarQuantidadePagina(event) {
  const tipo = event.target.dataset.vendasTipo;
  const quantidade = event.target.value;
  const url = dados[tipo].emExibicao.paginacao.options.baseUrl
  const body = url === urls[tipo].filtrar ? 
    { filtros: dados.filtros, subfiltros: dados.subfiltros[tipo] } : { ...dados.filtros }

  dados[tipo].porPagina = quantidade;

  iniciarRequisicao(async () => {
    const vendas = await requisitarVendas(url,
      body,
      {
        page: 1,
        por_pagina: quantidade
      }
    )

    dados[tipo].emExibicao = vendas
  })
}

function serializarDadosPesquisa() {
  const form = document.querySelector('#js-form-pesquisar');
  const dataInicial = form.querySelector('input[name="data_inicial"]').value;
  const dataFinal = form.querySelector('input[name="data_final"]').value;
  const empresas = checker.getCheckedValues('empresa');
  const statusConciliacao = checker.getCheckedValues('status-conciliacao');

  return {
    data_inicial: dataInicial,
    data_final: dataFinal,
    grupos_clientes: empresas,
    status_conciliacao: statusConciliacao
  };
}

function getFiltros() {
  const filtros = serializarDadosPesquisa();

  dados.filtros = { ...filtros }
  return dados.filtros;
}

function atualizarSubfiltros(modalidadeVendas) {
  const inputs = document.querySelectorAll(`#js-tabela-${modalidadeVendas} input:not([type="checkbox"]):not([name=""])`);
  const subFiltros = [...inputs].reduce((objeto, input) => {
    const chave = input.name;
    const valor = input.value.trim();

    if(valor) {
      objeto[chave] = valor;
    }

    return objeto
  }, {});

  dados.subfiltros[modalidadeVendas] = { ...subFiltros };
}

function getCsrfToken() {
  const csrfToken = document.querySelector('input[name="_token"').value;
  return csrfToken;
}

async function requisitarVendas(url, body = {}, params = {}) {
  const csrfToken = getCsrfToken();

  return api.post(url, {
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Content-type': 'application/json'
    },
    body: JSON.stringify({
      _token: csrfToken,
      ...body
    }),
    params
  });
}

function alternarVisibilidade(elementoDOM) {
  elementoDOM.classList.toggle('hidden');
}

async function iniciarRequisicao(requisicaoCallback = async () => {}) {
  const resultados = document.querySelector('#js-resultados');
  const loader = document.querySelector('#js-loader');
  
  alternarVisibilidade(loader);
  
  if(requisicaoCallback && typeof requisicaoCallback === 'function') {
    await requisicaoCallback();
  }

  atualizarInterface('erp', dados.erp.emExibicao, dados.erp.emExibicao.paginacao);
  atualizarInterface('operadoras', dados.operadoras.emExibicao, dados.operadoras.emExibicao.paginacao);
  
  alternarVisibilidade(loader);

  if(resultados.classList.contains('hidden')) {
    resultados.classList.remove('hidden');
  }
}

async function submeterPesquisa(event) {
  event.preventDefault();

  await iniciarRequisicao(async () => {
    const filtros = getFiltros();

    const [erp, operadoras] = await Promise.all([
      requisitarVendas(
        urls.erp.buscar,
        { ...filtros },
        { por_pagina: dados.erp.porPagina }
      ),
      requisitarVendas(
        urls.operadoras.buscar, 
        { ...filtros }, 
        { por_pagina: dados.operadoras.porPagina }
      ),
    ]).catch(err => {
      swal("Ooops...", " Um erro inesperado ocorreu.", "error")
    });

    dados.erp.emExibicao = 'busca'
    dados.operadoras.emExibicao = 'busca'
    dados.erp.busca = erp;
    dados.operadoras.busca = operadoras;
  });
  
  atualizarBoxes({ 
      erp: { ...dados.erp.busca.totais },
      operadoras: { ...dados.operadoras.busca.totais }
  });
  limparFiltros();
  window.scrollTo(0, document.querySelector('#js-resultados').offsetTop);
}

async function pesquisarPorBox(event) {
  const boxDOM = event.target;
  const statusConciliacao = boxDOM.dataset.status;
  const statusConciliacaoCheckbox = document.querySelector(`form .check-group input[data-status="${statusConciliacao}"]`);

  if(statusConciliacao === '*') {
    checker.checkAll('status-conciliacao')
  } else {
    checker.uncheckAll('status-conciliacao');
    statusConciliacaoCheckbox.checked = true;
  }
  
  await iniciarRequisicao(async () => {
    const vendas = await requisitarVendas(urls.erp.buscar, {
      ...getFiltros(),
    },
    {
      page: 1,
      por_pagina: dados.erp.porPagina
    });

    dados.erp.emExibicao = 'busca'
    dados.erp.busca = vendas;
  });

  atualizarInterface('erp', dados.erp.emExibicao, dados.erp.emExibicao.paginacao);
  limparFiltros('erp');
  window.scrollTo(0, document.querySelector('#js-resultados').offsetTop);
}

function atualizarBoxes(totais) {
  const totalErp = document.querySelector('p[data-total="EPR_TOTAL_BRUTO"]');
  const totalConciliada = document.querySelector('p[data-total="TOTAL_CONCILIADA"]');
  const totalDivergente = document.querySelector('p[data-total="TOTAL_DIVERGENTE"]');
  const totalManual = document.querySelector('p[data-total="TOTAL_MANUAL"]');
  const totalJustificada = document.querySelector('p[data-total="TOTAL_JUSTIFICADA"]');
  const totalNaoConciliada = document.querySelector('p[data-total="TOTAL_NAO_CONCILIADA"]');
  const totalOperadoras = document.querySelector('p[data-total="OPERADORAS_TOTAL_BRUTO"]');

  totalErp.textContent = formatadorMoeda.format(totais.erp.TOTAL_BRUTO);
  totalConciliada.textContent = formatadorMoeda.format(totais.erp.TOTAL_CONCILIADA);
  totalDivergente.textContent = formatadorMoeda.format(totais.erp.TOTAL_DIVERGENTE);
  totalManual.textContent = formatadorMoeda.format(totais.erp.TOTAL_MANUAL);
  totalJustificada.textContent = formatadorMoeda.format(totais.erp.TOTAL_JUSTIFICADA);
  totalNaoConciliada.textContent = formatadorMoeda.format(totais.erp.TOTAL_NAO_CONCILIADA);
  totalOperadoras.textContent = formatadorMoeda.format(totais.operadoras.TOTAL_BRUTO);
}

function renderizarTabela(tipo, vendas, totais) {
  const table = document.querySelector(`table#js-tabela-${tipo}`);
  const tbody = table.querySelector(`tbody`)
  const totaisDOM = table.querySelectorAll('tfoot td[data-chave]');
  const linhaTabelaTemplate = table.querySelector('tbody tr').cloneNode(true);
  let marcacoes = dados[tipo].marcacoes;

  tbody.innerHTML = '';
  tbody.appendChild(linhaTabelaTemplate);

  vendas.forEach(venda => {
    const tr = linhaTabelaTemplate.cloneNode(true);
    const id = venda[tr.dataset.id];
    tr.dataset.id = id;
    if(marcacoes.includes(id)) {
      tr.classList.add('marcada');
    }
    
    tr.addEventListener('click', (event) => {
      if(!marcacoes.includes(id)) {
        marcacoes.push(id); 
        tr.classList.add('marcada');
      } else {
        marcacoes = marcacoes.filter(idMarcacao => id !== idMarcacao);
        tr.classList.remove('marcada');
      }

      dados[tipo].marcacoes = marcacoes;
    })

    const tooltipsDOM = tr.querySelectorAll('.tooltip-hint');
    const imagensDOM = tr.querySelectorAll('td img');
    const colunasDOM = tr.querySelectorAll('td[data-campo]');
    const inputsDOM = tr.querySelectorAll('input[type="checkbox"][data-campo]');

    [...tooltipsDOM].forEach(tooltipDOM => {
      tooltipDOM.dataset.title = venda[tooltipDOM.dataset.title] || 'Sem identificação';
    });
    
    [...imagensDOM].forEach(imagemDOM => {
      const imagemUrl = imagemDOM.dataset.image;
      const textoImagem = imagemDOM.dataset.text;
      const td = imagemDOM.closest('td');
      const texto = imagemDOM.dataset.defaultText || '';

      if(venda[imagemUrl]) {
        imagemDOM.src = venda[imagemUrl];
        imagemDOM.alt = venda[textoImagem] || texto;
        return;
      }

      if(imagemDOM.dataset.defaultImage) {
        imagemDOM.src = imagemDOM.dataset.defaultImage;
        imagemDOM.alt = venda[textoImagem] || imagemDOM.dataset.defaultText || '';
        return;
      } 

      td.innerHTML = '';
      td.textContent = texto;
    });

    [...colunasDOM].forEach(colunaDOM => {
      const campo = colunaDOM.dataset.campo;
      const formato = colunaDOM.dataset.format;
      let valor = venda[campo] || '';
      
      if(formato === 'date' && valor) {
        valor = new Date(`${valor} 00:00:00`).toLocaleDateString();
      }
      if(formato === 'currency') {
        valor = formatadorMoeda.format(valor);
      }
      if(formato === 'decimal' && valor) {
        valor = formatadorDecimal.format(valor);
      }
      colunaDOM.textContent = valor;
    });

    [...inputsDOM].forEach(inputDOM => {
      const campo = inputDOM.dataset.campo;
      const idVenda = venda[campo] || '';
      inputDOM.value = idVenda;
      inputDOM.checked = dados[tipo].selecionados.includes(idVenda);
      inputDOM.addEventListener('change', event => {
        const { target } = event;
        if(target.checked) {
          dados[tipo].selecionados.push(idVenda);
        } else {
          dados[tipo].selecionados = dados[tipo].selecionados.filter(value => value != idVenda)
        }
      });
    });

    tr.classList.remove('hidden');
    tbody.appendChild(tr);
  });

  [...totaisDOM].forEach(totalDOM => {
    const chave = totalDOM.dataset.chave;
    totalDOM.textContent = formatadorMoeda.format(totais[chave]);
  });
}

function confirmarConciliacao() {
  const idErp = dados.erp.selecionados;
  const idOperadoras = dados.operadoras.selecionados;

  if(idErp.length != 1 || idOperadoras.length != 1) {
    swal("Ooops...", "Selecione apenas uma venda ERP e uma operadora para realizar a conciliação.", "error");
    return;
  }

  swal("Tem certeza que deseja realizar a conciliação?", {
    buttons: {
      confirm: "Sim",
      cancel: "Não",
    }
  }).then(value => {
    if(!value) {
      return;
    }

    conciliar();
  });
}

function conciliar() {
  const loader = document.querySelector('#js-loader');
  const idErp = dados.erp.selecionados;
  const idOperadoras = dados.operadoras.selecionados;

  alternarVisibilidade(loader);

  api.post(urls.conciliar, {
    headers: {
      'X-CSRF-TOKEN': getCsrfToken(),
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      _token: getCsrfToken(),
      id_erp: idErp,
      id_operadora: idOperadoras,
    })
  }).then(res => {
    alternarVisibilidade(loader);
    
    if(res.status != 'sucesso' && res.mensagem) {
      swal("Ooops...", res.mensagem, "error");
      return;
    }

    const erpTotais = dados.erp.busca.totais;
    const operadoraTotais = dados.operadoras.busca.totais;
    const erpBuscaIndex = dados.erp.busca.vendas.findIndex(venda => venda.ID_ERP == res.erp.ID);
    const erpFiltradosIndex = dados.erp.filtrados.vendas.findIndex(venda => venda.ID_ERP == res.erp.ID);
    const operadoraBuscaIndex = dados.operadoras.busca.vendas.findIndex(venda => venda.ID == res.operadora.ID);
    const operadoraFiltradoIndex = dados.operadoras.filtrados.vendas.findIndex(venda => venda.ID == res.operadora.ID);

    if(erpBuscaIndex != -1) {
      dados.erp.busca.vendas[erpBuscaIndex].STATUS_CONCILIACAO_IMAGEM = res.STATUS_MANUAL_IMAGEM_URL;
      dados.erp.busca.vendas[erpBuscaIndex].STATUS_CONCILIACAO = res.STATUS_MANUAL;
    }
    if(erpFiltradosIndex != -1) {
      dados.erp.filtrados.vendas[erpFiltradosIndex].STATUS_CONCILIACAO_IMAGEM = res.STATUS_MANUAL_IMAGEM_URL;
      dados.erp.filtrados.vendas[erpFiltradosIndex].STATUS_CONCILIACAO = res.STATUS_MANUAL;
    }
    
    if(operadoraBuscaIndex != -1) {
      dados.operadoras.busca.vendas[operadoraBuscaIndex].STATUS_CONCILIACAO_IMAGEM = res.STATUS_MANUAL_IMAGEM_URL;
      dados.operadoras.busca.vendas[operadoraBuscaIndex].STATUS_CONCILIACAO = res.STATUS_MANUAL;
    }
    if(operadoraFiltradoIndex != -1) {
      dados.operadoras.filtrados.vendas[operadoraFiltradoIndex].STATUS_CONCILIACAO_IMAGEM = res.STATUS_MANUAL_IMAGEM_URL;
      dados.operadoras.filtrados.vendas[operadoraFiltradoIndex].STATUS_CONCILIACAO = res.STATUS_MANUAL;
    }

    dados.erp.busca.totais = {
      ...erpTotais,
      TOTAL_MANUAL: (Number(erpTotais.TOTAL_MANUAL) || 0) + (Number(res.erp.TOTAL_BRUTO) || 0),
      TOTAL_NAO_CONCILIADA: (Number(erpTotais.TOTAL_NAO_CONCILIADA) || 0) - (Number(res.erp.TOTAL_BRUTO) || 0),
    }

    atualizarBoxes({ 
      erp: { ...dados.erp.busca.totais },
      operadoras: {
        TOTAL_BRUTO: (Number(operadoraTotais.TOTAL_BRUTO) || 0) - (Number(res.operadora.TOTAL_BRUTO) || 0)
      }
    });

    dados.erp.selecionados = [];
    dados.operadoras.selecionados = [];

    atualizarInterface('erp', dados.erp.emExibicao, dados.erp.emExibicao.paginacao);
    atualizarInterface('operadoras', dados.operadoras.emExibicao, dados.operadoras.emExibicao.paginacao);

    if(operadoraBuscaIndex != -1) {
      dados.operadoras.busca.vendas.splice(operadoraBuscaIndex, 1)
    }
    if(operadoraFiltradoIndex != -1) {
      dados.operadoras.filtrados.vendas.splice(operadoraFiltradoIndex, 1)
    }

    if(res.status === 'sucesso' && res.mensagem) {
      swal("Conciliação realizada!", "As vendas foram conciliadas com êxito.", "success");
      return;
    }
  });
}

function confirmarDesconciliacao() {
  const idErp = dados.erp.selecionados;
  
  if(idErp.length != 1) {
    swal("Ooops...", "Para realizar a desconciliação selecione apenas uma venda ERP.", "error");
    return;
  }

  swal("Tem certeza que deseja desconciliar a venda?", {
    buttons: {
      confirm: "Sim",
      cancel: "Não",
    }
  }).then(value => {
    if(!value) {
      return;
    }

    desconciliar();
  });
}

function desconciliar() {
  swal('Uhuuu!!!', 'Venda desconciliada', 'success');
}

function abrirModalJustificativa(event) {
  const botaoAbrirModal = document.querySelector(event.target.dataset.target);

  if(dados.erp.selecionados.length === 0) {
    swal("Ooops...", "Selecione ao menos uma venda ERP.", "error");
    return;
  }
  if(dados.operadoras.selecionados.length > 0) {
    swal("Ooops...", "Apenas as vendas ERP podem ser justificadas.", "error");
    return;
  }

  botaoAbrirModal.click();
}

function selecionarJustificativa(event) {
  const selecionada = document.querySelector('#js-justificativas-lista .list-group-item.active');
  const justificativaInput = document.querySelector('#js-justificar-modal input[name="justificativa"]');

  if(event.target.classList.contains('active')) {
    event.target.classList.remove('active');
    justificativaInput.value = '';
    return;
  }

  if(selecionada) {
    selecionada.classList.remove('active');
  }

  justificativaInput.value = event.target.textContent;
  event.target.classList.add('active');
}

function justificar() {
  const justificativaDOM = document.querySelector('#js-justificar-modal input[name="justificativa"]');
  const justificativa = justificativaDOM.value;
  const idErp = dados.erp.selecionados;
  const loader = document.querySelector('#js-loader');
 
  if(justificativa.trim() === '') {
    swal('Ooops...', 'A justificativa deve ser informada.', 'error')
    return;
  }

  alternarVisibilidade(loader);

  api.post(urls.justificar, {
    headers: {
      'X-CSRF-TOKEN': getCsrfToken(),
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      _token: getCsrfToken(),
      id_erp: idErp,
      justificativa
    })
  }).then(res => {
    if(res.status !== 'sucesso' && res.mensagem) {
      swal('Ooops...', res.mensagem, 'error');
      return;
    }

    alternarVisibilidade(loader);

    const erpTotais = dados.erp.busca.totais;

    dados.erp.busca.totais = {
      ...erpTotais,
      TOTAL_JUSTIFICADA: (Number(erpTotais.TOTAL_JUSTIFICADA) || 0) + (Number(res.erp.TOTAL_BRUTO) || 0),
      TOTAL_NAO_CONCILIADA: (Number(erpTotais.TOTAL_NAO_CONCILIADA) || 0) - (Number(res.erp.TOTAL_BRUTO) || 0),
    }
    
    res.erp.ID_ERP.forEach(erp => {
      const erpBuscaIndex = dados.erp.busca.vendas.findIndex(venda => venda.ID_ERP == erp.ID);
      const erpFiltradosIndex = dados.erp.filtrados.vendas.findIndex(venda => venda.ID_ERP == erp.ID);

      if(erpBuscaIndex !== -1) {
        dados.erp.busca.vendas[erpBuscaIndex] = {
          ...dados.erp.busca.vendas[erpBuscaIndex],
          JUSTIFICATIVA: res.JUSTIFICATIVA,
          STATUS_CONCILIACAO: res.STATUS_JUSTIFICADO,
          STATUS_CONCILIACAO_IMAGEM: res.STATUS_JUSTIFICADO_IMAGEM_URL
        }
      }
      if(erpFiltradosIndex !== 1) {
        dados.erp.filtrados.vendas[erpFiltradosIndex] = {
          ...dados.erp.busca.vendas[erpFiltradosIndex],
          JUSTIFICATIVA: res.JUSTIFICATIVA,
          STATUS_CONCILIACAO: res.STATUS_JUSTIFICADO,
          STATUS_CONCILIACAO_IMAGEM: res.STATUS_JUSTIFICADO_IMAGEM_URL
        }
      }
    });

    dados.erp.selecionados = [];
    atualizarBoxes({ 
      erp: { ...dados.erp.busca.totais },
      operadoras: { ...dados.operadoras.busca.totais }
    });

    atualizarInterface('erp', dados.erp.emExibicao, dados.erp.emExibicao.paginacao);

    if(res.status === 'sucesso') {
      swal('Justificativa realizada.', 'As vendas foram justificadas com êxito.', 'success');
    }

    justificativaDOM.value = "";
    const justificativaSelecionada = document.querySelector('#js-justificativas-lista .list-group-item.active');
    if(justificativaSelecionada) {
      justificativaSelecionada.classList.remove('active');
    }
  });
}

function abrirUrlExportacao(baseUrl, target =  '') {
  swal('Aguarde um momento...', 'A sua planilha está sendo gerada.', 'warning');
  const a = document.createElement('a');
  a.href = api.urlBuilder(baseUrl, dados.filtros);
  a.target = target;
  a.click();
}

function exportarErp() {
  abrirUrlExportacao(urls.erp.exportar, '_blank');
}

function exportarOperadoras() {
  abrirUrlExportacao(urls.operadoras.exportar, '_blank');
}

window.addEventListener('load', () => {
  document.querySelector('#pagina-conciliacao').classList.remove('hidden');
  window.scrollTo(0, 0);
});

document.querySelector('#js-form-pesquisar').addEventListener('submit', submeterPesquisa);

[...document.querySelectorAll('#js-resultados .boxes .card[data-status]')].forEach(boxDOM => {
  boxDOM.addEventListener('click', pesquisarPorBox);
});

document.querySelector('#js-resultados .boxes .card[data-navigate]')
  .addEventListener('click', event => {
    const seletor = event.target.dataset.navigate;
    const elementoDOM = document.querySelector(seletor);
    
    window.scrollTo(0, elementoDOM.offsetTop);
  });

[...document.querySelectorAll('table input:not([type="checkbox"]):not([name=""])')].forEach(input => {
  input.addEventListener('keyup', (event) => {
    const { target, key } = event;
    const modalidadeVendas = target.closest('table').dataset.modalidade;
    
    atualizarSubfiltros(modalidadeVendas);
    
    if(key === 'Enter') {
      if(Object.keys(dados.subfiltros[modalidadeVendas]).length === 0) {
        dados[modalidadeVendas].emExibicao = 'busca'
        atualizarInterface(modalidadeVendas, dados[modalidadeVendas].emExibicao, dados[modalidadeVendas].emExibicao.paginacao)
        return
      }
      
      iniciarRequisicao(async () => {
        const vendas = await requisitarVendas(urls[modalidadeVendas].filtrar, {
          filtros: dados.filtros,
          subfiltros: dados.subfiltros[modalidadeVendas]
        },
        {
          por_pagina: dados[modalidadeVendas].filtrados.paginacao.options.perPage,
          page: 1
        });

        dados[modalidadeVendas].emExibicao = 'filtrados'
        dados[modalidadeVendas].emExibicao = vendas
      })
    }
  })
})

document.querySelector('#js-reset-form')
  .addEventListener('click', limpar);

[...document.querySelectorAll('select[name="por_pagina"]')].forEach(select => {
  select.addEventListener('change', event => {
  selecionarQuantidadePagina(event)});
});

[...document.querySelectorAll('button[data-acao]')].forEach(botaoDOM => {
  botaoDOM.addEventListener('click', confirmarSelecao);
});

document.querySelector('#js-conciliar')
  .addEventListener('click', confirmarConciliacao)

  document.querySelector('#js-desconciliar')
  .addEventListener('click', confirmarDesconciliacao)

document.querySelector('form#js-justificar-modal')
  .addEventListener('click', event => event.preventDefault());

document.querySelector('button[data-target="#js-abrir-justificar-modal"]')
  .addEventListener('click', abrirModalJustificativa);

[...document.querySelectorAll('#js-justificativas-lista .list-group-item')].forEach(justificativaDOM => {
  justificativaDOM.addEventListener('click', selecionarJustificativa);
});

document.querySelector('#js-justificar')
  .addEventListener('click', justificar);

document.querySelector('#js-exportar-erp')
  .addEventListener('click', exportarErp);

document.querySelector('#js-exportar-operadoras')
  .addEventListener('click', exportarOperadoras);