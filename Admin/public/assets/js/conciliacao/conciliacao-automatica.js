const formatadorMoeda = new Intl.NumberFormat('pt-br', {
  style: 'currency',
  currency: 'BRL'
});
const formatadorDecimal = new Intl.NumberFormat('pt-br', {
  style: 'decimal',
  minimumFractionDigits: 2,
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
  subfiltros: {
    erp: {},
    operadoras: {},
  },
  statusAtivos: [],
  statusFiltros: [],
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

      target[name] = value
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
      justificar: form.dataset.urlJustificarOperadora,
    },
    conciliar: form.dataset.urlConciliarManualmente,
    desconciliar: form.dataset.urlDesconciliarManualmente,
    justificar: form.dataset.urlJustificar,
    desjustificar: form.dataset.urlDesjustificar,
    retornoErp: form.dataset.urlRetornoErp,
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
      { 
        filtros: {
          ...dados.filtros, 
          status_conciliacao: dados.statusFiltros
        },
        subfiltros: dados.subfiltros[id]
      } : 
      { 
        ...dados.filtros, 
        status_conciliacao: dados.statusFiltros
      }
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

function naoConciliadaEstaSelecionada() {
  const status = document.querySelector('#js-box-nao-conciliada').dataset.status;
  const statusNaoConciliada = document.querySelector(`input[data-status="${status}"]`).value;

  return dados.statusFiltros.includes(statusNaoConciliada);
}

function atualizarInterface(modalidadeVendas, registros, paginacao = null) {
  const vendasErpInfoDOM = document.querySelector('#js-vendas-erp-info');
  const pendenciasOperadorasInfoDOM = document.querySelector('#js-pendencias-operadoras-info');
  let totaisOperadoras = { ...dados.operadoras.busca.totais };

  registros = { ...registros };
  vendasErpInfoDOM.textContent = `(${dados.erp.emExibicao.paginacao.options.total} registros)`;
  pendenciasOperadorasInfoDOM.textContent = 
    `(${naoConciliadaEstaSelecionada() ? dados.operadoras.emExibicao.paginacao.options.total : 0} registros)`;
  
  if(!naoConciliadaEstaSelecionada()) {
    totaisOperadoras = {
      TOTAL_BRUTO: 0,
      TOTAL_LIQUIDO: 0,
      TOTAL_TAXA: 0,
    }
  }
  if(modalidadeVendas === 'operadoras' && !naoConciliadaEstaSelecionada()) {
    registros.vendas = [];
    registros.totais = totaisOperadoras;
    paginacao = new Pagination([],  {
      paginationContainer: document.querySelector('#js-paginacao-operadoras'),
      total: 0,
      currentPage: 1,
      lastPage: 1,
      perPage: paginacao ? paginacao.options.perPage : 5,
    });
  }
  
  renderizarTabela(modalidadeVendas, registros.vendas, registros.totais);
  atualizarBoxes({ 
    erp: { ...dados.erp.busca.totais },
    operadoras: totaisOperadoras
  });

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

  dados.statusAtivos = checker.getCheckedValues('status-conciliacao', 'status');
  dados.statusFiltros = checker.getValuesBy('status-conciliacao', 'status', dados.statusAtivos);

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
  
  limparFiltros();
  window.scrollTo(0, document.querySelector('#js-resultados').offsetTop);
}

async function pesquisarPorBox(event) {
  const boxDOM = event.target;
  const statusConciliacao = boxDOM.dataset.status;
  const statusConciliacaoCheckbox = document.querySelector(`form .check-group input[data-status="${statusConciliacao}"]`);
  const filtros = { ...getFiltros() };

  if(statusConciliacao === '*') {
    const statusConciliacao = checker.getValuesBy('status-conciliacao', 'status', dados.statusAtivos);
    filtros.status_conciliacao = statusConciliacao;
    dados.statusFiltros = statusConciliacao;
  } else {
    filtros.status_conciliacao = [statusConciliacaoCheckbox.value];
    dados.statusFiltros = [statusConciliacaoCheckbox.value];
  }
  
  await iniciarRequisicao(async () => {
    const vendas = await requisitarVendas(urls.erp.buscar, {
      ...filtros,
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

function calcularTotalErpBrutoBox() {
  const statusFiltros = [...dados.statusFiltros];

  const total = statusFiltros.reduce((total, statusId) => {
    const status = document.querySelector(`input[data-status][value="${statusId}"]`).dataset.status;
    const statusDOM = document.querySelector(`.boxes .card[data-status="${status}"] p[data-valor]`);
    return total + (Number(statusDOM.dataset.valor) || 0);
  }, 0);

  return total;
}

function retornaValorBox(total, status) {
  const statusId = checker.getValuesBy('status-conciliacao', 'status', [status])[0];
  if(dados.statusFiltros.includes(statusId)) {
    return Number(total) || 0;
  }

  return 0;
}

function atualizarBoxes(totais) {
  const totalErp = document.querySelector('p[data-total="EPR_TOTAL_BRUTO"]');
  const totalConciliada = document.querySelector('p[data-total="TOTAL_CONCILIADA"]');
  const totalDivergente = document.querySelector('p[data-total="TOTAL_DIVERGENTE"]');
  const totalManual = document.querySelector('p[data-total="TOTAL_MANUAL"]');
  const totalJustificada = document.querySelector('p[data-total="TOTAL_JUSTIFICADA"]');
  const totalNaoConciliada = document.querySelector('p[data-total="TOTAL_NAO_CONCILIADA"]');
  const totalOperadoras = document.querySelector('p[data-total="OPERADORAS_TOTAL_BRUTO"]');

  let status = totalConciliada.closest('*[data-status]').dataset.status;
  totalConciliada.dataset.valor = retornaValorBox(totais.erp.TOTAL_CONCILIADA, status);
  totalConciliada.textContent = formatadorMoeda.format(totalConciliada.dataset.valor);

  status = totalDivergente.closest('*[data-status]').dataset.status;
  totalDivergente.dataset.valor = retornaValorBox(totais.erp.TOTAL_DIVERGENTE, status);
  totalDivergente.textContent = formatadorMoeda.format(totalDivergente.dataset.valor);

  status = totalManual.closest('*[data-status]').dataset.status;
  totalManual.dataset.valor = retornaValorBox(totais.erp.TOTAL_MANUAL, status);
  totalManual.textContent = formatadorMoeda.format(totalManual.dataset.valor);
  
  status = totalJustificada.closest('*[data-status]').dataset.status;
  totalJustificada.dataset.valor =  retornaValorBox(totais.erp.TOTAL_JUSTIFICADA, status);
  totalJustificada.textContent = formatadorMoeda.format(totalJustificada.dataset.valor);
  
  status = totalNaoConciliada.closest('*[data-status]').dataset.status;
  totalNaoConciliada.dataset.valor = retornaValorBox(totais.erp.TOTAL_NAO_CONCILIADA, status);
  totalNaoConciliada.textContent = formatadorMoeda.format(totalNaoConciliada.dataset.valor);
  
  totalErp.textContent = formatadorMoeda.format(calcularTotalErpBrutoBox());

  totalOperadoras.dataset.valor = totais.operadoras.TOTAL_BRUTO;
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
      if(event.target.tagName.toLowerCase() === 'input') {
        return;
      }

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
      tooltipDOM.dataset.title = venda[tooltipDOM.dataset.title] || tooltipDOM.dataset.defaultTitle || 'Sem identificação';
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
      if(formato === 'percent' && valor) {
        valor = `${formatadorDecimal.format(valor)}%`;
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

    dados.operadoras.busca.totais = {
      ...operadoraTotais,
      TOTAL_BRUTO: (Number(operadoraTotais.TOTAL_BRUTO) || 0) - (Number(res.operadora.TOTAL_BRUTO) || 0)
    }

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
  const loader = document.querySelector('#js-loader');
  const idErp = dados.erp.selecionados;

  alternarVisibilidade(loader);

  api.post(urls.desconciliar, {
    headers: {
      'X-CSRF-TOKEN': getCsrfToken(),
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      _token: getCsrfToken(),
      id_erp: idErp,
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
      dados.erp.busca.vendas[erpBuscaIndex].STATUS_CONCILIACAO_IMAGEM = res.STATUS_CONCILIACAO_IMAGEM_URL;
      dados.erp.busca.vendas[erpBuscaIndex].STATUS_CONCILIACAO = res.STATUS_CONCILIACAO;
    }
    if(erpFiltradosIndex != -1) {
      dados.erp.filtrados.vendas[erpFiltradosIndex].STATUS_CONCILIACAO_IMAGEM = res.STATUS_CONCILIACAO_IMAGEM_URL;
      dados.erp.filtrados.vendas[erpFiltradosIndex].STATUS_CONCILIACAO = res.STATUS_CONCILIACAO;
    }
    
    if(operadoraBuscaIndex != -1) {
      dados.operadoras.busca.vendas[operadoraBuscaIndex].STATUS_CONCILIACAO_IMAGEM = res.STATUS_CONCILIACAO_IMAGEM_URL;
      dados.operadoras.busca.vendas[operadoraBuscaIndex].STATUS_CONCILIACAO = res.STATUS_CONCILIACAO;
    }
    if(operadoraFiltradoIndex != -1) {
      dados.operadoras.filtrados.vendas[operadoraFiltradoIndex].STATUS_CONCILIACAO_IMAGEM = res.STATUS_CONCILIACAO_IMAGEM_URL;
      dados.operadoras.filtrados.vendas[operadoraFiltradoIndex].STATUS_CONCILIACAO = res.STATUS_CONCILIACAO;
    }

    dados.erp.busca.totais = {
      ...erpTotais,
      TOTAL_MANUAL: (Number(erpTotais.TOTAL_MANUAL) || 0) - (Number(res.erp.TOTAL_BRUTO) || 0),
      TOTAL_NAO_CONCILIADA: (Number(erpTotais.TOTAL_NAO_CONCILIADA) || 0) + (Number(res.erp.TOTAL_BRUTO) || 0),
    }
    
    dados.operadoras.busca.totais = {
      ...operadoraTotais,
      TOTAL_BRUTO: (Number(operadoraTotais.TOTAL_BRUTO) || 0) + (Number(res.operadora.TOTAL_BRUTO) || 0)
    }

    dados.erp.selecionados = [];
    dados.operadoras.selecionados = [];

    atualizarInterface('erp', dados.erp.emExibicao, dados.erp.emExibicao.paginacao);
    atualizarInterface('operadoras', dados.operadoras.emExibicao, dados.operadoras.emExibicao.paginacao);

    if(res.status === 'sucesso' && res.mensagem) {
      swal("Desconciliação realizada!", res.mensagem, "success");
      return;
    }
  });
}

function abrirModalJustificativa(event) {
  const type = event.target.dataset.type;
  const botaoAbrirModal = document.querySelector(event.target.dataset.target);
  if(dados.erp.selecionados.length < 1 && type === 'erp') {
    swal("Ooops...", "Selecione ao menos uma venda ERP.", "error");
    return;
  }
  if(dados.operadoras.selecionados.length < 1 && type !== 'erp') {
    swal("Ooops...", "Selecione ao menos uma venda operadora.", "error");
    return;
  }

  const botaoJustificar = document.querySelector('#js-justificar');
  const botaoJustificarClone = botaoJustificar.cloneNode(true);
  botaoJustificar.parentNode.replaceChild(botaoJustificarClone, botaoJustificar);

  if(type === 'erp') {
    botaoJustificarClone.addEventListener('click', justificarErp);
  } else {
    botaoJustificarClone.addEventListener('click', justificarOperadora);
  }

  botaoAbrirModal.click();
}

function justificarErp() {
  const justificativaDOM = document.querySelector('#js-justificar-modal select[name="justificativa"]');
  const justificativa = justificativaDOM.value;
  const idErp = dados.erp.selecionados;
  const loader = document.querySelector('#js-loader');
 
  if(!justificativa) {
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
          STATUS_CONCILIACAO_IMAGEM: res.STATUS_JUSTIFICADO_IMAGEM_URL,
          DATA_CONCILIACAO: res.DATA_CONCILIACAO,
          HORA_CONCILIACAO: res.HORA_CONCILIACAO,
        }
      }
      if(erpFiltradosIndex !== 1) {
        dados.erp.filtrados.vendas[erpFiltradosIndex] = {
          ...dados.erp.busca.vendas[erpFiltradosIndex],
          JUSTIFICATIVA: res.JUSTIFICATIVA,
          STATUS_CONCILIACAO: res.STATUS_JUSTIFICADO,
          STATUS_CONCILIACAO_IMAGEM: res.STATUS_JUSTIFICADO_IMAGEM_URL,
          DATA_CONCILIACAO: res.DATA_CONCILIACAO,
          HORA_CONCILIACAO: res.HORA_CONCILIACAO,
        }
      }
    });

    dados.erp.selecionados = [];

    atualizarInterface('erp', dados.erp.emExibicao, dados.erp.emExibicao.paginacao);

    if(res.status === 'sucesso') {
      swal('Justificativa realizada.', 'As vendas foram justificadas com êxito.', 'success');
    }

    justificativaDOM.value = "";
  });
}

function justificarOperadora() {
  const justificativaDOM = document.querySelector('#js-justificar-modal select[name="justificativa"]');
  const justificativa = justificativaDOM.value;
  const idOperadoras = dados.operadoras.selecionados;
  const loader = document.querySelector('#js-loader');

  if(!justificativa) {
    swal('Ooops...', 'A justificativa deve ser informada.', 'error')
    return;
  }

  alternarVisibilidade(loader);

  api.post(urls.operadoras.justificar, {
    headers: {
      'X-CSRF-TOKEN': getCsrfToken(),
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      _token: getCsrfToken(),
      id: idOperadoras,
      justificativa
    })
  }).then(res => {
    alternarVisibilidade(loader);
    if(res.status !== 'sucesso' && res.mensagem) {
      swal('Ooops...', res.mensagem, 'error');
      return;
    }

    const operadoraTotais = dados.operadoras.busca.totais;

    dados.operadoras.busca.totais = {
      ...operadoraTotais,
      TOTAL_BRUTO: (Number(operadoraTotais.TOTAL_BRUTO) || 0) - (Number(res.totais.TOTAL_BRUTO) || 0),
      TOTAL_LIQUIDO: (Number(operadoraTotais.TOTAL_LIQUIDO) || 0) - (Number(res.totais.TOTAL_LIQUIDO) || 0),
      TOTAL_TAXA: (Number(operadoraTotais.TOTAL_TAXA) || 0) - (Number(res.totais.TOTAL_TAXA) || 0),
    }

    atualizarInterface('operadoras', dados.operadoras.emExibicao, dados.operadoras.emExibicao.paginacao);
    res.vendas.forEach(venda => {
      const tr = document.querySelector(`#js-tabela-operadoras tr[data-id="${venda.ID}"]`);
      if(tr) {
        if(!tr.classList.contains('hidden')) {
          tr.classList.add('hidden');
        }
      }
    })

    dados.operadoras.selecionados = [];

    if(res.status === 'sucesso') {
      swal('Justificativa realizada.', 'As vendas foram justificadas com êxito.', 'success');
    }

    justificativaDOM.value = "";
  });
}

function confirmarDesjustificar () {
  const idErp = dados.erp.selecionados;
  
  if(idErp.length < 1) {
    swal("Ooops...", "Para desfazer a justificativa selecione ao menos uma venda ERP.", "error");
    return;
  }

  swal("Tem certeza que deseja desfazer a justificativa?", {
    buttons: {
      confirm: "Sim",
      cancel: "Não",
    }
  }).then(value => {
    if(!value) {
      return;
    }

    desjustificar();
  });
}

function desjustificar() {
  const idErp = dados.erp.selecionados;
  const loader = document.querySelector('#js-loader');

  alternarVisibilidade(loader);

  api.post(urls.desjustificar, {
    headers: {
      'X-CSRF-TOKEN': getCsrfToken(),
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      _token: getCsrfToken(),
      id_erp: idErp,
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
      TOTAL_JUSTIFICADA: (Number(erpTotais.TOTAL_JUSTIFICADA) || 0) - (Number(res.erp.TOTAL_BRUTO) || 0),
      TOTAL_NAO_CONCILIADA: (Number(erpTotais.TOTAL_NAO_CONCILIADA) || 0) + (Number(res.erp.TOTAL_BRUTO) || 0),
    }
    
    res.erp.ID_ERP.forEach(erp => {
      const erpBuscaIndex = dados.erp.busca.vendas.findIndex(venda => venda.ID_ERP == erp.ID);
      const erpFiltradosIndex = dados.erp.filtrados.vendas.findIndex(venda => venda.ID_ERP == erp.ID);

      if(erpBuscaIndex !== -1) {
        dados.erp.busca.vendas[erpBuscaIndex] = {
          ...dados.erp.busca.vendas[erpBuscaIndex],
          JUSTIFICATIVA: res.JUSTIFICATIVA,
          STATUS_CONCILIACAO: res.STATUS_CONCILIACAO,
          STATUS_CONCILIACAO_IMAGEM: res.STATUS_CONCILIACAO_IMAGEM_URL,
          DATA_CONCILIACAO: res.DATA_CONCILIACAO,
          HORA_CONCILIACAO: res.HORA_CONCILIACAO,
        }
      }
      if(erpFiltradosIndex !== 1) {
        dados.erp.filtrados.vendas[erpFiltradosIndex] = {
          ...dados.erp.busca.vendas[erpFiltradosIndex],
          JUSTIFICATIVA: res.JUSTIFICATIVA,
          STATUS_CONCILIACAO: res.STATUS_CONCILIACAO,
          STATUS_CONCILIACAO_IMAGEM: res.STATUS_CONCILIACAO_IMAGEM_URL,
          DATA_CONCILIACAO: res.DATA_CONCILIACAO,
          HORA_CONCILIACAO: res.HORA_CONCILIACAO,
        }
      }
    });

    dados.erp.selecionados = [];

    atualizarInterface('erp', dados.erp.emExibicao, dados.erp.emExibicao.paginacao);

    if(res.status === 'sucesso') {
      swal('Justificativa desfeita.', 'A justificativa foi desfeita com êxito.', 'success');
    }
  });
}

function abrirUrlExportacao(baseUrl, target =  '') {
  swal('Aguarde um momento...', 'A sua planilha está sendo gerada.', 'warning');
  const a = document.createElement('a');
  const subfiltros = baseUrl === urls.erp.exportar ? { ...dados.subfiltros.erp } : { ...dados.subfiltros.operadoras };

  if(baseUrl === urls.operadoras.exportar && !naoConciliadaEstaSelecionada()) {
    subfiltros.id_erp = [null];
  }

  a.href = api.urlBuilder(baseUrl, { 
    ...{ ...dados.filtros, status_conciliacao: dados.statusFiltros }, 
    ...subfiltros 
  });
  a.target = target;
  setTimeout(() => {
    a.click();
  }, 500);
}

function exportarErp() {
  abrirUrlExportacao(urls.erp.exportar, '_blank');
}

function exportarOperadoras() {
  abrirUrlExportacao(urls.operadoras.exportar, '_blank');
}

function retornoErp() {
  const loader = document.querySelector('#js-loader');
  const dataInicial = document.querySelector('#modal-retorno-erp #js-data-inicial');
  const dataFinal = document.querySelector('#modal-retorno-erp #js-data-final');

  swal('Aguarde um momento...', 'O processo pode levar alguns segundos.', 'warning');
  alternarVisibilidade(loader);

    api.get(urls.retornoErp, {
      params: {
        'data-inicial': dataInicial.value,
        'data-final': dataFinal.value,
      },
    })
    .then(res => {
      if(res.status === 'erro' && res.mensagem) {
        swal("Ooops...", res.mensagem, "error");
        return;
      }

      swal("Retorno ERP realizado!", `${res.vendas.length} de ${res.total} registros atualizados!`, "success");
    })
    .catch(err => {
      swal("Ooops...", "Um erro inesperado ocorreu. Tente novamente mais tarde!", "error");
    })
    .finally(() => {
      alternarVisibilidade(loader);
      document.querySelector('#modal-retorno-erp #js-data-inicial').value = "";
      document.querySelector('#modal-retorno-erp #js-data-final').value = "";
    });
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
          filtros: {
            ...dados.filtros,
            status_conciliacao: dados.statusFiltros
          },
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

Array.from(
  document.querySelectorAll('button[data-target="#js-abrir-justificar-modal"]')
).forEach(element => {
  element.addEventListener('click', abrirModalJustificativa);
});
document.querySelector('#js-desjustificar')
  .addEventListener('click', confirmarDesjustificar);

document.querySelector('#js-exportar-erp')
  .addEventListener('click', exportarErp);

document.querySelector('#js-exportar-operadoras')
  .addEventListener('click', exportarOperadoras);

document.querySelector('#js-retorno-erp')
  .addEventListener('click', retornoErp);