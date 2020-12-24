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
    busca: { paginacao: new Pagination([], { perPage: 5 }) },
    filtrados: { paginacao: new Pagination([], { perPage: 5 }) },
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
      filtrar: form.dataset.urlFiltrarErp
    },
    operadoras: {
      buscar: form.dataset.urlOperadoras,
      filtrar: form.dataset.urlFiltrarOperadoras
    }
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
      const vendas = await requisitarVendas(url, body, params).catch(err => alert("Não foi possível realizar a consulta!"));
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

function atualizarInterface(modalidadeVendas, dados, paginacao = null) {
  renderizarTabela(modalidadeVendas, dados.vendas, dados.totais);
  if(paginacao) {
    paginacao.render();
    document.querySelector(`#js-porpagina-${modalidadeVendas}`).value = paginacao.options.perPage
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

function limparFiltros() {
  const seletor = 'table input:not([type="checkbox"]):not([name=""])';
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

async function requisitarVendas(url, body = {}, params = {}) {
  const csrfToken = document.querySelector('input[name="_token"').value;

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
    window.scrollTo(0, resultados.offsetTop);
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
      alert("Ooops... Um erro inesperado ocorreu.");
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

    const imagensDOM = tr.querySelectorAll('td img');
    const colunasDOM = tr.querySelectorAll('td[data-campo]');
    const inputsDOM = tr.querySelectorAll('input[type="checkbox"][data-campo]');
    
    [...imagensDOM].forEach(imagemDOM => {
      const imagemUrl = imagemDOM.dataset.image;
      const textoImagem = imagemDOM.dataset.text;
      imagemDOM.src = venda[imagemUrl] || 'assets/images/iconCart.jpeg';
      imagemDOM.alt = venda[textoImagem] || 'Sem identificação';
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

window.addEventListener('load', () => {
  document.querySelector('#pagina-conciliacao').classList.remove('hidden');
  window.scrollTo(0, 0);
});

document.querySelector('#js-form-pesquisar').addEventListener('submit', submeterPesquisa);

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