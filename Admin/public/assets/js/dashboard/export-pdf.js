function gerarPdfVendasOperadora() {
  window.location.href = `{{ url('export-vendasoperadora')}}/${localStorage.getItem(
    "  periodo_venda_operadora"
  )}`;
}
function gerarPdfVendasBandeira() {
  window.location.href = `{{ url('export-vendasbandeira')}}/${localStorage.getItem(
    "periodo_venda_bandeira"
  )}`;
}
function gerarPdfVendasModalidade() {
  window.location.href = `{{ url('export-vendasmodalidade')}}/${localStorage.getItem(
    "periodo_venda_modalidade"
  )}`;
}
function gerarPdfVendasProduto() {
  window.location.href = `{{ url('export-vendasproduto')}}/${localStorage.getItem(
    "periodo_venda_produto"
  )}`;
}
