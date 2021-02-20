function gerarPdfVendasOperadora() {
  window.location = `export-vendasoperadora/${localStorage.getItem(
    "periodo_venda_operadora"
  )}`;
}

function gerarPdfVendasBandeira() {
  window.location = `export-vendasbandeira/${localStorage.getItem(
    "periodo_venda_bandeira"
  )}`;
}

function gerarPdfVendasModalidade() {
  window.location = `export-vendasmodalidade/${localStorage.getItem(
    "periodo_venda_modalidade"
  )}`;
}

function gerarPdfVendasProduto() {
  window.location = `export-vendasproduto/${localStorage.getItem(
    "periodo_venda_produto"
  )}`;
}
