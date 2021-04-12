/**
 * Theme: Metrica - Responsive Bootstrap 4 Admin Dashboard
 * Author: Mannatthemes
 * Ecommerce Dashboard Js
 */
function carregaGraficoRecebimentosFuturos(recebimentos) {
  "use strict";
  document.addEventListener("DOMContentLoaded", function(event) {
    let dados_grafico_recebimentos = [];
    let labels_grafico = [];
    let valores_grafico = [];
    let meses_ano_seguinte;
    let meses = {
      "0": [],
      "1": [],
      "2": [],
      "3": [],
      "4": [],
      "5": [],
      "6": [],
      "7": [],
      "8": [],
      "9": [],
      "10": [],
      "11": []
    };
    let total_meses = [
      { mes: "Jan", total: 0 },
      { mes: "Fev", total: 0 },
      { mes: "Mar", total: 0 },
      { mes: "Abr", total: 0 },
      { mes: "Mai", total: 0 },
      { mes: "Jun", total: 0 },
      { mes: "Jul", total: 0 },
      { mes: "Ago", total: 0 },
      { mes: "Set", total: 0 },
      { mes: "Out", total: 0 },
      { mes: "Nov", total: 0 },
      { mes: "Dez", total: 0 }
    ];

    const data = new Date();
    const ano = data.getFullYear();
    const mes_atual = data.getMonth();

    for (let recebimento of recebimentos) {
      if (
        recebimento.DATA_PREVISTA_PAGTO >= `${ano}-01-01` &&
        recebimento.DATA_PREVISTA_PAGTO <= `${ano}-01-01`
      ) {
        meses["0"].push(recebimento);
        total_meses[0].total += parseFloat(recebimento.val_liquido);
      } else if (
        recebimento.DATA_PREVISTA_PAGTO >= `${ano}-02-01` &&
        recebimento.DATA_PREVISTA_PAGTO <= `${ano}-02-28`
      ) {
        meses["1"].push(recebimento);
        total_meses[1].total += parseFloat(recebimento.val_liquido);
      } else if (
        recebimento.DATA_PREVISTA_PAGTO >= `${ano}-03-01` &&
        recebimento.DATA_PREVISTA_PAGTO <= `${ano}-03-30`
      ) {
        meses["2"].push(recebimento);
        total_meses[2].total += parseFloat(recebimento.val_liquido);
      } else if (
        recebimento.DATA_PREVISTA_PAGTO >= `${ano}-04-01` &&
        recebimento.DATA_PREVISTA_PAGTO <= `${ano}-04-30`
      ) {
        meses["3"].push(recebimento);
        total_meses[3].total += parseFloat(recebimento.val_liquido);
      } else if (
        recebimento.DATA_PREVISTA_PAGTO >= `${ano}-05-01` &&
        recebimento.DATA_PREVISTA_PAGTO <= `${ano}-05-30`
      ) {
        meses["4"].push(recebimento);
        total_meses[4].total += parseFloat(recebimento.val_liquido);
      } else if (
        recebimento.DATA_PREVISTA_PAGTO >= `${ano}-06-01` &&
        recebimento.DATA_PREVISTA_PAGTO <= `${ano}-06-30`
      ) {
        meses["5"].push(recebimento);
        total_meses[5].total += parseFloat(recebimento.val_liquido);
      } else if (
        recebimento.DATA_PREVISTA_PAGTO >= `${ano}-07-01` &&
        recebimento.DATA_PREVISTA_PAGTO <= `${ano}-07-30`
      ) {
        meses["6"].push(recebimento);
        total_meses[6].total += parseFloat(recebimento.val_liquido);
      } else if (
        recebimento.DATA_PREVISTA_PAGTO >= `${ano}-08-01` &&
        recebimento.DATA_PREVISTA_PAGTO <= `${ano}-08-30`
      ) {
        meses["7"].push(recebimento);
        total_meses[7].total += parseFloat(recebimento.val_liquido);
      } else if (
        recebimento.DATA_PREVISTA_PAGTO >= `${ano}-09-01` &&
        recebimento.DATA_PREVISTA_PAGTO <= `${ano}-09-30`
      ) {
        meses["8"].push(recebimento);
        total_meses[8].total += parseFloat(recebimento.val_liquido);
      } else if (
        recebimento.DATA_PREVISTA_PAGTO >= `${ano}-10-01` &&
        recebimento.DATA_PREVISTA_PAGTO <= `${ano}-10-30`
      ) {
        meses["9"].push(recebimento);
        total_meses[9].total += parseFloat(recebimento.val_liquido);
      } else if (
        recebimento.DATA_PREVISTA_PAGTO >= `${ano}-11-01` &&
        recebimento.DATA_PREVISTA_PAGTO <= `${ano}-11-30`
      ) {
        meses["10"].push(recebimento);
        total_meses[10].total += parseFloat(recebimento.val_liquido);
      } else if (
        recebimento.DATA_PREVISTA_PAGTO >= `${ano}-12-01` &&
        recebimento.DATA_PREVISTA_PAGTO <= `${ano}-12-30`
      ) {
        meses["11"].push(recebimento);
        total_meses[11].total += parseFloat(recebimento.val_liquido);
      }
    }

    for (let i = mes_atual; i < 12; i++) {
      if (i >= mes_atual) {
        dados_grafico_recebimentos.push(total_meses[i]);
        labels_grafico.push(total_meses[i].mes)
        valores_grafico.push(total_meses[i].total)
      }
    }

    for (let i = 0; i < mes_atual; i++) {
      dados_grafico_recebimentos.push(total_meses[i]);
      labels_grafico.push(total_meses[i].mes)
      valores_grafico.push(total_meses[i].total)
    }

    console.log(valores_grafico);
    var currentChartCanvas = document.querySelector("#bar");

    var currentChart = new Chart(currentChartCanvas, {
      type: "bar",
      data: {
        labels: labels_grafico,
        datasets: [
          {
            // label: "Revenue",
            backgroundColor: "#2D93AD",
            borderColor: "transparent",
            borderWidth: 2,
            categoryPercentage: 0.5,
            hoverBackgroundColor: "#136dd0",
            hoverBorderColor: "transparent",
            data: valores_grafico
          }
        ]
      },

      options: {
        responsive: true,
        maintainAspectRatio: true,
        legend: {
          display: false,
          labels: {
            fontColor: "#50649c"
          }
        },
        tooltips: {
          enabled: true,
          callbacks: {
            label: function(tooltipItems, data) {
              return (
                // data.datasets[tooltipItems.datasetIndex].label +
                // " R$ " +
                formataMoeda(tooltipItems.yLabel)
              );
            }
          }
        },

        scales: {
          xAxes: [
            {
              barPercentage: 0.35,
              categoryPercentage: 0.4,
              display: true,
              gridLines: {
                color: "transparent",
                borderDash: [0],
                zeroLineColor: "transparent",
                zeroLineBorderDash: [2],
                zeroLineBorderDashOffset: [2]
              },
              ticks: {
                fontColor: "#a4abc5",
                beginAtZero: true,
                padding: 12
              }
            }
          ],
          yAxes: [
            {
              gridLines: {
                color: "#8997bd29",
                borderDash: [3],
                drawBorder: false,
                drawTicks: false,
                zeroLineColor: "#8997bd29",
                zeroLineBorderDash: [2],
                zeroLineBorderDashOffset: [2]
              },
              ticks: {
                fontColor: "#a4abc5",
                beginAtZero: true,
                padding: 12,
                callback: function(value) {
                  if (!(value % 10)) {
                    return formataMoeda(value);
                  }
                }
              }
            }
          ]
        }
      }
    });
  });
}
