const adquirentesDOM = document.querySelectorAll('input.adquirente');
const meiosCapturaDOM = document.querySelectorAll('input.meio-captura');
const btPesquisar = document.querySelector('#bt-pesquisar');
const btsSelecionarTudo = document.querySelectorAll('.selecionar-tudo');

const adquirentes = Array.from(adquirentesDOM).reduce(function (adquirentes, adquirenteInput) {
    const adquirente = {
        CODIGO: adquirenteInput.dataset.codigo,
        ADQUIRENTE: adquirenteInput.dataset.adquirente,
    };

    adquirentes.push(adquirente);
    return adquirentes;
}, []);

const meiosCaptura = Array.from(meiosCapturaDOM).reduce(function (meiosCaptura, meioCapturaInput) {
    const adquirente = {
        CODIGO: meioCapturaInput.dataset.codigo,
        DESCRICAO: meioCapturaInput.dataset.descricao,
    };

    meiosCaptura.push(adquirente);
    return meiosCaptura;
}, []);

function selecionarTudo(event) {
    const seletor = event.target.dataset.seletor;
    const estaSelecionado = event.target.checked;

    const naoSelecionados = document.querySelectorAll(`input.${seletor}:${estaSelecionado ? 'not(:checked)' : 'checked'}`);

    Array.from(naoSelecionados).forEach(naoSelecionado => {
        naoSelecionado.checked = estaSelecionado;
        naoSelecionado.dispatchEvent(new Event('change'));
    });
}

Array.from(adquirentesDOM).forEach(adquirenteCheckBox => {
    adquirenteCheckBox.addEventListener('change', () => {
        const adquirenteInput = document.querySelector('#adquirente.form-control');
        console.log(adquirenteInput);
        const adquirentesSelecionados = document.querySelectorAll('input.adquirente:checked');
        console.log(adquirentesSelecionados);
        const adquirentes = Array.from(adquirentesSelecionados).map(selecionado => selecionado.dataset.adquirente);
        console.log(adquirentes.join(', '))

        adquirenteInput.value = adquirentes.join(', ');
    })
});

Array.from(meiosCapturaDOM).forEach(meioCapturaCheckbox => {
    meioCapturaCheckbox.addEventListener('change', () => {
        const meioCapturaInput = document.querySelector('#meiocaptura.form-control');
        console.log(meioCapturaInput);
        const meiosCapturaSelecionados = document.querySelectorAll('input.meio-captura:checked');
        console.log(meiosCapturaSelecionados);
        const meiosCaptura = Array.from(meiosCapturaSelecionados).map(selecionado => selecionado.dataset.descricao);
        console.log(meiosCaptura.join(', '))

        meioCapturaInput.value = meiosCaptura.join(', ');
    })
});

Array.from(btsSelecionarTudo).forEach(btSelecionarTudo => {
    btSelecionarTudo.addEventListener('change', selecionarTudo);
})

btPesquisar.addEventListener('click', (event) => {
    console.log(event);
});
