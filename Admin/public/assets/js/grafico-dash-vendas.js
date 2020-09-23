function geraGraficoVendas(dados_grafico, grupo) {
  array = [];
  arrayNomeAdq = [];

  if (grupo == 1) {
    dados_grafico.forEach((dado) => {
      if (dado.COD_PERIODO == periodo) {
        array.push(parseInt(dado.PERCENTUAL));
      }
    });

    dados_grafico.forEach((dado) => {
      if (dado.COD_PERIODO == periodo) {
        arrayNomeAdq.push(dado.ADQUIRENTE);
      }
    });
  } else if (grupo == 2) {
    dados_grafico.forEach((dado) => {
      if (dado.COD_PERIODO == periodo) {
        array.push(parseInt(dado.PERCENTUAL));
      }
    });

    dados_grafico.forEach((dado) => {
      if (dado.COD_PERIODO == periodo) {
        arrayNomeAdq.push(dado.BANDEIRA);
      }
    });
  } else if (grupo == 3) {
    dados_grafico.forEach((dado) => {
      if (dado.COD_PERIODO == periodo) {
        array.push(parseInt(dado.PERCENTUAL));
      }
    });

    dados_grafico.forEach((dado) => {
      if (dado.COD_PERIODO == periodo) {
        arrayNomeAdq.push(dado.DESCRICAO);
      }
    });
  } else if (grupo == 4) {
  }

  cores = ["#119DA4", "#FFBC42", "#DA2C38", "#4CB944"];
  coresGrafico = [];

  for (var i = 0; i < arrayNomeAdq.length; i++) {
    coresGrafico.push(cores[i]);
  }

  var options = {
    chart: {
      height: 270,
      type: "donut",
    },
    plotOptions: {
      pie: {
        donut: {
          size: "85%",
        },
      },
    },
    dataLabels: {
      enabled: false,
    },

    stroke: {
      show: true,
      width: 2,
      colors: ["transparent"],
    },

    series: array,
    legend: {
      show: true,
      position: "bottom",
      horizontalAlign: "center",
      verticalAlign: "middle",
      floating: false,
      fontSize: "14px",
      offsetX: 0,
      offsetY: 5,
    },
    labels: arrayNomeAdq,
    colors: cores,

    responsive: [
      {
        breakpoint: 600,
        options: {
          plotOptions: {
            donut: {
              customScale: 0.2,
            },
          },
          chart: {
            height: 240,
          },
          legend: {
            show: false,
          },
        },
      },
    ],

    tooltip: {
      y: {
        formatter: function (val) {
          return val + " %";
        },
      },
    },
  };

  var chart = new ApexCharts(document.querySelector("#ana_dash_1"), options);

  grafico_vendas = chart;

  chart.render(options);
}

var options = {
  chart: {
    height: 270,
    type: "donut",
  },
  plotOptions: {
    pie: {
      donut: {
        size: "85%",
      },
    },
  },
  dataLabels: {
    enabled: false,
  },

  stroke: {
    show: true,
    width: 2,
    colors: ["transparent"],
  },

  series: [90, 10],
  legend: {
    show: true,
    position: "bottom",
    horizontalAlign: "center",
    verticalAlign: "middle",
    floating: false,
    fontSize: "14px",
    offsetX: 0,
    offsetY: 5,
  },
  labels: ["Concluído", "Pendente"],
  colors: ["#ff9f43", "#506ee4"],

  responsive: [
    {
      breakpoint: 600,
      options: {
        plotOptions: {
          donut: {
            customScale: 0.2,
          },
        },
        chart: {
          height: 240,
        },
        legend: {
          show: false,
        },
      },
    },
  ],

  tooltip: {
    y: {
      formatter: function (val) {
        return val + " %";
      },
    },
  },
};

var charte = new ApexCharts(document.querySelector("#ana_devicee"), options);

charte.render();

function trocaAgrupamento(grupo) {
  dados_grafico = [];

  // periodo = "{{ Session::get(periodo) }}";

  if (grupo == 1) {
    dash_vendas = dados_dash_vendas;

    $('#table_vendas tbody').empty();

    dash_vendas.forEach((dados_dash) => {
      if (dados_dash.COD_PERIODO == periodo) {
        total_bruto = parseInt(dados_dash.TOTAL_BRUTO);
        total_liquido = parseInt(dados_dash.TOTAL_LIQUIDO);
        total_taxa = parseInt(dados_dash.TOTAL_TAXA);
        total_ticket_medio = parseInt(dados_dash.TICKET_MEDIO);
        qtde = parseInt(dados_dash.QUANTIDADE);

        var html = "<tr>";

        html += "<td>"+dados_dash.ADQUIRENTE+"</td>";
        html += "<td>"+dados_dash.QUANTIDADE+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_bruto)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_taxa)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_liquido)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_ticket_medio)+"</td>";

        html += "</tr>";

        $('#table_vendas').append(html);

        dados_grafico.push(dados_dash);

        document.getElementById("dropdownMenuButton").innerHTML =
        dados_dash.PERIODO + " " + '<i class="mdi mdi-chevron-down"></i>';
      }
    });

    if (dados_grafico.length == 0) {
      console.log("VAZIOOOOOOOOOOOO");
    } else {
      grafico_vendas.destroy();

      document.getElementById("th_tipo").innerHTML = "Operadora";
      document.getElementById("dropdownMenuButtonAgrupamento").innerHTML =
      "Operadora" + " " + '<i class="mdi mdi-chevron-down"></i>';

      geraGraficoVendas(dados_grafico, 1);
    }
  } else if (grupo == 2) {
    dash_vendas_bandeira = dados_dash_vendas_bandeira;

    $('#table_vendas tbody').empty();

    dash_vendas_bandeira.forEach((dados_dash) => {
      if (dados_dash.COD_PERIODO == periodo) {
        total_bruto = parseInt(dados_dash.TOTAL_BRUTO);
        total_liquido = parseInt(dados_dash.TOTAL_LIQUIDO);
        total_taxa = parseInt(dados_dash.TOTAL_TAXA);
        total_ticket_medio = parseInt(dados_dash.TICKET_MEDIO);
        qtde = parseInt(dados_dash.QUANTIDADE);

        var html = "<tr>";

        html += "<td>"+dados_dash.BANDEIRA+"</td>";
        html += "<td>"+dados_dash.QUANTIDADE+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_bruto)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_taxa)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_liquido)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_ticket_medio)+"</td>";

        html += "</tr>";

        $('#table_vendas').append(html);

        dados_grafico.push(dados_dash);

        document.getElementById("dropdownMenuButton").innerHTML =
        dados_dash.PERIODO + " " + '<i class="mdi mdi-chevron-down"></i>';
      }
    });

    if (dados_grafico.length == 0) {
      console.log("VAZIOOOOOOOOOOOO");
    } else {
      grafico_vendas.destroy();

      document.getElementById("th_tipo").innerHTML = "Bandeira";
      document.getElementById("dropdownMenuButtonAgrupamento").innerHTML =
      "Bandeira" + " " + '<i class="mdi mdi-chevron-down"></i>';

      geraGraficoVendas(dados_grafico, 2);
    }
  } else if (grupo == 3) {
    dash_vendas = dados_dash_vendas_modalidade;

    dash_vendas.forEach((dados_dash) => {
      if (dados_dash.COD_PERIODO == periodo) {
        total_bruto = parseInt(dados_dash.TOTAL_BRUTO);
        total_liquido = parseInt(dados_dash.TOTAL_LIQUIDO);
        total_taxa = parseInt(dados_dash.TOTAL_TAXA);
        total_ticket_medio = parseInt(dados_dash.TICKET_MEDIO);
        qtde = parseInt(dados_dash.QUANTIDADE);

        var html = "<tr>";

        html += "<td>"+dados_dash.MODALIDADE_FIXA+"</td>";
        html += "<td>"+dados_dash.QUANTIDADE+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_bruto)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_taxa)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency',currency: 'BRL'}).format(total_liquido)+"</td>";
        html += "<td>"+Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(total_ticket_medio)+"</td>";

        html += "</tr>";

        $('#table_vendas').append(html);

        dados_grafico.push(dados_dash);

        document.getElementById("dropdownMenuButtonAgrupamento").innerHTML =
        "Modalidade" + " " + '<i class="mdi mdi-chevron-down"></i>';
      }
    });

    if (dados_grafico.length == 0) {
      console.log("VAZIOOOOOOOOOOOO");
    } else {
      grafico_vendas.destroy();

      document.getElementById("th_tipo").innerHTML = "Modalidade";
      document.getElementById("dropdownMenuButtonAgrupamento").innerHTML =
      "Modalidade" + " " + '<i class="mdi mdi-chevron-down"></i>';

      geraGraficoVendas(dados_grafico, 3);
    }
  } else if (grupo == 4) {
  }
}
