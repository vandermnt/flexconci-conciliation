const data = new Date(),
  dia = data.getDate() > 9 ? data.getDate() : `0${data.getDate()}`,
  mes =
    data.getMonth() + 1 > 9 ? data.getMonth() + 1 : `0${data.getMonth() + 1}`,
  ano = data.getFullYear();
const data_atual = [ano, mes, dia].join("-");
let venda_prevista_pagamento;
let venda_paga;
fetch("dados-calendario")
  .then(response => {
    response
      .json()
      .then(resultado => {
        console.log("Carregando calendário...");

        venda_prevista_pagamento = resultado.previstos;
        venda_paga = resultado.pagos;

        renderizaCalendario();
      })
      .catch(erro => {
        console.log("Erro ao converter JSON: " + erro);
      });
  })
  .catch(e => console.log(e));

function renderizaCalendario() {
  const calendarEl = document.getElementById("calendar");
  let eventsList = [];
  console.log("Renderizando calendário!!!!!!");
  venda_paga.forEach(pagamento => {
    if (pagamento.DATA_PAGAMENTO < data_atual) {
      const total_liq = formataMoeda(pagamento.val_liquido);

      eventsList.push({
        title: total_liq,
        description: pagamento.CODIGO,
        start: pagamento.DATA_PAGAMENTO,
        color: "#257e4a",
        background: "#FF4000",
        publicId: pagamento.DATA_PAGAMENTO
      });
    }
  });

  venda_prevista_pagamento.forEach(previsao_pagamento => {
    if (previsao_pagamento.DATA_PREVISTA_PAGTO >= data_atual) {
      const total_liq_prev_pagt = formataMoeda(previsao_pagamento.val_liquido);

      eventsList.push({
        title: total_liq_prev_pagt,
        description: previsao_pagamento.CODIGO,
        start: previsao_pagamento.DATA_PREVISTA_PAGTO,
        color: "#2D93AD",
        publicId: previsao_pagamento.DATA_PREVISTA_PAGTO
      });
    }
  });

  var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: ""
    },
    height: 550,
    navLinks: false,
    businessHours: true,
    events: eventsList,
    eventClick: function(calEvent, jsEvent, view) {
      if (calEvent.event._def.extendedProps.publicId) {
        const color = calEvent.event._def.ui.backgroundColor;
        const data_clicada = calEvent.event._def.extendedProps.publicId;
        const valor = calEvent.event._def.title;

        showRecebiveis(data_clicada, valor, color, jsEvent);
      }
    }
  });

  $(".fc-prev-button").append('<i class="glyphicon"...</i>');

  calendar.render();
}

function formataMoeda(valor) {
  return Intl.NumberFormat("pt-br", {
    style: "currency",
    currency: "BRL"
  }).format(valor);
}
