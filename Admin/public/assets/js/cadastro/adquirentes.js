document.querySelector(".success-save-ad").style.display = "none";
const buttonLimparFiltro = document.querySelector(".limpa-filtro");
const buttonCadastroAdquirente = document.querySelector(".bt-cadastro-ad");
const buttonSalvaCadastro = document.querySelector(".bt-salva-ad");
const buttonSimExclusao = document.querySelector("#sim");

buttonCadastroAdquirente.addEventListener("click", function() {
  $("#modalCadastroAdquirente").modal({
    show: true
  });
});

buttonSalvaCadastro.addEventListener("click", function() {
  const adquirente = document.querySelector('input[name="adquirente"]').value;
  cadastrarAdquirente(adquirente);
});

function cadastrarAdquirente(adquirente) {
  const headers = new Headers();
  const data = { _token: "{{csrf_token()}}", adquirente };

  fetch("cadastro-adquirente", {
    method: "POST",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    }),
    body: JSON.stringify(data)
  })
    .then(function(response) {
      response.json().then(function(data) {
        document.querySelector(".success-save-ad").style.display = "block";

        setTimeout(function() {
          document.querySelector(".success-save-ad").style.display = "none";
          document.querySelector('input[name="adquirente"]').value = "";
        }, 2500);

        atualizaTabela();
      });
    })
    .catch(error => alert("Algo deu errado!"));
}

function atualizaTabela() {
  buscarTodosAdquirentes();
}

function buscarTodosAdquirentes() {
  fetch("atualiza-tabela", {
    method: "GET",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      Accept: "application/json"
    })
  }).then(function(response) {
    response.json().then(function(data) {
      renderizaTabela(data.adquirentes);
    });
  });
}

function renderizaTabela(adquirentes) {
  // $("#conteudo_tabe").empty();
  document.querySelector("#conteudo_tabe").innerHTML = "";

  let html = "";

  for (adquirente of adquirentes) {
    html += `<tr id='${adquirente.CODIGO}'>
    <td> ${adquirente.ADQUIRENTE} </td>
    <td> ${adquirente.ADQUIRENTE} </td>
    <td> ${
      adquirente.HOMOLOGADO
        ? `<i style="color: green" class="fas fa-check"></i>`
        : `<i  style="color: red" class="fas fa-times"></i>`
    }
    <td>
    <a href='#' onclick='editarJustificativa("+response[i].CODIGO+")'><i class='far fa-edit'></i></a>
    <a href='#' onclick='excluirAdquirente(event, ${
      adquirente.CODIGO
    })'><i style='margin-left: 12px' class='far fa-trash-alt'></i></a>"
    </td>
    </tr>`;
  }

  document.querySelector(
    "#qtd-adquirentes"
  ).innerHTML = `Total de adquirentes (${adquirentes.length} registros)`;
  document.querySelector("#conteudo_tabe").innerHTML = html;
}

function excluirAdquirente(e, codigo_adquirente) {
  e.preventDefault();

  localStorage.setItem("cod_adquirente", codigo_adquirente);
  $("#modalExcluirAdquirente").modal({
    show: true
  });
}

function editarJustificativa(cod_justificativa) {
  let url = "{{ url('justificativa') }}" + "/" + cod_justificativa;

  $.ajax({
    url: url,
    type: "get",
    header: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    success: function(response) {
      $("#justificativaEdit").val(response.JUSTIFICATIVA);

      $("#modalEditarJustificativa").modal({
        show: true
      });
    }
  });

  localStorage.setItem("cod_justificativa", cod_justificativa);
}

function salvarEdicaoJustificativa() {
  let justificativa = {
    codigo: localStorage.getItem("cod_justificativa"),
    justificativa: $("#justificativaEdit").val()
  };

  $.ajax({
    type: "PUT",
    url: "api/justificativa/" + localStorage.getItem("cod_justificativa"),
    data: justificativa,
    context: this,
    header: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    success: function(data) {
      let linhas = $("#" + localStorage.getItem("cod_justificativa"));

      linhas[0].cells[0].textContent = justificativa.justificativa.toUpperCase();
    }
  });
}

document.getElementById("sim").addEventListener("click", function() {
  const cod_adquirente = localStorage.getItem("cod_adquirente");

  fetch(`delete-adquirente/${cod_adquirente}`, {
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    })
  }).then(function(response) {
    response.json().then(function(data) {
      buscarTodosAdquirentes();
    });
  });
});
