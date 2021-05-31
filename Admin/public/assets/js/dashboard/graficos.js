let periodo = null;
let grafico_vendas_operadoras;
let grafico_vendas_bandeiras = null;
let grafico_vendas_produtos = null;
let grafico_vendas_modalidades = null;
let dados_vendas_operadora;
let dados_vendas_bandeira;
let dados_vendas_forma_pagamento;
let dados_vendas_produto;

function preCarregarGraficoVendas(grafico_vendas_operadora) {
  let dados_grafico = [];
  let totalQtd = 0;
  let totalBruto = 0;
  let totalTx = 0;
  let totalTxMedia = 0;
  let totalLiq = 0;
  let html;

  grafico_vendas_operadoras = grafico_vendas_operadora;

  grafico_vendas_operadora.forEach(dados_dash => {
    if (dados_dash.COD_PERIODO == 2 && dados_dash.QUANTIDADE > 0) {
      dados_grafico.push(dados_dash);

      html = geraTabela(
        dados_dash.IMAGEM,
        dados_dash.ADQUIRENTE,
        dados_dash.QUANTIDADE_REAL,
        dados_dash.TOTAL_BRUTO,
        dados_dash.TOTAL_TAXA,
        dados_dash.TOTAL_LIQUIDO
      );

      totalTxMedia += (dados_dash.TOTAL_TAXA / dados_dash.TOTAL_BRUTO) * 100;
      totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
      totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
      totalTx += parseFloat(dados_dash.TOTAL_TAXA);
      totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);

      $("#table_vendas_operadora").append(html);
      document.getElementById("dropdownMenuButton").innerHTML =
        dados_dash.PERIODO + " " + '<i class="mdi mdi-chevron-down"></i>';
    }
  });

  dados_vendas_operadora = dados_grafico;

  geraRodapeTabelaComTotais(
    "#table_vendas_operadora tfoot",
    totalQtd,
    totalBruto,
    totalTx,
    totalLiq,
    totalTxMedia
  );

  periodo = 2;
  localStorage.setItem("periodo_venda_operadora", 2);
  geraGraficoVendas(dados_grafico, 1);
}

function preCarregarGraficoVendasBandeira(grafico_vendas_bandeira) {
  let dados_grafico = [];
  let totalQtd = 0;
  let totalBruto = 0;
  let totalTx = 0;
  let totalLiq = 0;
  let totalTicket = 0;
  let totalTxMedia = 0;
  let html;

  grafico_vendas_bandeiras = grafico_vendas_bandeira;

  grafico_vendas_bandeira.forEach(dados_dash => {
    if (dados_dash.COD_PERIODO == 2 && dados_dash.QUANTIDADE > 0) {
      dados_grafico.push(dados_dash);

      html = geraTabela(
        dados_dash.IMAGEM,
        dados_dash.BANDEIRA,
        dados_dash.QUANTIDADE_REAL,
        dados_dash.TOTAL_BRUTO,
        dados_dash.TOTAL_TAXA,
        dados_dash.TOTAL_LIQUIDO
      );

      totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
      totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
      totalTx += parseFloat(dados_dash.TOTAL_TAXA);
      totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
      totalTicket += parseFloat(dados_dash.TICKET_MEDIO);
      totalTxMedia += (dados_dash.TOTAL_TAXA / dados_dash.TOTAL_BRUTO) * 100;

      $("#table_vendas_bandeira").append(html);
      document.getElementById("dropdownMenuButtonBandeira").innerHTML =
        dados_dash.PERIODO + " " + '<i class="mdi mdi-chevron-down"></i>';
    }
  });

  dados_vendas_bandeira = dados_grafico;

  geraRodapeTabelaComTotais(
    "#table_vendas_bandeira tfoot",
    totalQtd,
    totalBruto,
    totalTx,
    totalLiq,
    totalTxMedia
  );

  periodo = 2;
  localStorage.setItem("periodo_venda_bandeira", 2);
  geraGraficoVendasBandeira(dados_grafico, 1);
}

function preCarregarGraficoVendasProduto(grafico_vendas_produto) {
  let dados_grafico = [];
  let totalQtd = 0;
  let totalBruto = 0;
  let totalTx = 0;
  let totalLiq = 0;
  let totalTicket = 0;
  let totalTxMedia = 0;

  grafico_vendas_produtos = grafico_vendas_produto;

  grafico_vendas_produto.forEach(dados_dash => {
    if (dados_dash.COD_PERIODO == 2 && dados_dash.QUANTIDADE > 0) {
      dados_grafico.push(dados_dash);

      html = geraTabelaSemImagem(
        dados_dash.PRODUTO_WEB,
        dados_dash.QUANTIDADE_REAL,
        dados_dash.TOTAL_BRUTO,
        dados_dash.TOTAL_TAXA,
        dados_dash.TOTAL_LIQUIDO
      );

      totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
      totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
      totalTx += parseFloat(dados_dash.TOTAL_TAXA);
      totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
      totalTicket += parseFloat(dados_dash.TICKET_MEDIO);
      totalTxMedia += (dados_dash.TOTAL_TAXA / dados_dash.TOTAL_BRUTO) * 100;

      $("#table_vendas_produto").append(html);

      document.getElementById("dropdownMenuButtonProduto").innerHTML =
        dados_dash.PERIODO + " " + '<i class="mdi mdi-chevron-down"></i>';
    }
  });

  dados_vendas_produto = dados_grafico;

  geraRodapeTabelaComTotais(
    "#table_vendas_produto tfoot",
    totalQtd,
    totalBruto,
    totalTx,
    totalLiq,
    totalTxMedia
  );

  periodo = 2;
  localStorage.setItem("periodo_venda_produto", 2);
  geraGraficoVendasProduto(dados_grafico, 1);
}

function preCarregarGraficoVendasModalidade(grafico_vendas_forma_pagamento) {
  let dados_grafico = [];
  let totalQtd = 0;
  let totalBruto = 0;
  let totalTx = 0;
  let totalLiq = 0;
  let totalTicket = 0;
  let totalTxMedia = 0;

  grafico_vendas_forma_pagamentos = grafico_vendas_forma_pagamento;

  $("#table_vendas_modalidade tbody").empty();

  grafico_vendas_forma_pagamento.forEach(dados_dash => {
    if (dados_dash.COD_PERIODO == 2 && dados_dash.QUANTIDADE > 0) {
      html = geraTabelaSemImagem(
        dados_dash.DESCRICAO,
        dados_dash.QUANTIDADE_REAL,
        dados_dash.TOTAL_BRUTO,
        dados_dash.TOTAL_TAXA,
        dados_dash.TOTAL_LIQUIDO
      );

      $("#table_vendas_modalidade").append(html);

      totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
      totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
      totalTx += parseFloat(dados_dash.TOTAL_TAXA);
      totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
      totalTicket += parseFloat(dados_dash.TICKET_MEDIO);
      totalTxMedia += (dados_dash.TOTAL_TAXA / dados_dash.TOTAL_BRUTO) * 100;

      dados_grafico.push(dados_dash);
      document.getElementById("dropdownMenuButtonModalidade").innerHTML =
        dados_dash.PERIODO + " " + '<i class="mdi mdi-chevron-down"></i>';
    }
  });

  dados_vendas_forma_pagamento = dados_grafico;

  geraRodapeTabelaComTotais(
    "#table_vendas_modalidade tfoot",
    totalQtd,
    totalBruto,
    totalTx,
    totalLiq,
    totalTxMedia
  );

  periodo = 2;
  localStorage.setItem("periodo_venda_modalidade", 2);
  geraGraficoVendasModalidade(dados_grafico);
}

function trocaPeriodo(cod_periodo, tipo, label_button) {
  let dados_grafico = [];
  let totalQtd = 0;
  let totalBruto = 0;
  let totalTx = 0;
  let totalLiq = 0;
  let totalTicket = 0;
  let totalTxMedia = 0;

  if (tipo == "operadora") {
    $("#table_vendas_operadora tbody").empty();
    $("#table_vendas_operadora tfoot").empty();

    grafico_vendas_operadoras.forEach(dados_dash => {
      if (dados_dash.COD_PERIODO == cod_periodo && dados_dash.QUANTIDADE > 0) {
        html = geraTabela(
          dados_dash.IMAGEM,
          dados_dash.ADQUIRENTE,
          dados_dash.QUANTIDADE_REAL,
          dados_dash.TOTAL_BRUTO,
          dados_dash.TOTAL_TAXA,
          dados_dash.TOTAL_LIQUIDO
        );

        totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
        totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
        totalTx += parseFloat(dados_dash.TOTAL_TAXA);
        totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
        totalTxMedia += (dados_dash.TOTAL_TAXA / dados_dash.TOTAL_BRUTO) * 100;

        $("#table_vendas_operadora").append(html);
        document.getElementById("dropdownMenuButton").innerHTML =
          dados_dash.PERIODO + " " + '<i class="mdi mdi-chevron-down"></i>';
        dados_grafico.push(dados_dash);
      }
    });

    dados_vendas_operadora = dados_grafico;

    geraRodapeTabelaComTotais(
      "#table_vendas_operadora tfoot",
      totalQtd,
      totalBruto,
      totalTx,
      totalLiq,
      totalTxMedia
    );
    document.getElementById("dropdownMenuButton").innerHTML =
      label_button + " " + '<i class="mdi mdi-chevron-down"></i>';

    if (dados_grafico.length == 0) {
      grafico_vendas_operadora.destroy();
      document.getElementById("label_sem_dados_vop").style.display = "block";
      const div = document.querySelector(".vendasop");
      div.style.display = "none";
      document.querySelector(".bt-vendas-op").style.visibility = "hidden";
    } else {
      grafico_vendas_operadora.destroy();
      const div = document.querySelector(".vendasop");
      div.style.display = "block";
      document.getElementById("label_sem_dados_vop").style.display = "none";
      document.querySelector(".bt-vendas-op").style.visibility = "visible";

      // document.getElementById("table_vendas_operadora").style.display = "block"

      periodo = cod_periodo;
      localStorage.setItem("periodo_venda_operadora", cod_periodo);
      geraGraficoVendas(dados_grafico);
    }
  } else if (tipo == "bandeira") {
    $("#table_vendas_bandeira tbody").empty();
    $("#table_vendas_bandeira tfoot").empty();

    grafico_vendas_bandeiras.forEach(dados_dash => {
      if (dados_dash.COD_PERIODO == cod_periodo && dados_dash.QUANTIDADE > 0) {
        html = geraTabela(
          dados_dash.IMAGEM,
          dados_dash.BANDEIRA,
          dados_dash.QUANTIDADE_REAL,
          dados_dash.TOTAL_BRUTO,
          dados_dash.TOTAL_TAXA,
          dados_dash.TOTAL_LIQUIDO
        );

        $("#table_vendas_bandeira").append(html);

        totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
        totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
        totalTx += parseFloat(dados_dash.TOTAL_TAXA);
        totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
        totalTicket += parseFloat(dados_dash.TICKET_MEDIO);
        totalTxMedia += (dados_dash.TOTAL_TAXA / dados_dash.TOTAL_BRUTO) * 100;

        dados_grafico.push(dados_dash);

        document.getElementById("dropdownMenuButtonBandeira").innerHTML =
          dados_dash.PERIODO + " " + '<i class="mdi mdi-chevron-down"></i>';
      }
    });

    dados_vendas_bandeira = dados_grafico;

    geraRodapeTabelaComTotais(
      "#table_vendas_bandeira tfoot",
      totalQtd,
      totalBruto,
      totalTx,
      totalLiq,
      totalTxMedia
    );
    document.getElementById("dropdownMenuButtonBandeira").innerHTML =
      label_button + " " + '<i class="mdi mdi-chevron-down"></i>';

    if (dados_grafico.length == 0) {
      grafico_vendas_bandeira.destroy();
      document.getElementById("label_sem_dados_vb").style.display = "block";
      const div = document.querySelector(".vendasband");
      div.style.display = "none";
      document.querySelector(".bt-vendas-band").style.visibility = "hidden";
    } else {
      grafico_vendas_bandeira.destroy();
      document.getElementById("label_sem_dados_vb").style.display = "none";
      const div = document.querySelector(".vendasband");
      div.style.display = "block";
      document.querySelector(".bt-vendas-band").style.visibility = "visible";

      periodo = cod_periodo;
      localStorage.setItem("periodo_venda_bandeira", 2);
      geraGraficoVendasBandeira(dados_grafico);
    }
  } else if (tipo == "modalidade") {
    $("#table_vendas_modalidade tbody").empty();
    $("#table_vendas_modalidade tfoot").empty();

    grafico_vendas_forma_pagamentos.forEach(dados_dash => {
      if (dados_dash.COD_PERIODO == cod_periodo && dados_dash.QUANTIDADE > 0) {
        html = geraTabelaSemImagem(
          dados_dash.DESCRICAO,
          dados_dash.QUANTIDADE_REAL,
          dados_dash.TOTAL_BRUTO,
          dados_dash.TOTAL_TAXA,
          dados_dash.TOTAL_LIQUIDO
        );

        $("#table_vendas_modalidade").append(html);

        totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
        totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
        totalTx += parseFloat(dados_dash.TOTAL_TAXA);
        totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
        totalTicket += parseFloat(dados_dash.TICKET_MEDIO);
        totalTxMedia += (dados_dash.TOTAL_TAXA / dados_dash.TOTAL_BRUTO) * 100;

        dados_grafico.push(dados_dash);
        document.getElementById("dropdownMenuButtonModalidade").innerHTML =
          dados_dash.PERIODO + " " + '<i class="mdi mdi-chevron-down"></i>';
      }
    });

    dados_vendas_forma_pagamento = dados_grafico;

    geraRodapeTabelaComTotais(
      "#table_vendas_modalidade tfoot",
      totalQtd,
      totalBruto,
      totalTx,
      totalLiq,
      totalTxMedia
    );
    document.getElementById("dropdownMenuButtonModalidade").innerHTML =
      label_button + " " + '<i class="mdi mdi-chevron-down"></i>';

    if (dados_grafico.length == 0) {
      grafico_vendas_modalidade.destroy();
      document.getElementById("label_sem_dados_vmod").style.display = "block";
      const div = document.querySelector(".vendasmod");
      div.style.display = "none";
      document.querySelector(".bt-vendas-formpg").style.visibility = "hidden";
    } else {
      grafico_vendas_modalidade.destroy();

      document.getElementById("label_sem_dados_vmod").style.display = "none";
      const div = document.querySelector(".vendasmod");
      div.style.display = "block";
      document.querySelector(".bt-vendas-formpg").style.visibility = "visible";

      periodo = cod_periodo;
      localStorage.setItem("periodo_venda_modalidade", cod_periodo);
      geraGraficoVendasModalidade(dados_grafico);
    }
  } else if (tipo == "produto") {
    $("#table_vendas_produto tbody").empty();
    $("#table_vendas_produto tfoot").empty();

    grafico_vendas_produtos.forEach(dados_dash => {
      if (dados_dash.COD_PERIODO == cod_periodo && dados_dash.QUANTIDADE > 0) {
        html = geraTabelaSemImagem(
          dados_dash.PRODUTO_WEB,
          dados_dash.QUANTIDADE_REAL,
          dados_dash.TOTAL_BRUTO,
          dados_dash.TOTAL_TAXA,
          dados_dash.TOTAL_LIQUIDO
        );

        $("#table_vendas_produto").append(html);

        totalQtd += parseInt(dados_dash.QUANTIDADE_REAL);
        totalBruto += parseFloat(dados_dash.TOTAL_BRUTO);
        totalTx += parseFloat(dados_dash.TOTAL_TAXA);
        totalLiq += parseFloat(dados_dash.TOTAL_LIQUIDO);
        totalTicket += parseFloat(dados_dash.TICKET_MEDIO);
        totalTxMedia += (dados_dash.TOTAL_TAXA / dados_dash.TOTAL_BRUTO) * 100;

        dados_grafico.push(dados_dash);

        document.getElementById("dropdownMenuButtonProduto").innerHTML =
          dados_dash.PERIODO + " " + '<i class="mdi mdi-chevron-down"></i>';
      }
    });

    dados_vendas_produto = dados_grafico;

    geraRodapeTabelaComTotais(
      "#table_vendas_produto tfoot",
      totalQtd,
      totalBruto,
      totalTx,
      totalLiq,
      totalTxMedia
    );
    document.getElementById("dropdownMenuButtonProduto").innerHTML =
      label_button + " " + '<i class="mdi mdi-chevron-down"></i>';

    if (dados_grafico.length == 0) {
      grafico_vendas_produto.destroy();

      document.getElementById("label_sem_dados_vprod").style.display = "block";
      const div = document.querySelector(".vendasprod");
      div.style.display = "none";
      document.querySelector(".bt-vendas-prod").style.visibility = "hidden";
    } else {
      grafico_vendas_produto.destroy();

      document.getElementById("label_sem_dados_vprod").style.display = "none";
      const div = document.querySelector(".vendasprod");
      div.style.display = "block";
      document.querySelector(".bt-vendas-prod").style.visibility = "visible";

      periodo = cod_periodo;
      localStorage.setItem("periodo_venda_produto", cod_periodo);
      geraGraficoVendasProduto(dados_grafico);
    }
  }
}
