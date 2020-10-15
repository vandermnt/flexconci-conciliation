function geraGraficoVendas(dados_grafico) {
  array = [];
  arrayNomeAdq = [];

  dados_grafico.forEach((dado) => {
    if (dado.COD_PERIODO == periodo && dado.QUANTIDADE > 0) {
      var percentualFloat = parseFloat(dado.PERCENTUAL);
      array.push(percentualFloat);
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
    }
  };

  var chart = new ApexCharts(document.querySelector("#apex_pie2"), options);

  grafico_vendas = chart;

  chart.render(options);
}

function geraGraficoVendasBandeira(dados_grafico) {
  array = [];
  arrayNomeAdq = [];

  dados_grafico.forEach((dado) => {
    if (dado.COD_PERIODO == periodo && dado.QUANTIDADE > 0) {
      var percentualFloat = parseFloat(dado.PERCENTUAL);
      array.push(percentualFloat);
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
    }
  };

  var chart = new ApexCharts(document.querySelector("#apex_pie7"), options);

  grafico_vendas = chart;

  chart.render(options);
}

function geraGraficoVendasProduto(dados_grafico) {
  array = [];
  arrayNomeAdq = [];

  dados_grafico.forEach((dado) => {
    if (dado.COD_PERIODO == periodo && dado.QUANTIDADE > 0) {
      array.push(parseInt(dado.PERCENTUAL));
    }
  });

  dados_grafico.forEach((dado) => {
    if (dado.COD_PERIODO == periodo) {
      arrayNomeAdq.push(dado.DESCRICAO);
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
    series: [50,47,14,58,22,69,45,78,10, 78, 45, 45, 47, 78, 12],
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
    labels: ["teste", "teste", "teste", "teste","teste","teste","teste","teste","teste", "teste","teste","teste","teste","teste",],
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
    }
  };

  var chart = new ApexCharts(document.querySelector("#apex_pie9"), options);

  grafico_vendas = chart;

  chart.render(options);
}

function geraGraficoVendasModalidade(dados_grafico) {
  array = [];
  arrayNomeAdq = [];

  dados_grafico.forEach((dado) => {
    console.log(dado)
    console.log(periodo)


    if (dado.COD_PERIODO == periodo && dado.QUANTIDADE > 0) {
      var percentualFloat = parseFloat(dado.PERCENTUAL);
      array.push(percentualFloat);    }
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
    }
  };


  var chart = new ApexCharts(document.querySelector("#apex_pie8"), options);

  grafico_vendas = chart;

  chart.render(options);
}


function trocaAgrupamento(grupo) {
  dados_grafico = [];

  // periodo = "{{ Session::get(periodo) }}";

  if (grupo == 1) {
    dash_vendas = dados_dash_vendas;

    $('#table_vendas tbody').empty();

    dash_vendas.forEach((dados_dash) => {
      if (dados_dash.COD_PERIODO == periodo && dados_dash.QUANTIDADE > 0) {
        total_bruto = parseInt(dados_dash.TOTAL_BRUTO);
        total_liquido = parseInt(dados_dash.TOTAL_LIQUIDO);
        total_taxa = parseInt(dados_dash.TOTAL_TAXA);
        total_ticket_medio = parseInt(dados_dash.TICKET_MEDIO);
        qtde = parseInt(dados_dash.QUANTIDADE);

        var html = "<tr>";
        console.log(dados_dash)
        html += "<td>"+dados_dash.IMAGEM+"</td>";
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

    $('#table_vendas_bandeira tbody').empty();

    dash_vendas_bandeira.forEach((dados_dash) => {
      if (dados_dash.COD_PERIODO == periodo && dados_dash.QUANTIDADE > 0) {
        total_bruto = parseInt(dados_dash.TOTAL_BRUTO);
        total_liquido = parseInt(dados_dash.TOTAL_LIQUIDO);
        total_taxa = parseInt(dados_dash.TOTAL_TAXA);
        total_ticket_medio = parseInt(dados_dash.TICKET_MEDIO);
        qtde = parseInt(dados_dash.QUANTIDADE);

        var html = "<tr>";

        html += "<td>"+dados_dash.IMAGEM+"</td>";
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
      if (dados_dash.COD_PERIODO == periodo && dados_dash.QUANTIDADE > 0) {
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
