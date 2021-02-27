function formataData(data) {
  const data_formatada = data.toLocaleDateString("pt-BR", {
    timeZone: "UTC"
  });

  return data_formatada;
}

function formataMoeda(valor) {
  return Intl.NumberFormat("pt-br", {
    style: "currency",
    currency: "BRL"
  }).format(valor);
}
