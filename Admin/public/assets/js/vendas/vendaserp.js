const checker = new Checker();
const paginacao = new Pagination([], { from: 'api' });
const paginacaoFiltros = new Pagination([], { from: 'cache' });
let vendas = [];
let totais = {};
let tabelaFiltros = {};

const observadores = {
    'vendas': () => {}
}

function inicializar() {
    checker.addGroup('empresa');
    checker.addGroup('adquirente');
    checker.addGroup('bandeira');
    checker.addGroup('modalidade');
    checker.addGroup('meio-captura');
    checker.addGroup('status-conciliacao');
    checker.addGroup('status-financeiro');

    const formPesquisa = document.querySelector('form#form-pesquisa');
    const btPesquisar = document.querySelector('#bt-pesquisar');
    const btLimparForm = document.querySelector('.bt-limpar-form');
    const selectPorPagina = document.querySelector('.form-control[name="porPagina"]');
    const btAcoesModal = document.querySelectorAll('.modal-footer button[data-acao]');
    const tbFiltrosDOM = document.querySelectorAll('#resultadosPesquisa th input');

    formPesquisa.addEventListener('submit', submeterFormularioPesquisa);
    btPesquisar.addEventListener('click', pesquisar);
    btLimparForm.addEventListener('click', limparCampos);
    selectPorPagina.addEventListener('change', selecionaQuantidadePorPagina);
    [...btAcoesModal]
        .forEach(btAcaoModal => btAcaoModal.addEventListener('click', confirmarCancelarSelecao));
    [...tbFiltrosDOM]
        .forEach(filtroInput => filtroInput.addEventListener('keyup', atualizaFiltrosTabela));
}

function submeterFormularioPesquisa(event) {
    event.preventDefault();
    const url = event.target.action;
    
    enviarFiltros({ url }).then(() => {
        limparFiltrosTabela();
        window.scrollTo(0, 880);

        vendas = [];

        requisitaTodasAsVendas(url)
            .then((resposta) => {
                vendas = resposta.vendas;
                observadores.vendas();
            }
        );
    });
}

function pesquisar(event) {
    const formPesquisa = document.querySelector('form#form-pesquisa');
    formPesquisa.dispatchEvent(new Event('submit', { cancelable: true }));
}

function limparCampos(event) {
    const form = document.querySelector('form#form-pesquisa');
    const dataInputs = document.querySelectorAll('form#form-pesquisa input[type=date]');
    
    form.reset();
    Array.from(dataInputs).forEach(dataInput => {
        dataInput.value = "";
    });
}

function limparFiltrosTabela() {
    const tbFiltrosDOM = document.querySelectorAll('#resultadosPesquisa th input');
    [...tbFiltrosDOM].forEach(input => {
        input.value = "";
    });
    tabelaFiltros = {};
}

function confirmarCancelarSelecao(event) {
    const { acao, group } = event.target.dataset;

    if(acao === 'cancelar') {
        checker.uncheckAll(group);
    }

    checker.setValuesToTextElement(group, 'descricao');
}

function alternaVisibilidade(elemento) {
    elemento.classList.toggle('hidden');
}

function serializarDadosFiltros() {
    const empresas = checker.getCheckedValues('empresa');
    const arrayAdquirentes = checker.getCheckedValues('adquirente');
    const bandeiras = checker.getCheckedValues('bandeira');
    const modalidades = checker.getCheckedValues('modalidade');
    const arrayMeioCaptura = checker.getCheckedValues('meio-captura');
    const statusConciliacaoSelecionados = checker.getCheckedValues('status-conciliacao');
    const statusFinanceiroSelecionados = checker.getCheckedValues('status-financeiro');

    const [
        dataInicialDOM,
        dataFinalDOM
    ] = document.querySelectorAll('form#form-pesquisa input[type=date]');


    const data_inicial = dataInicialDOM.value;
    const data_final = dataFinalDOM.value;
    const id_erp = document.querySelector('#id_erp').value;
    const csrfToken = document.querySelector('input[name=_token]').value;

    const dados = {
        data_inicial,
        data_final,
        empresas,
        arrayAdquirentes,
        bandeiras,
        modalidades,
        arrayMeioCaptura,
        id_erp,
        status_conciliacao: statusConciliacaoSelecionados,
        status_financeiro: statusFinanceiroSelecionados,
        csrfToken,
    };

    return dados;
}

function formatarDadosVenda(venda) {
    const formatadorMoeda = new Intl.NumberFormat('pt-br', {
        style: 'currency',
        currency: 'BRL',
    });

    const taxa = Number(venda.TAXA) || 0;

    return {
        ...venda,
        DATA_VENDA: new Date(`${venda.DATA_VENDA} 00:00:00`).toLocaleDateString(),
        DATA_VENCIMENTO: new Date(`${venda.DATA_VENCIMENTO} 00:00:00`).toLocaleDateString(),
        TOTAL_VENDA: formatadorMoeda.format(venda.TOTAL_VENDA),
        VALOR_LIQUIDO_PARCELA: formatadorMoeda.format(venda.VALOR_LIQUIDO_PARCELA),
        TAXA: taxa.toFixed(2),
        VALOR_TAXA: formatadorMoeda.format(venda.VALOR_TAXA || 0)
    }
}

function renderizaTabela(vendas, totais) {
    const tabelaVendas = document.querySelector('#resultadosPesquisa #jsgrid-table tbody');
    const formatadorMoeda = new Intl.NumberFormat('pt-br', {
        style: 'currency',
        currency: 'BRL',
    });

    let tabelaVendasHTML = '';

    tabelaVendas.innerHTML = ''
    vendas.forEach(venda => {
        const vendaFormatada = formatarDadosVenda(venda);

        tabelaVendasHTML += `
            <tr>
                <td>
                    <a class="link-impressao">
                        <i class="fas fa-print"></i>
                    </a>
                </td>
                <td>${vendaFormatada.ID_ERP || ''}</td>
                <td>${vendaFormatada.NOME_EMPRESA || ''}</td>
                <td>${vendaFormatada.CNPJ || ''}</td>
                <td>${vendaFormatada.DATA_VENDA || ''}</td>
                <td>${vendaFormatada.DATA_VENCIMENTO || ''}</td>
                <td>
                    ${
                        vendaFormatada.ADQUIRENTE_IMAGEM ?
                            `<img
                                class="img-fluid"
                                alt="${vendaFormatada.ADQUIRENTE || 'Sem identificação'}"
                                src="${vendaFormatada.ADQUIRENTE_IMAGEM
                                    || 'assets/images/iconCart.jpeg'}"
                            >` :
                            `${(vendaFormatada.ADQUIRENTE || '')}`
                    }
                </td>
                <td>
                    <img class="img-fluid"
                        alt="${vendaFormatada.BANDEIRA || 'Sem identificação'}"
                        src="${vendaFormatada.BANDEIRA_IMAGEM || 'assets/images/iconCart.jpeg'}"
                    >
                </td>
                <td>${vendaFormatada.MODALIDADE || ''}</td>
                <td>${vendaFormatada.NSU || ''}</td>
                <td>${vendaFormatada.CODIGO_AUTORIZACAO || ''}</td>
                <td></td>
                <td>${vendaFormatada.TOTAL_VENDA || '0,00'}</td>
                <td>${vendaFormatada.TAXA || '0,00'}</td>
                <td class="text-danger">${vendaFormatada.VALOR_TAXA || '0,00'}</td>
                <td>${vendaFormatada.VALOR_LIQUIDO_PARCELA || '0,00'}</td>
                <td>${vendaFormatada.PARCELA || ''}</td>
                <td>${vendaFormatada.TOTAL_PARCELAS || ''}</td>
                <td></td>
                <td></td>
                <td>${vendaFormatada.BANCO || ''}</td>
                <td>${vendaFormatada.AGENCIA || ''}</td>
                <td>${vendaFormatada.CONTA_CORRENTE || ''}</td>
                <td>${vendaFormatada.PRODUTO || ''}</td>
                <td>${vendaFormatada.MEIOCAPTURA || ''}</td>
                <td>${vendaFormatada.STATUS_CONCILIACAO || ''}</td>
                <td>${vendaFormatada.STATUS_FINANCEIRO || ''}</td>
                <td>${vendaFormatada.JUSTIFICATIVA || ''}</td>
                <td>${vendaFormatada.CAMPO1 || ''}</td>
                <td>${vendaFormatada.CAMPO2 || ''}</td>
                <td>${vendaFormatada.CAMPO3 || ''}</td>
            </tr>
        `;
    });

    Object.keys(totais).forEach(chave => {
        const valor = totais[chave];
        const colunaDOM = document.querySelector(`#resultadosPesquisa tfoot td[data-chave="${chave}"]`);
        colunaDOM.textContent = formatadorMoeda.format(valor);
    });

    tabelaVendas.innerHTML = tabelaVendasHTML;
}

function renderizaItemPaginacao(itemPaginacao = {}, descricao = itemPaginacao.pagina) {
    const paginacaoDOM = document.querySelector('#resultadosPesquisa ul.pagination');
    const li = document.createElement('li');
    li.classList.add('page-item');

    const link = document.createElement('a');
    link.classList.add('page-link');
    link.textContent = descricao;

    if(Number.isInteger(itemPaginacao.pagina)) {
        if(itemPaginacao.pagina === itemPaginacao.paginaAtual) li.classList.add('active');

        link.dataset.pagina = itemPaginacao.pagina;
        link.dataset.url = `${itemPaginacao.urlBase}?page=${itemPaginacao.pagina}`;
        link.dataset.origem = `${itemPaginacao.origem}`
        link.addEventListener('click', irParaPagina);
    }

    li.appendChild(link);
    paginacaoDOM.appendChild(li);
}

function renderizaPaginacao(paginacao) {
    const paginacaoDOM = document.querySelector('#resultadosPesquisa ul.pagination');
    const paginaAtual = paginacao.options.currentPage;
    const urlBase = document.querySelector('form#form-pesquisa').action;
    const origem = paginacao.options.from;
    const paginas = paginacao.toArray(true, 8);

    paginacaoDOM.dataset.url = urlBase;
    paginacaoDOM.innerHTML = '';

    paginas.forEach(pagina => {
        renderizaItemPaginacao({
            paginaAtual,
            urlBase,
            pagina,
            origem,
        })
    });
}

async function requisitaVendas(urlBase, parametros = {}, dadosRequisicao) {
    const quantidadePorPagina = document.querySelector('.form-control[name="porPagina"]').value;

    if(!parametros.por_pagina)
        parametros.por_pagina = quantidadePorPagina;
    
    const resposta = await api.post(urlBase, {
        headers: {
            'X-CSRF-TOKEN': dadosRequisicao.csrfToken,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dadosRequisicao),
        params: {
            ...parametros
        }
    });
    
    return resposta;
}

async function requisitaTodasAsVendas(urlBase, quantidadePorPagina = '*') {
    const dados = serializarDadosFiltros();
    
    const resposta = await requisitaVendas(urlBase, {
        por_pagina: quantidadePorPagina
    }, dados);

    return resposta;
}

function irParaPagina(event) {
    const pagina = event.target.dataset.pagina;
    const origem = event.target.dataset.origem;
    const url = document.querySelector('form#form-pesquisa').action;

    paginaAtual = pagina;
    
    if(origem === 'cache') {
        paginacaoFiltros.goToPage(pagina);
        renderizaTabela(paginacaoFiltros.getPageData(), totais);
        renderizaPaginacao(paginacaoFiltros);
        return;
    }

    enviarFiltros({ url, parametros: { page: pagina } });
}

function selecionaQuantidadePorPagina(event) {
    const quantidadePorPagina = event.target.value;
    const url = document.querySelector('form#form-pesquisa').action;

    paginacao.setOptions({ perPage: Number(quantidadePorPagina) }).paginate();
    
    if(Object.keys(tabelaFiltros).length > 0) {
        paginacaoFiltros.setOptions({ perPage: Number(quantidadePorPagina) });
        paginacaoFiltros.goToPage(1).paginate();
        renderizaTabela(paginacaoFiltros.getPageData(1), totais);
        renderizaPaginacao(paginacaoFiltros);
        paginacao.setData(vendas);
        return;
    }

    enviarFiltros({ url, parametros: { page: paginacao.options.currentPage }});
}

async function enviarFiltros({ url, parametros }) {
    const resultadosPesquisa = document.querySelector('#resultadosPesquisa');
    const carregamentoModal = document.querySelector("#preloader");
    const dados = serializarDadosFiltros();

    alternaVisibilidade(carregamentoModal);

    const resposta = await requisitaVendas(url, parametros, dados);

    paginacao.setData(resposta.vendas);
    paginacao.setOptions({
        currentPage: resposta.paginacao.current_page,
        lastPage: resposta.paginacao.last_page,
        path: resposta.paginacao.path,
        total: resposta.paginacao.total,
        perPage: resposta.paginacao.per_page
    });

    totais = resposta.totais;

    if(resultadosPesquisa.classList.contains('hidden')) {
        alternaVisibilidade(resultadosPesquisa);
    }

    alternaVisibilidade(carregamentoModal);

    renderizaTabela(paginacao.data, totais);
    renderizaPaginacao(paginacao);


    return resposta;
}

function exibeAlertaQuantidadeResultados(quantidade = 0) {
    const quantidadeResultadosAlerta = document.querySelector('.alerta-quantidade-resultados');
    const quantidadeResultadosSpan = document.querySelector('.alerta-quantidade-resultados span');

    quantidadeResultadosSpan.textContent = quantidade;
    quantidadeResultadosAlerta.classList.remove('hidden');
    quantidadeResultadosAlerta.classList.add('deslizar-alerta');
    setTimeout(() => {
        quantidadeResultadosAlerta.classList.remove('deslizar-alerta');
    }, 4000);
}

function executarFiltrosTabela() {
    const filtrados = filtrarTabela(tabelaFiltros, vendas);
    paginacaoFiltros.setData(filtrados);
    paginacaoFiltros.setOptions({ total: filtrados.length });
    
    exibeAlertaQuantidadeResultados(filtrados.length);
    renderizaTabela(paginacaoFiltros.getPageData(1), totais);
    renderizaPaginacao(paginacaoFiltros.paginate());
    
}

function atualizaFiltrosTabela (event) {
    const filtroInput = event.target;
    const chave = filtroInput.name;
    const valor = filtroInput.value.trim();

    const carregamentoModal = document.querySelector("#preloader");
    const camposMoedas = ['TOTAL_VENDA', 'VALOR_LIQUIDO_PARCELA'];

    tabelaFiltros = { ...tabelaFiltros, [chave]: valor };

    if(camposMoedas.includes(chave)) {
        const valorNumerico = valor.replace(/[^0-9-,-\.]/gi, '');
        tabelaFiltros = { ...tabelaFiltros, [chave]: valorNumerico };
    }
    
    if(!valor) {
        delete tabelaFiltros[chave];
    }

    if(event.key !== 'Enter') return;
    alternaVisibilidade(carregamentoModal);

    if(Object.keys(tabelaFiltros).length === 0) {
        renderizaTabela(paginacao.getPageData(), totais);
        renderizaPaginacao(paginacao);
        alternaVisibilidade(carregamentoModal);
        return;
    }

    if(vendas.length === 0) {
        observadores.vendas = () => {
            executarFiltrosTabela();
            alternaVisibilidade(carregamentoModal);
        }

        return;
    }

    executarFiltrosTabela();

    alternaVisibilidade(carregamentoModal);
}

function filtrarTabela(filtros, vendas) {
    const filtrados = vendas.filter(venda => {
        venda = formatarDadosVenda(venda);

        return Object.keys(filtros).map(filtro => {
            const filtroFormatado = filtros[filtro].replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&');
            const valor = new String(venda[filtro]);

            return (new RegExp(filtroFormatado, 'gi').test(valor));
        }).every((valor) => valor === true);
    });

    return filtrados;
}

window.addEventListener('load', (event) => {
    window.scrollTo(0, 0);
    alternaVisibilidade(document.querySelector('#tudo_page'));
    inicializar();
});