const checker = new Checker();
let vendas = [];
let vendasPaginaAtual = [];
let paginacao = {};
let totais = {};
let tabelaFiltros = {};

function inicializar() {
    checker.addGroup('adquirente');
    checker.addGroup('meio-captura');
    checker.addGroup('status-conciliacao');

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
        window.scrollTo(0, 550);

        vendas = [];

        requisitaTodasAsVendas(paginacao.path)
            .then((resposta) => {
                vendas = resposta.vendas;
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
    const adquirentesSelecionados = checker.getCheckedValues('adquirente');
    const meiosCapturaSelecionados = checker.getCheckedValues('meio-captura');
    const statusConciliacaoSelecionados = checker.getCheckedValues('status-conciliacao');

    const [
        dataInicialDOM,
        dataFinalDOM
    ] = document.querySelectorAll('form#form-pesquisa input[type=date]');


    const data_inicial = dataInicialDOM.value;
    const data_final = dataFinalDOM.value;
    const arrayAdquirentes = adquirentesSelecionados;
    const arrayMeioCaptura = meiosCapturaSelecionados;
    const cod_autorizacao = document.querySelector('#cod_autorizacao').value;
    const identificador_pagamento = document.querySelector('#identificador_pagamento').value;
    const nsu = document.getElementById("nsu").value;
    const csrfToken = document.querySelector('input[name=_token]').value;

    const dados = {
        data_inicial,
        data_final,
        arrayAdquirentes,
        arrayMeioCaptura,
        status_conciliacao: statusConciliacaoSelecionados,
        cod_autorizacao,
        identificador_pagamento,
        nsu,
        csrfToken,
    };

    return dados;
}

function formatarDadosVenda(venda) {
    const formatadorMoeda = new Intl.NumberFormat('pt-br', {
        style: 'currency',
        currency: 'BRL',
    });

    return {
        ...venda,
        DATA_VENDA: new Date(`${venda.DATA_VENDA} 00:00:00`).toLocaleDateString(),
        DATA_VENCIMENTO: new Date(`${venda.DATA_VENCIMENTO} 00:00:00`).toLocaleDateString(),
        TOTAL_VENDA: formatadorMoeda.format(venda.TOTAL_VENDA),
        VALOR_LIQUIDO_PARCELA: formatadorMoeda.format(venda.VALOR_LIQUIDO_PARCELA),
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
                <td>${vendaFormatada.DATA_VENDA || ''}</td>
                <td>${vendaFormatada.DATA_VENCIMENTO || ''}</td>
                <td>${vendaFormatada.NSU || ''}</td>
                <td>${vendaFormatada.TOTAL_VENDA || ''}</td>
                <td>${vendaFormatada.PARCELA || ''}</td>
                <td>${vendaFormatada.TOTAL_PARCELAS || ''}</td>
                <td>${vendaFormatada.VALOR_LIQUIDO_PARCELA || ''}</td>
                <td>${vendaFormatada.DESCRICAO_TIPO_PRODUTO || ''}</td>
                <td>${vendaFormatada.CODIGO_AUTORIZACAO || ''}</td>
                <td>${vendaFormatada.IDENTIFICADOR_PAGAMENTO || ''}</td>
                <td>${vendaFormatada.MEIOCAPTURA || ''}</td>
                <td>${vendaFormatada.STATUS_CONCILIACAO || ''}</td>
                <td>${vendaFormatada.JUSTIFICATIVA || ''}</td>
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

function dividirPaginacao(paginaAtual, paginaFinal) {
    let inicio = [];
    let meio = [];
    let fim = [];

    if(paginaAtual < 5) {
        inicio = Array.from({ length: 5 }, (valor, index) => index + 1);
        meio = ['...'];
        fim = [(paginaFinal - 2), (paginaFinal - 1), paginaFinal];
    } else if(paginaAtual > 4 && paginaAtual < (paginaFinal - 3)) {
        inicio = Array.from({ length: 2 }, (valor, index) => index + 1);
        meio = ['...', (paginaAtual - 1), paginaAtual, (paginaAtual + 1)];
        fim = ['...', (paginaFinal - 2), (paginaFinal - 1), paginaFinal];
    } else {
        inicio = Array.from({ length: 2 }, (valor, index) => index + 1);
        meio = ['...'];
        fim = [(paginaFinal - 2), (paginaFinal - 1), paginaFinal];
        if(paginaAtual === (paginaFinal - 3)) {
            fim.unshift(paginaAtual);
        }
    }

    return [inicio, meio, fim];
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
        link.addEventListener('click', irParaPagina);
    }

    li.appendChild(link);
    paginacaoDOM.appendChild(li);
}

function renderizaPaginacao(paginacao) {
    const paginacaoDOM = document.querySelector('#resultadosPesquisa ul.pagination');
    const totalPaginas = paginacao.last_page;
    const paginaAtual = paginacao.current_page;
    const urlBase = paginacao.path;
    const secoes = dividirPaginacao(paginaAtual, totalPaginas);

    paginacaoDOM.dataset.url = urlBase;
    paginacaoDOM.innerHTML = '';

    const itemPaginacao = {
        paginaAtual,
        urlBase,
    }

    if(totalPaginas < 9) {
        const paginas = Array.from({ length: totalPaginas }, (valor, index) => index + 1);
        paginas.forEach(pagina => {
            renderizaItemPaginacao({
                ...itemPaginacao,
                pagina,
            });
        });

        return;
    }

    secoes.forEach(secao => {
        secao.forEach(pagina => {
            renderizaItemPaginacao({
                ...itemPaginacao,
                pagina,
            });
        });
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
    const paginacaoDOM = document.querySelector('#resultadosPesquisa ul.pagination');
    const pagina = event.target.dataset.pagina;
    const url = paginacaoDOM.dataset.url;
    paginaAtual = pagina;

    enviarFiltros({ url, parametros: { page: pagina } });
}

function selecionaQuantidadePorPagina(event) {
    const paginacaoDOM = document.querySelector('#resultadosPesquisa ul.pagination');
    const url = paginacaoDOM.dataset.url;
    enviarFiltros({ url });
}

async function enviarFiltros({ url, parametros }) {
    const resultadosPesquisa = document.querySelector('#resultadosPesquisa');
    const carregamentoModal = document.querySelector("#preloader");
    const dados = serializarDadosFiltros();

    alternaVisibilidade(carregamentoModal);

    const resposta = await requisitaVendas(url, parametros, dados);

    paginacao = resposta.paginacao;
    vendasPaginaAtual = resposta.vendas;
    totais = resposta.totais;

    if(resultadosPesquisa.classList.contains('hidden')) {
        alternaVisibilidade(resultadosPesquisa);
    }

    alternaVisibilidade(carregamentoModal);

    renderizaTabela(vendasPaginaAtual, totais);
    renderizaPaginacao(paginacao);


    return resposta;
}

function atualizaFiltrosTabela (event) {
    const carregamentoModal = document.querySelector("#preloader");
    const camposMoedas = ['TOTAL_VENDA', 'VALOR_LIQUIDO_PARCELA'];
    const filtroInput = event.target;
    const chave = filtroInput.name;
    const valor = filtroInput.value.trim();

    tabelaFiltros = { ...tabelaFiltros, [chave]: valor };

    if(camposMoedas.includes(chave)) {
        const valorNumerico = valor.replace(/[^0-9-,-\.]/gi, '');
        tabelaFiltros = { ...tabelaFiltros, [chave]: valorNumerico };
    }
    
    if(!valor) {
        delete tabelaFiltros[chave];
    }

    if(event.key === 'Enter') {
        let filtrados;
        alternaVisibilidade(carregamentoModal);

        if(Object.keys(tabelaFiltros).length === 0) {
            filtrados = vendasPaginaAtual;
        } else {
            filtrados = filtrarTabela(tabelaFiltros, vendas);
        }

        renderizaTabela(filtrados, totais);
        alternaVisibilidade(carregamentoModal);
    }
}

function filtrarTabela(filtros, vendas) {
    const filtrados = vendas.filter(venda => {
        venda = formatarDadosVenda(venda);

        return Object.keys(filtros).map(filtro => {
            const filtroFormatado = filtros[filtro].replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&');
            const valor = new String(venda[filtro]);
            console.log(new RegExp(filtroFormatado, 'gi'));
            return (new RegExp(filtroFormatado, 'gi').test(valor));
        }).every((valor) => valor === true);
    });

    return filtrados;
}

window.addEventListener('load', (event) => {
    alternaVisibilidade(document.querySelector('#tudo_page'));
    inicializar();
});