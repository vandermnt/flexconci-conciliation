function formataMoeda(valor) {
  return Intl.NumberFormat("pt-br", {
    style: "currency",
    currency: "BRL"
  }).format(valor);
}

function geraTabela(tipo, nome_tipo, qdt, bruto, taxa, liq) {
  const taxaMedia = (taxa / bruto) * 100;

  let html = "<tr>";
  html += `<td>
  <div class='tooltip-hint' data-title=${nome_tipo}>
  <div class='img-tables' style='background-image: url(${tipo})'/> </div>
  </div>
  </td>`;
  html += `<td> ${qdt} </td>`;
  html += `<td> ${formataMoeda(bruto)} </td>`;
  html += `<td> ${this.toFixed(taxaMedia,2)} % </td>`;
  html += `<td> ${formataMoeda(taxa)} </td>`;
  html += `<td> ${formataMoeda(liq)} </td>`;
  html += `</tr>`;

  return html;
}

function geraTabelaSemImagem(tipo, qdt, bruto, taxa, liq) {
  const taxaMedia = (taxa / bruto) * 100;

  let html = "<tr>";
  html += `<td style='color: #231F20'>  ${tipo}  </td>`;
  html += `<td style='color: #231F20'>  ${qdt}  </td>`;
  html += `<td style='color: #231F20'>  ${formataMoeda(bruto)}  </td>`;
  html += `<td> ${this.toFixed(taxaMedia,2)} % </td>`;
  html += `<td style='color: red'>  ${formataMoeda(taxa)} </td>`;
  html += `<td style='color: #231F20'>  ${formataMoeda(liq)}  </td>`;
  html += `</tr>`;

  return html;
}

function geraRodapeTabelaComTotais(
  idTabela,
  totalQtd,
  totalBruto,
  totalTx,
  totalLiq,
  totalTxMedia
) {
  let htmlSubTotal = "<tr class='subtotal-dash'>";
  htmlSubTotal += `<td>  Total  </td>`;
  htmlSubTotal += `<td> ${totalQtd}  </td>`;
  htmlSubTotal += `<td> ${formataMoeda(totalBruto)}  </td>`;
  htmlSubTotal += `<td> ${this.toFixed(totalTxMedia,2)} % </td>`;
  htmlSubTotal += `<td> ${formataMoeda(totalTx)}  </td>`;
  htmlSubTotal += `<td> ${formataMoeda(totalLiq)} </td>`;
  htmlSubTotal += `</tr>`;

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
  html += "<td>" + "<b> Valor Total Bruto: </b>" + formataMoeda(val_bruto) + "</td>";
  html += "</tr>";
  html += "<tr>";
  html += "<td>" + "<b>Ajuste a Crédito: </b>" + "0,00" + "</td>";
  html += "</tr>";
  html += "<tr>";
  html += "<td>" + "<b> Custo Taxa: </b>" + formataMoeda(taxa_adm) + "</td>";
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
  html += `<td>
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

function toFixed(num, fixed) {
    var re = new RegExp('^-?\\d+(?:\.\\d{0,' + (fixed || -1) + '})?');
    return num.toString().match(re)[0].replace(".", ",");
}
