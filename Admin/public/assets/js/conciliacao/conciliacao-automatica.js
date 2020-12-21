const checker = new Checker();
const modalFilter = new ModalFilter();
const dados = new Proxy({
  marcacoes: {
    erp: [],
    operadoras: [],
  },
  filtros: {},
  subfiltros: { erp: {}, operadoras: {} }
}, handler());
const formatadorMoeda = new Intl.NumberFormat('pt-br', {
  style: 'currency',
  currency: 'BRL'
});
const formatadorDecimal = new Intl.NumberFormat('pt-br', {
  style: 'decimal',
  maximumFractionDigits: 2,
});

checker.addGroup('empresa');
checker.addGroup('status-conciliacao');
modalFilter.addGroup('empresa');

function handler() {
  return {
    set: function(target, name, value) {
      if(['erp', 'operadoras'].includes(name)) {
        value = value[name];
  
        const vendas = value.vendas.data;
        const paginacao = value.vendas;
        paginacao.id = name;
        delete value.vendas.data;
        delete value.vendas;
        value.vendas = vendas;
        value.paginacao = criarPaginacao(paginacao)
        target[name] = { ...value };
        target[name].paginacao.render();
      }

      if(name === 'filtros') {
        target[name].data_inicial = value.data_inicial;
        target[name].data_final = value.data_final;
        target[name].grupos_clientes = value.grupos_clientes;
        target[name].status_conciliacao = value.status_conciliacao;
      }
    }
  };
}

function criarPaginacao(paginacao) {
  const novaPaginacao = new Pagination().setOptions({
    currentPage: paginacao.current_page,
    lastPage: paginacao.last_page,
    perPage: paginacao.per_page,
    total: paginacao.total,
    paginationContainer: document.querySelector(`#js-paginacao-${paginacao.id}`),
    baseUrl: paginacao.path,
    id: paginacao.id,
  });

  novaPaginacao.setNavigateHandler(page => {
    alternarVisibilidade(document.querySelector('#js-loader'));
    requisitarVendas(novaPaginacao.options.baseUrl, {
      por_pagina: novaPaginacao.options.perPage,
      page
    }).then(res => {
      const idVendas = novaPaginacao.options.id;
      dados[idVendas] = { ...res };
      renderizarTabela(idVendas, dados[idVendas].vendas, dados[idVendas].totais);
      alternarVisibilidade(document.querySelector('#js-loader'));
    });
  });
  
  return novaPaginacao;
}

function limpar() {
  const form = document.querySelector('#js-form-pesquisar');
  const dataInputs = document.querySelectorAll('#js-form-pesquisar input[type=date]');
  
  form.reset();
  [...dataInputs].forEach(input => {
    input.value = "";
  });
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
  const loader = document.querySelector('#js-loader');

  dados[tipo].paginacao.setOptions({ perPage: quantidade }).goToPage(1);

  alternarVisibilidade(loader);
  requisitarVendas(dados[tipo].paginacao.options.baseUrl, {
    por_pagina: quantidade,
    page: 1,
  }).then(res => {
    dados[tipo] = { ...res };
    renderizarTabela(tipo, dados[tipo].vendas, dados[tipo].totais);
    alternarVisibilidade(loader);
  });
}

function serializarDadosPesquisa() {
  const form = document.querySelector('#js-form-pesquisar');
  const dataInicial = form.querySelector('input[name="data_inicial"]').value;
  const dataFinal = form.querySelector('input[name="data_final"]').value;
  const csrfToken = form.querySelector('input[name="_token"]').value;
  const empresas = checker.getCheckedValues('empresa');
  const statusConciliacao = checker.getCheckedValues('status-conciliacao');

  return {
    _token: csrfToken,
    data_inicial: dataInicial,
    data_final: dataFinal,
    grupos_clientes: empresas,
    status_conciliacao: statusConciliacao
  };
}

function atualizarFiltros() {
  const filtros = serializarDadosPesquisa();
  
  dados.filtros = { ...filtros }
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

async function requisitarVendas(url, params = {}) {
  const filtros = serializarDadosPesquisa();

  return api.post(url, {
    headers: {
      'X-CSRF-TOKEN': filtros._token,
      'Content-type': 'application/json'
    },
    body: JSON.stringify(filtros),
    params
  });
}

function alternarVisibilidade(elementoDOM) {
  elementoDOM.classList.toggle('hidden');
}

async function submeterPesquisa(event) {
  event.preventDefault();

  const resultados = document.querySelector('#js-resultados');
  const loader = document.querySelector('#js-loader');

  const erpUrl = document.querySelector('#js-form-pesquisar').dataset.urlErp;
  const porPaginaErp = document.querySelector('#js-porpagina-erp').value;
  const operadorasUrl = document.querySelector('#js-form-pesquisar').dataset.urlOperadoras;
  const porPaginaOperadoras = document.querySelector('#js-porpagina-operadoras').value;

  alternarVisibilidade(loader);

  const [erp, operadoras] = await Promise.all([
    requisitarVendas(erpUrl, { por_pagina: porPaginaErp }),
    requisitarVendas(operadorasUrl, { por_pagina: porPaginaOperadoras }),
  ]).catch(err => {
    alternarVisibilidade(loader);
    alert("Ooops... Um erro inesperado ocorreu.");
  });

  dados.erp = erp;
  dados.operadoras = operadoras;
  
  atualizarBoxes();
  renderizarTabela('erp', dados.erp.vendas, dados.erp.totais);
  renderizarTabela('operadoras', dados.operadoras.vendas, dados.operadoras.totais);
  
  alternarVisibilidade(loader);
  atualizarFiltros()

  if(resultados.classList.contains('hidden')) {
    resultados.classList.remove('hidden');
  }

  window.scrollTo(0, resultados.offsetTop);
}

function atualizarBoxes() {
  const totalErp = document.querySelector('p[data-total="EPR_TOTAL_BRUTO"]');
  const totalConciliada = document.querySelector('p[data-total="TOTAL_CONCILIADA"]');
  const totalDivergente = document.querySelector('p[data-total="TOTAL_DIVERGENTE"]');
  const totalManual = document.querySelector('p[data-total="TOTAL_MANUAL"]');
  const totalJustificada = document.querySelector('p[data-total="TOTAL_JUSTIFICADA"]');
  const totalNaoConciliada = document.querySelector('p[data-total="TOTAL_NAO_CONCILIADA"]');
  const totalOperadoras = document.querySelector('p[data-total="OPERADORAS_TOTAL_BRUTO"]');

  totalErp.textContent = formatadorMoeda.format(dados.erp.totais.TOTAL_BRUTO);
  totalConciliada.textContent = formatadorMoeda.format(dados.erp.totais.TOTAL_CONCILIADA);
  totalDivergente.textContent = formatadorMoeda.format(dados.erp.totais.TOTAL_DIVERGENTE);
  totalManual.textContent = formatadorMoeda.format(dados.erp.totais.TOTAL_MANUAL);
  totalJustificada.textContent = formatadorMoeda.format(dados.erp.totais.TOTAL_JUSTIFICADA);
  totalNaoConciliada.textContent = formatadorMoeda.format(dados.erp.totais.TOTAL_NAO_CONCILIADA);
  totalOperadoras.textContent = formatadorMoeda.format(dados.operadoras.totais.TOTAL_BRUTO);
}

function renderizarTabela(tipo, vendas, totais) {
  const table = document.querySelector(`table#js-tabela-${tipo}`);
  const tbody = table.querySelector(`tbody`)
  const totaisDOM = table.querySelectorAll('tfoot td[data-chave]');
  const linhaTabelaTemplate = table.querySelector('tbody tr').cloneNode(true);
  let marcacoes = dados.marcacoes[tipo];

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

      dados.marcacoes[tipo] = marcacoes;
    })

    const imagensDOM = tr.querySelectorAll('td img');
    const colunasDOM = tr.querySelectorAll('td[data-campo]');
    
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
    const { target } = event;
    const modalidadeVendas = target.closest('table').dataset.modalidade;
    
    atualizarSubfiltros(modalidadeVendas);
  })
})

document.querySelector('#js-reset-form')
  .addEventListener('click', limpar);

[...document.querySelectorAll('select[name="por_pagina"]')].forEach(select => {
  select.addEventListener('change', event => selecionarQuantidadePagina(event));
});

[...document.querySelectorAll('button[data-acao]')].forEach(botaoDOM => {
  botaoDOM.addEventListener('click', confirmarSelecao);
});