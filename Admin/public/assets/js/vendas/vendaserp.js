const adquirentesDOM = document.querySelectorAll('input[type=checkbox].adquirente');
const meiosCapturaDOM = document.querySelectorAll('input[type=checkbox].meio-captura');
const carregamentoModal = document.querySelector("#preloader");
const formFiltros = document.querySelector('form#myform');
const dataInputs = document.querySelectorAll('form#myform input[type=date]');
const btPesquisar = document.querySelector('#bt-pesquisar');
const btsSelecionarTudo = document.querySelectorAll('.selecionar-tudo');
const btConfirmarAdquirentes = document.querySelector('.modal-adquirentes .bt-confirmar-selecao');
const btConfirmarMeiosCaptura = document.querySelector('.modal-meio-captura .bt-confirmar-selecao');
const btLimparForm = document.querySelector('.bt-limpar-form');
const resultadosPesquisa = document.querySelector('#resultadosPesquisa');
const paginacaoDOM = document.querySelector('#resultadosPesquisa ul.pagination');
const selectPorPagina = document.querySelector('.form-control[name="porPagina"]');

function limparCampos(event) {
    formFiltros.reset();
    Array.from(dataInputs).forEach(dataInput => {
        dataInput.value = "";
    });
}

function selecionarTudo(event) {
    const seletor = event.target.dataset.seletor;
    const estaSelecionado = event.target.checked;

    const naoSelecionados = document.querySelectorAll(`input.${seletor}:${estaSelecionado ? 'not(:checked)' : 'checked'}`);

    Array.from(naoSelecionados).forEach(naoSelecionado => {
        naoSelecionado.checked = estaSelecionado;
    });
}

function atualizaFiltroSelecao(seletor) {
    const inputFiltroSelecao = document.querySelector(`input:not([type=checkbox]).${seletor}`);
    const selecionados = document.querySelectorAll(`input.${seletor}:checked`);
    const valoresSelecionados = Array.from(selecionados).map(selecionado => selecionado.dataset.descricao);
    inputFiltroSelecao.value = valoresSelecionados.join(', ');
}

function alternaVisibilidade(elemento) {
    elemento.classList.toggle('hidden');
}

function serializarDadosFiltros() {
    const adquirentesSelecionados = document.querySelectorAll('input[type=checkbox]:checked.adquirente');
    const meiosCapturaSelecionados = document.querySelectorAll('input[type=checkbox]:checked.meio-captura');
    const [dataInicialDOM, dataFinalDOM] = dataInputs;

    const data_inicial = dataInicialDOM.value;
    const data_final = dataFinalDOM.value;
    const arrayAdquirentes = Array.from(adquirentesSelecionados).map(selecionado => selecionado.dataset.codigo);
    const arrayMeioCaptura = Array.from(meiosCapturaSelecionados).map(selecionado => selecionado.dataset.codigo);
    const cod_autorizacao = document.querySelector('#cod_autorizacao').value;
    const identificador_pagamento = document.querySelector('#identificador_pagamento').value;
    const nsu = document.getElementById("nsu").value;
    const quantidadePorPagina = selectPorPagina.value;
    const csrfToken = document.querySelector('input[name=_token]').value;

    const dados = {
        data_inicial,
        data_final,
        arrayAdquirentes,
        arrayMeioCaptura,
        cod_autorizacao,
        identificador_pagamento,
        nsu,
        csrfToken,
    };

    return dados;
}

function renderizaTabela(vendas) {
    const tabelaVendas = resultadosPesquisa.querySelector('#jsgrid-table tbody');
    let tabelaVendasHTML = '';

    tabelaVendas.innerHTML = ''
    vendas.forEach(venda => {
        const vendaFormatada = {
            ...venda,
            DATA_VENDA: new Date(venda.DATA_VENDA).toLocaleDateString(),
            DATA_VENCIMENTO: new Date(venda.DATA_VENCIMENTO).toLocaleDateString(),
            TOTAL_VENDA: new Intl.NumberFormat('pt-br', {
                style: 'currency',
                currency: 'BRL',
            }).format(venda.TOTAL_VENDA),
            VALOR_LIQUIDO_PARCELA: new Intl.NumberFormat('pt-br', {
                style: 'currency',
                currency: 'BRL',
            }).format(venda.VALOR_LIQUIDO_PARCELA),
        }

        tabelaVendasHTML += `
            <tr>
                <td>
                    <a class="link-impressao">
                        <i class="fas fa-print"></i>
                    </a>
                </td>
                <td>${vendaFormatada.DATA_VENDA}</td>
                <td>${vendaFormatada.DATA_VENCIMENTO}</td>
                <td>${vendaFormatada.NSU || ''}</td>
                <td>${vendaFormatada.TOTAL_VENDA}</td>
                <td>${vendaFormatada.PARCELA}</td>
                <td>${vendaFormatada.TOTAL_PARCELAS}</td>
                <td>${vendaFormatada.VALOR_LIQUIDO_PARCELA}</td>
                <td>${vendaFormatada.DESCRICAO_TIPO_PRODUTO}</td>
                <td>${vendaFormatada.CODIGO_AUTORIZACAO}</td>
                <td>${vendaFormatada.IDENTIFICADOR_PAGAMENTO}</td>
                <td>${vendaFormatada.MEIOCAPTURA}</td>
                <td>${vendaFormatada.STATUS_CONCILIACAO}</td>
                <td></td>
            </tr>
        `;
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

function renderizaItemPaginacao(itemPaginacao, descricao = itemPaginacao.pagina) {
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
    const totalPaginas = paginacao.last_page;
    const paginaAtual = paginacao.current_page;
    const urlBase = paginacao.path;
    const secoes = dividirPaginacao(paginaAtual, totalPaginas);

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

async function requisitaVendas(url, dadosRequisicao) {
    const response = await fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': dadosRequisicao.csrfToken, 'Content-Type': 'application/json' },
        body: JSON.stringify(dadosRequisicao),
    });

    const [json] = await response.json();

    return json;
}

function irParaPagina(event) {
    const url = event.target.dataset.url;
    const quantidadePorPagina = selectPorPagina.value;

    enviarFiltros(`${url}&por_pagina=${quantidadePorPagina}`);
}

function selecionaQuantidadePorPagina(event) {
    const url = formFiltros.action;
    const quantidade = event.target.value;
    enviarFiltros(`${url}?por_pagina=${quantidade}`);
}

function enviarFiltros(url, callback = () => {}) {
    const dados = serializarDadosFiltros();
    alternaVisibilidade(carregamentoModal);

    requisitaVendas(url, dados).then(resposta => {
        renderizaTabela(resposta.data.flat(1));
        renderizaPaginacao({
            last_page: resposta.last_page,
            current_page: resposta.current_page,
            path: resposta.path
        });

        if(resultadosPesquisa.classList.contains('hidden')) {
            alternaVisibilidade(resultadosPesquisa);
        }

        callback();
    }).finally(() => {
        alternaVisibilidade(carregamentoModal);
    });
}

btLimparForm.addEventListener('click', limparCampos);

Array.from(btsSelecionarTudo).forEach(btSelecionarTudo => {
    btSelecionarTudo.addEventListener('change', selecionarTudo);
});

btConfirmarAdquirentes.addEventListener('click', () => {
    atualizaFiltroSelecao('adquirente');
});

btConfirmarMeiosCaptura.addEventListener('click', () => {
    atualizaFiltroSelecao('meio-captura');
});

selectPorPagina.addEventListener('change', selecionaQuantidadePorPagina);

formFiltros.addEventListener('submit', (event) => {
    event.preventDefault();
    const url = event.target.action;
    const quantidade = selectPorPagina.value;

    enviarFiltros(`${url}?por_pagina=${quantidade}`, () => {
        window.scrollTo(0, 550);
    });
});

btPesquisar.addEventListener('click', (event) => {
    formFiltros.dispatchEvent(new Event('submit'));
});
