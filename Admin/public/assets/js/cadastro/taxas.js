document.querySelector(".success-save-tx").style.display = "none";
document.querySelector(".success-update-tx").style.display = "none";

const buttonCadastroTaxa = document.querySelector(".bt-cadastro-tx");
const buttonSalvaCadastro = document.querySelector(".bt-salva-tx");
const buttonSalvaEdicao = document.querySelector(".bt-salva-edicao-tx");
const buttonSimExclusao = document.querySelector("#sim");
const taxasPesquisados = document.querySelector(
  'input[name="taxas-pesquisados"]'
);
const taxasCarregadas = document.querySelector(
  'input[name="taxas-carregadas"]'
);

buttonSalvaCadastro.addEventListener("click", function() {
  const taxa = document.querySelector('input[name="taxa"]').value;
  const forma_pagamento = document.querySelector(
    'select[name="forma_pagamento"]'
  ).value;
  const cliente = document.querySelector('select[name="cliente"]').value;
  const bandeira = document.querySelector('select[name="bandeira"]').value;
  const operadora = document.querySelector('select[name="operadora"]').value;
  const data_vigencia = document.querySelector('input[name="data_vigencia"]')
    .value;

  const payload = {
    taxa,
    forma_pagamento,
    cliente,
    bandeira,
    data_vigencia,
    operadora
  };

  cadastrarTaxa(payload);
});

buttonSalvaEdicao.addEventListener("click", function() {
  const taxa = document.querySelector('input[name="editar-taxa"]').value;
  salvarEdicaoTaxa(taxa);
});

buttonCadastroTaxa.addEventListener("click", function() {
  $("#modalCadastroTaxa").modal({
    show: true
  });
});

taxasPesquisados.addEventListener("keydown", function() {
  const taxas = JSON.parse(taxasCarregadas.value);

  setTimeout(function() {
    if (taxasPesquisados.value == "") {
      for (taxa of taxas) {
        document.getElementById(taxa.CODIGO).style = "display: ";
      }
    } else {
      for (taxa of taxas) {
        var regex = new RegExp(taxasPesquisados.value, "gi");
        resultado = taxa.TAXA.match(regex);

        if (resultado) {
          document.getElementById(taxa.CODIGO).style = "display: ";
        } else {
          document.getElementById(taxa.CODIGO).style.display = "none";
        }
      }
    }
  }, 300);
});

function editarTaxa(e, taxa) {
  console.log(taxa);
  localStorage.setItem("cod_taxa", taxa.CODIGO);

  $("#modalEditarAdquirente").modal({
    show: true
  });
  document.querySelector("input[name='editar-taxa']").value = `${taxa.TAXA}`;
}

function salvarEdicaoTaxa(taxa) {
  const token = document.getElementById("token").value;
  const data = { taxa };

  fetch("update-taxa/" + localStorage.getItem("cod_taxa"), {
    method: "PUT",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": token
    }),
    body: JSON.stringify(data)
  })
    .then(function(response) {
      response.json().then(function(data) {
        document.querySelector(".success-update-ad").style.display = "block";

        setTimeout(function() {
          $("#modalEditarAdquirente").modal("hide");
          document.querySelector(".success-update-ad").style.display = "none";
          document.querySelector('input[name="editar-taxa"]').value = "";
        }, 2000);

        atualizaTabela();
      });
    })
    .catch(error => alert("Algo deu errado!"));
}

function cadastrarTaxa(taxa) {
  const token = document.getElementById("token").value;

  fetch("cadastro-taxa", {
    method: "POST",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": token
    }),
    body: JSON.stringify(taxa)
  })
    .then(function(response) {
      response.json().then(function(data) {
        document.querySelector(".success-save-tx").style.display = "block";

        setTimeout(function() {
          document.querySelector(".success-save-tx").style.display = "none";
          document.querySelector('input[name="taxa"]').value = "";
        }, 2500);

        atualizaTabela();
      });
    })
    .catch(error => alert("Algo deu errado!"));
}

function atualizaTabela() {
  buscarTodasTaxas();
}

function buscarTodasTaxas() {
  fetch("load-taxas", {
    method: "GET",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      Accept: "application/json"
    })
  }).then(function(response) {
    response.json().then(function(data) {
      renderizaTabela(data.taxas);
    });
  });
}

function renderizaTabela(taxas) {
  document.querySelector("#conteudo_tabe").innerHTML = "";

  let html = "";

  for (taxa of taxas) {
    html += `<tr id='${taxa.CODIGO}'>
    <td> ${taxa.CODIGO} </td>
    <td> ${taxa.NOME_FANTASIA} </td>
    <td> ${taxa.TAXA} </td>
    <td> ${taxa.ADQUIRENTE} </td>
    <td> ${taxa.BANDEIRA} </td>
    <td> ${taxa.DESCRICAO} </td>
    <td> ${formataData(taxa.DATA_VIGENCIA)} </td>
    <td class='excluir'>
    <a href='#' onclick='editarTaxa(event, ${JSON.stringify(
      taxa
    )})'><i class='far fa-edit'></i></a>
    <a href='#' onclick='excluirTaxa(event, ${
      taxa.CODIGO
    })'><i style='margin-left: 12px' class='far fa-trash-alt'></i></a>"
    </td>
    </tr>`;
  }

  document.querySelector(
    "#qtd-registros"
  ).innerHTML = `Total de taxas (${taxas.length} registros)`;
  document.querySelector("#conteudo_tabe").innerHTML = html;
}

function excluirTaxa(e, codigo_taxa) {
  e.preventDefault();

  localStorage.setItem("cod_taxa", codigo_taxa);
  $("#modalExcluirTaxa").modal({
    show: true
  });
}

document.getElementById("sim").addEventListener("click", function() {
  const cod_taxa = localStorage.getItem("cod_taxa");

  fetch(`delete-taxa/${cod_taxa}`, {
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    })
  }).then(function(response) {
    response.json().then(function(data) {
      buscarTodasTaxas();
    });
  });
});

function mascaraTaxa(a, e, r, t) {
  let n = "",
    h = (j = 0),
    u = (tamanho2 = 0),
    l = (ajd2 = ""),
    o = window.Event ? t.which : t.keyCode;
  if (13 == o || 8 == o) return !0;
  if (((n = String.fromCharCode(o)), -1 == "0123456789".indexOf(n))) return !1;
  for (
    u = a.value.length, h = 0;
    h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r);
    h++
  );
  for (l = ""; h < u; h++)
    -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
  if (
    ((l += n),
    0 == (u = l.length) && (a.value = ""),
    1 == u && (a.value = "0" + r + "0" + l),
    2 == u && (a.value = "0" + r + l),
    u > 2)
  ) {
    for (ajd2 = "", j = 0, h = u - 3; h >= 0; h--)
      3 == j && ((ajd2 += e), (j = 0)), (ajd2 += l.charAt(h)), j++;
    for (a.value = "", tamanho2 = ajd2.length, h = tamanho2 - 1; h >= 0; h--)
      a.value += ajd2.charAt(h);
    a.value += r + l.substr(u - 2, u);
  }
  return !1;
}

function formataData(data) {
  const new_data_ = new Date(data);
  const data_formatada = new_data_.toLocaleDateString("pt-BR", {
    timeZone: "UTC"
  });
  return data_formatada;
}
