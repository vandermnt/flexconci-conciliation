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
        naoSelecionado.dispatchEvent(new Event('change'));
    });
}

function atualizaFiltroSelecao(seletor) {
    const inputFiltroSelecao = document.querySelector(`input:not([type=checkbox]).${seletor}`);
    const selecionados = document.querySelectorAll(`input.${seletor}:checked`);
    const valoresSelecionados = Array.from(selecionados).map(selecionado => selecionado.dataset.descricao);
    inputFiltroSelecao.value = valoresSelecionados.join(', ');
}

function alternaCarregamento() {
    carregamentoModal.classList.toggle('hidden');
}

function serializarDadosFiltros() {
    const adquirentesSelecionados = document.querySelectorAll('input[type=checkbox]:checked.adquirente');
    const meiosCapturaSelecionados = document.querySelectorAll('input[type=checkbox]:checked.meio-captura');

    const data_inicial = dataInputs[0].value;
    const data_final = dataInputs[dataInputs.length - 1].value;
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

function enviarFiltrosForm(form) {
    const url = form.action;

    const dados = serializarDadosFiltros();
    alternaCarregamento();

    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': dados.csrfToken, 'Content-Type': 'application/json' },
        body: JSON.stringify(dados),
    }).then(response => {
        return response.json();
    }).then(json => {
        // TODO
    }).finally(() => {
        alternaCarregamento();
    })
}

btLimparForm.addEventListener('click', limparCampos);

Array.from(btsSelecionarTudo).forEach(btSelecionarTudo => {
    btSelecionarTudo.addEventListener('change', selecionarTudo);
})

btConfirmarAdquirentes.addEventListener('click', () => {
    atualizaFiltroSelecao('adquirente');
});

btConfirmarMeiosCaptura.addEventListener('click', () => {
    atualizaFiltroSelecao('meio-captura');
});

myform.addEventListener('submit', event => {
    event.preventDefault();
    enviarFiltrosForm(myform);
});

btPesquisar.addEventListener('click', (event) => {
    enviarFiltrosForm(myform);
});
