const checker = new Checker();
const modalFilter = new ModalFilter();
const formatadorMoeda = new Intl.NumberFormat('pt-br', {
  style: 'currency',
  currency: 'BRL'
});
const formatadorDecimal = new Intl.NumberFormat('pt-br', {
  style: 'decimal',
  maximumFractionDigits: 2,
})
const dados = new Proxy({}, {
  set: function(target, name, value) {
    if(['erp', 'operadoras'].includes(name)) {
      value = value[name];

      const vendas = value.vendas.data;
      const paginacao = value.vendas;
      delete value.vendas.data;
      delete value.vendas;
      value.vendas = vendas;
      value.paginacao = paginacao;
      target[name] = { ...value };
    }
  }
});

checker.addGroup('empresa');
checker.addGroup('status-conciliacao');
modalFilter.addGroup('empresa');

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

async function requisitarVendasErp() {
  const url = document.querySelector('#js-form-pesquisar').dataset.urlErp;
  const por_pagina = document.querySelector('#por_pagina_erp').value;

  return requisitarVendas(url, { por_pagina });
}

async function requisitarVendasOperadoras() {
  const url = document.querySelector('#js-form-pesquisar').dataset.urlOperadoras;
  const por_pagina = document.querySelector('#por_pagina_operadoras').value;

  return requisitarVendas(url, { por_pagina })
}

async function submeterPesquisa(event) {
  event.preventDefault();

  Promise.all([
    requisitarVendasErp(), 
    requisitarVendasOperadoras()
  ]).then(res => {
    const [erp, operadoras] = res;
    dados.erp = erp;
    dados.operadoras = operadoras;

    atualizarBoxes();
    renderizarTabela(dados.erp.vendas, dados.erp.totais, document.querySelector('#js-tabela-erp'));
    renderizarTabela(dados.operadoras.vendas, dados.operadoras.totais, document.querySelector('#js-tabela-pendencias'));
  });
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

function renderizarTabela(vendas, totais, tabelaDOM) {
  const tbody = tabelaDOM.querySelector('tbody');
  const totaisDOM = tabelaDOM.querySelectorAll('tfoot td[data-chave]');
  const linhaTabelaTemplate = tabelaDOM.querySelector('tbody tr').cloneNode(true);

  tbody.innerHTML = '';
  tbody.appendChild(linhaTabelaTemplate);

  vendas.forEach(venda => {
    const tr = linhaTabelaTemplate.cloneNode(true);
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
});

document.querySelector('#js-form-pesquisar').addEventListener('submit', submeterPesquisa);

document.querySelector('#js-reset-form')
  .addEventListener('click', limpar);

[...document.querySelectorAll('button[data-acao]')].forEach(botaoDOM => {
  botaoDOM.addEventListener('click', confirmarSelecao);
});