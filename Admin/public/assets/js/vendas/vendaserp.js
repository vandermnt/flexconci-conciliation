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
    let tabelaVendasHTML = '';
    
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

    resultadosPesquisa.querySelector('#jsgrid-table tbody').innerHTML = tabelaVendasHTML;
}

function enviarFiltrosForm(event) {
    event.preventDefault();

    const url = event.target.action;

    const dados = serializarDadosFiltros();
    alternaVisibilidade(carregamentoModal);

    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': dados.csrfToken, 'Content-Type': 'application/json' },
        body: JSON.stringify(dados),
    }).then(response => {
        return response.json();
    }).then(json => {
        renderizaTabela(json.flat(2));
        if(resultadosPesquisa.classList.contains('hidden')) {
            alternaVisibilidade(resultadosPesquisa);
        }
        
        window.scrollTo(0, 550);
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

formFiltros.addEventListener('submit', enviarFiltrosForm);

btPesquisar.addEventListener('click', (event) => {
    formFiltros.dispatchEvent(new Event('submit'));
});
