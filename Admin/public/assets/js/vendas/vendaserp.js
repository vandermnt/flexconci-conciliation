const adquirentesDOM = Array.from(document.querySelectorAll('input.adquirente'));
const adquirentes = adquirentesDOM.reduce(function (adquirentes, adquirenteInput) {
    const adquirente = {
        CODIGO: adquirenteInput.dataset.codigo,
        ADQUIRENTE: adquirenteInput.dataset.adquirente,
    };

    adquirentes.push(adquirente);
    return adquirentes;
}, []);

const meiosCapturaDOM = Array.from(document.querySelectorAll('input.meio-captura'));
const meiosCaptura = meiosCapturaDOM.reduce(function (meiosCaptura, meioCapturaInput) {
    const adquirente = {
        CODIGO: meioCapturaInput.dataset.codigo,
        DESCRICAO: meioCapturaInput.dataset.descricao,
    };

    meiosCaptura.push(adquirente);
    return meiosCaptura;
}, []);