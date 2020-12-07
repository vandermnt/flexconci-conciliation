function geraGraficoVendas(dados_grafico) {

  const array = [];
  const arrayNomeAdq = [];
  let ticket_medio = null;


  dados_grafico.forEach((dado) => {
    if (dado.COD_PERIODO) {
      var percentualFloat = parseFloat(dado.PERCENTUAL);
      percentualFloat = percentualFloat.toFixed(1)
      array.push(parseFloat(percentualFloat));
      ticket_medio += parseFloat(dado.TICKET_MEDIO)
    }
  });

  dados_grafico.forEach((dado) => {
    if (dado.COD_PERIODO == periodo) {
      arrayNomeAdq.push(dado.ADQUIRENTE);
    }
  });


  cores = ["#119DA4", "#FFBC42", "#DA2C38", "#4CB944", "#FF8000"];
  coresGrafico = [];

  for (var i = 0; i < arrayNomeAdq.length; i++) {
    coresGrafico.push(cores[i]);
  }

  var options = {
    chart: {
      height: 320,
      type: 'donut',
      dropShadow: {
        enabled: true,
        top: 10,
        left: 0,
        bottom: 0,
        right: 0,
        blur: 2,
        color: '#45404a2e',
        opacity: 0.35
      },
    },
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent']
    },
    series: array,
    legend: {
      show: true,
      position: 'bottom',
      horizontalAlign: 'center',
      verticalAlign: 'middle',
      floating: false,
      fontSize: '14px',
      offsetX: 0,
      offsetY: 6
    },
    labels: arrayNomeAdq,
    colors: cores,
    responsive: [{
      breakpoint: 600,
      options: {
        chart: {
          height: 240
        },
        legend: {
          show: false
        },
      }
    }],
    fill: {
      type: 'gradient'
    },
    tooltip: {
      y: {
        formatter: function(val) {

          dados_grafico.forEach((dado) => {
            if (dado.COD_PERIODO == periodo && dado.QUANTIDADE > 0 && dado.ADQUIRENTE == val ) {
              ticket_medio += parseFloat(dado.TICKET_MEDIO)
            }
          });

          ticket_medio_correto = ticket_medio / dados_grafico.length

          t = ticket_medio_correto.toFixed(2);

          return `${val}% | Ticket Médio: R$ ${t} `;

        }
      },
    }
  };

  var chart = new ApexCharts(document.querySelector("#apex_pie2"), options);
  grafico_vendas_operadora = chart;

  chart.render(options);
}

function geraGraficoVendasBandeira(dados_grafico) {
  array = [];
  arrayNomeAdq = [];
  let ticket_medio = null;


  dados_grafico.forEach((dado) => {
    if (dado.COD_PERIODO == periodo && dado.QUANTIDADE > 0) {
      dado.PERCENTUAL.toLocaleString(undefined, { maximumFractionDigits: 2, minimumFractionDigits: 2 });

      var percentualFloat = parseFloat(dado.PERCENTUAL);
      percentualFloat = percentualFloat.toFixed(1)
      array.push(parseFloat(percentualFloat));
      ticket_medio += parseFloat(dado.TICKET_MEDIO)

    }
  });

  dados_grafico.forEach((dado) => {
    if (dado.COD_PERIODO == periodo) {
      arrayNomeAdq.push(dado.BANDEIRA);
    }
  });

  cores = ["#119DA4", "#FFBC42", "#DA2C38", "#4CB944", "#FF8000", "#848484", "#00FFFF", "#086A87", "#FA58F4", "#7401DF", "#8181F7", "#D0A9F5"];
  coresGrafico = [];

  for (var i = 0; i < arrayNomeAdq.length; i++) {
    coresGrafico.push(cores[i]);
  }

  var options = {
    chart: {
      height: 320,
      type: 'donut',
      dropShadow: {
        enabled: true,
        top: 10,
        left: 0,
        bottom: 0,
        right: 0,
        blur: 2,
        color: '#45404a2e',
        opacity: 0.35
      },
    },
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent']
    },
    series: array,
    legend: {
      show: true,
      position: 'bottom',
      horizontalAlign: 'center',
      verticalAlign: 'middle',
      floating: false,
      fontSize: '14px',
      offsetX: 0,
      offsetY: 6
    },
    labels: arrayNomeAdq,
    colors: cores,
    responsive: [{
      breakpoint: 600,
      options: {
        chart: {
          height: 240
        },
        legend: {
          show: false
        },
      }
    }],
    fill: {
      type: 'gradient'
    },
    tooltip: {
      y: {
        formatter: function(val) {

          dados_grafico.forEach((dado) => {
            if (dado.COD_PERIODO == periodo && dado.QUANTIDADE > 0 && dado.BANDEIRA == val ) {
               ticket_medio += parseFloat(dado.TICKET_MEDIO)
            }
          });

          ticket_medio_correto = ticket_medio / dados_grafico.length

          t = ticket_medio_correto.toFixed(2);

          return `${val}% | Ticket Médio: R$ ${t} `;

        }
      },
    }
  };

  var chart = new ApexCharts(document.querySelector("#apex_pie7"), options);

  grafico_vendas_bandeira = chart;

  chart.render(options);
}

function geraGraficoVendasProduto(dados_grafico) {
  array = [];
  arrayNomeAdq = [];
  let ticket_medio = null;

  dados_grafico.forEach((dado) => {
    if (dado.COD_PERIODO == periodo && dado.QUANTIDADE > 0) {
      var percentualFloat = parseFloat(dado.PERCENTUAL);
      percentualFloat = percentualFloat.toFixed(1)
      array.push(parseFloat(percentualFloat));
      ticket_medio += parseFloat(dado.TICKET_MEDIO)
    }
    });

    dados_grafico.forEach((dado) => {
      if (dado.COD_PERIODO == periodo) {
        arrayNomeAdq.push(dado.PRODUTO_WEB);
      }
    });

    cores = ["#119DA4", "#FFBC42", "#DA2C38", "#4CB944", "#FF8000", "#848484", "#00FFFF", "#086A87", "#FA58F4", "#7401DF", "#8181F7", "#D0A9F5"];
    coresGrafico = [];

    for (var i = 0; i < arrayNomeAdq.length; i++) {
      coresGrafico.push(cores[i]);
    }

    var options = {
      chart: {
        height: 320,
        type: 'donut',
        dropShadow: {
          enabled: true,
          top: 10,
          left: 0,
          bottom: 0,
          right: 0,
          blur: 2,
          color: '#45404a2e',
          opacity: 0.35
        },
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      series: array,
      legend: {
        show: true,
        position: 'bottom',
        horizontalAlign: 'center',
        verticalAlign: 'middle',
        floating: false,
        fontSize: '14px',
        offsetX: 0,
        offsetY: 6
      },
      labels: arrayNomeAdq,
      colors: cores,
      responsive: [{
        breakpoint: 600,
        options: {
          chart: {
            height: 240
          },
          legend: {
            show: false
          },
        }
      }],
      fill: {
        type: 'gradient'
      },
      tooltip: {
        y: {
          formatter: function(val) {

            dados_grafico.forEach((dado) => {
              if (dado.COD_PERIODO == periodo && dado.QUANTIDADE > 0 && dado.PRODUTO_WEB == val ) {
                 ticket_medio += parseFloat(dado.TICKET_MEDIO)
              }
            });
            ticket_medio_correto = ticket_medio / dados_grafico.length

            t = ticket_medio_correto.toFixed(2);
            return `${val}% | Ticket Médio: R$ ${t} `;

          }
        },
      }
    };

    var chart = new ApexCharts(document.querySelector("#apex_pie9"), options);

    grafico_vendas = chart;

    chart.render(options);
  }

  function geraGraficoVendasModalidade(dados_grafico) {
    array = [];
    arrayNomeAdq = [];
    let ticket_medio = null;

    dados_grafico.forEach((dado) => {

      if (dado.COD_PERIODO == periodo && dado.QUANTIDADE > 0) {
        var percentualFloat = parseFloat(dado.PERCENTUAL);
        percentualFloat = percentualFloat.toFixed(1)
        array.push(parseFloat(percentualFloat));
        ticket_medio += parseFloat(dado.TICKET_MEDIO)

      }
    });

    dados_grafico.forEach((dado) => {
      if (dado.COD_PERIODO == periodo) {
        arrayNomeAdq.push(dado.DESCRICAO);
      }
    });



    cores = ["#119DA4", "#FFBC42", "#DA2C38", "#4CB944"];
    coresGrafico = [];

    for (var i = 0; i < arrayNomeAdq.length; i++) {
      coresGrafico.push(cores[i]);
    }

    var options = {
      chart: {
        height: 320,
        type: 'donut',
        dropShadow: {
          enabled: true,
          top: 10,
          left: 0,
          bottom: 0,
          right: 0,
          blur: 2,
          color: '#45404a2e',
          opacity: 0.35
        },
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      series: array,
      legend: {
        show: true,
        position: 'bottom',
        horizontalAlign: 'center',
        verticalAlign: 'middle',
        floating: false,
        fontSize: '14px',
        offsetX: 0,
        offsetY: 6
      },
      labels: arrayNomeAdq,
      colors: cores,
      responsive: [{
        breakpoint: 600,
        options: {
          chart: {
            height: 240
          },
          legend: {
            show: false
          },
        }
      }],
      fill: {
        type: 'gradient'
      },
      tooltip: {
        y: {
          formatter: function(val) {

            dados_grafico.forEach((dado) => {
              if (dado.COD_PERIODO == periodo && dado.QUANTIDADE > 0 && dado.DESCRICAO == val ) {
                 ticket_medio += parseFloat(dado.TICKET_MEDIO)
              }
            });
            ticket_medio_correto = ticket_medio / dados_grafico.length

            t = ticket_medio_correto.toFixed(2);
            return `${val}% | Ticket Médio: R$ ${t} `;

          }
        },
      }
    };


    var chart = new ApexCharts(document.querySelector("#apex_pie8"), options);

    grafico_vendas_modalidade = chart;

    chart.render(options);
  }
