function formataMoeda(valor) {
  return Intl.NumberFormat("pt-br", {
    style: "currency",
    currency: "BRL"
  }).format(valor);
}

function geraTabela(tipo, nome_tipo, qdt, bruto, taxa, liq) {
  let html = "<tr>";
  html += `<td>
  <div class='tooltip-hint' data-title=${nome_tipo}>
  <div class='img-tables' style='background-image: url(${tipo})'/> </div>
  </div>
  </td>`;
  html += "<td>" + qdt + "</td>";
  html += "<td>" + formataMoeda(bruto) + "</td>";
  html += "<td>" + formataMoeda(taxa) + "</td>";
  html += "<td>" + formataMoeda(liq) + "</td>";
  html += "</tr>";

  return html;
}

function geraTabelaSemImagem(tipo, qdt, bruto, taxa, liq) {
  let html = "<tr>";
  html += "<td style='color: #231F20'>" + tipo + "</td>";
  html += "<td style='color: #231F20'>" + qdt + "</td>";
  html += "<td style='color: #231F20'>" + formataMoeda(bruto) + "</td>";
  html += "<td style='color: red'>" + formataMoeda(taxa) + "</td>";
  html += "<td style='color: #231F20'>" + formataMoeda(liq) + "</td>";
  html += "</tr>";

  return html;
}

function geraRodapeTabelaComTotais(
  idTabela,
  totalQtd,
  totalBruto,
  totalTx,
  totalLiq
) {
  let htmlSubTotal = "<tr class='subtotal-dash'>";
  htmlSubTotal += "<td>" + "Total" + "</td>";
  htmlSubTotal += "<td>" + totalQtd + "</td>";
  htmlSubTotal += "<td>" + formataMoeda(totalBruto) + "</td>";
  htmlSubTotal += "<td>" + formataMoeda(totalTx) + "</td>";
  htmlSubTotal += "<td>" + formataMoeda(totalLiq) + "</td>";
  htmlSubTotal += "</tr>";

  $(idTabela).append(htmlSubTotal);
}

function geraTabelaDetalhamentoCalendario(
  idTabela,
  val_bruto,
  val_liquido,
  taxa_adm,
  pgto_normal,
  pgto_antecipado
) {
  let html = "<tr>";
  html += "<td>" + "<b> Bruto: </b>" + formataMoeda(val_bruto) + "</td>";
  html += "</tr>";
  html += "<tr>";
  html += "<td>" + "<b>Ajuste a Crédito: </b>" + "0,00" + "</td>";
  html += "</tr>";
  html += "<tr>";
  html += "<td>" + "<b> Taxa Adm.: </b>" + formataMoeda(taxa_adm) + "</td>";
  html += "</tr>";
  html += "<tr>";
  html += "<td>" + "<b> Custo Antecipação: </b>" + "R$ 0,00" + "</td>";
  html += "</tr>";
  html += "<tr>";
  html += "<td>" + "<b> Cancelamento: </b>" + "R$ 0,00" + "</td>";
  html += "</tr>";
  html += "<tr>";
  html += "<td>" + "<b> Chargeback: </b>" + "R$ 0,00" + "</td>";
  html += "</tr>";
  html += "<tr>";
  html += "<td>" + "<b> Ajuste a débito: </b>" + "R$ 0,00" + "</td>";
  html += "</tr>";
  html += "<tr>";
  html +=
    "<td>" + "<b>Valor Líquido: </b>" + formataMoeda(val_liquido) + "</td>";
  html += "</tr>";
  html += "<tr>";
  html += `<td style='background: #BDBDBD '>
    <b>Situação de Pagamento:
    ${
      localStorage.getItem("situacao_pgto") == "Depositado"
        ? "<b style='color: #257E4A'> Depositado </b>"
        : "<b style='color: #2D93AD'> Previsto </b>"
    }
    </td>`;
  html += "</tr>";

  $(idTabela).append(html);
}
