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
const tbFiltrosDOM = document.querySelectorAll('#resultadosPesquisa th input');
let tabelaFiltros = {};

let vendas = [];
let vendasPaginaAtual = [];
let dadosPaginacao = {
    paginaAtual: 1
};

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

function montarUrl(urlBase, parametros) {
    const url = Object.keys(parametros).reduce((url, parametro) => {
        const valor = parametros[parametro];
        return `${url}${parametro}=${valor}&`
    }, `${urlBase}?`);

    /** Remoção do '&' restante no final da string montada */
    const urlFormatada = url.replace(/\&$/g, '');

    return urlFormatada;
}

async function requisitaVendas(urlBase, parametros = {}, dadosRequisicao) {
    const quantidade = selectPorPagina.value;

    if(!parametros.por_pagina)
        parametros.por_pagina = quantidade;
    
    const url = montarUrl(urlBase, parametros);

    const response = await fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': dadosRequisicao.csrfToken, 'Content-Type': 'application/json' },
        body: JSON.stringify(dadosRequisicao),
    });

    const [json] = await response.json();

    return json;
}

async function requisitaTodasAsVendas(urlBase, total, quantidadePorPagina) {
    const totalPaginas = Math.ceil(total / quantidadePorPagina);
    const dados = serializarDadosFiltros(); 

    return Promise.all(
        Array.from({ length: totalPaginas }, pagina => {
            return requisitaVendas(urlBase, {
                page: pagina,
                por_pagina: quantidadePorPagina
            }, dados);
        })
    );
}

function irParaPagina(event) {
    const pagina = event.target.dataset.pagina;
    const url = paginacaoDOM.dataset.url;
    paginaAtual = pagina;

    enviarFiltros({ url, parametros: { page: pagina } });
}

function selecionaQuantidadePorPagina(event) {
    const url = paginacaoDOM.dataset.url;
    enviarFiltros({ url });
}

async function enviarFiltros({ url, parametros }) {
    const dados = serializarDadosFiltros();

    alternaVisibilidade(carregamentoModal);

    const resposta = await requisitaVendas(url, parametros, dados);

    dadosPaginacao = {
        last_page: resposta.last_page,
        current_page: resposta.current_page,
        path: resposta.path,
        total: resposta.total
    };
    vendasPaginaAtual = resposta.data.flat(1);

    if(resultadosPesquisa.classList.contains('hidden')) {
        alternaVisibilidade(resultadosPesquisa);
    }

    alternaVisibilidade(carregamentoModal);

    renderizaTabela(vendasPaginaAtual);
    renderizaPaginacao(dadosPaginacao);


    return resposta;
}

function filtrarTabela(filtros, paginas) {
    let filtrados = [];

    paginas.forEach(pagina => {
        const vendas = pagina.data.flat(1);
        const filtradosPagina = vendas.filter(venda => {
            return Object.keys(filtros).map(filtro => {
                return (new RegExp(filtros[filtro], 'gi').test(venda[filtro]));
            }).some((valor) => valor === true);
        });

        filtrados = [...filtrados, filtradosPagina].flat(1);
    });

    return filtrados;
}

btLimparForm.addEventListener('click', limparCampos);

Array.from(btsSelecionarTudo).forEach(btSelecionarTudo => {
    btSelecionarTudo.addEventListener('change', selecionarTudo);
});

Array.from(tbFiltrosDOM).forEach(filtroInput => {
    filtroInput.addEventListener('keypress', event => {
        const chave = filtroInput.name;
        const valor = filtroInput.value;

        if(valor) {
            tabelaFiltros = { ...tabelaFiltros, [chave]: valor };
        } else {
            delete tabelaFiltros[chave];
        }

        if(event.key === 'Enter') {
            const filtrados = filtrarTabela(tabelaFiltros, vendas);
            if(filtrados.length === 0) {
                renderizaTabela(vendasPaginaAtual);
                return;
            }

            renderizaTabela(filtrados);
        }
    })
})

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

    enviarFiltros({ url }).then(() => {
        window.scrollTo(0, 550);

        vendas = [];

        requisitaTodasAsVendas(dadosPaginacao.path, dadosPaginacao.total, 200)
            .then((resposta) => {
                vendas = resposta;
            }
        );
    });
});

btPesquisar.addEventListener('click', (event) => {
    formFiltros.dispatchEvent(new Event('submit'));
});
