document.querySelector(".success-save-ad").style.display = "none";
document.querySelector(".success-update-ad").style.display = "none";

const buttonCadastroAdquirente = document.querySelector(".bt-cadastro-ad");
const buttonSalvaCadastro = document.querySelector(".bt-salva-ad");
const buttonSalvaEdicao = document.querySelector(".bt-salva-edicao-ad");
const buttonSimExclusao = document.querySelector("#sim");
const adquirentesPesquisados = document.querySelector('input[name="adquirentes-pesquisados"]');
const adquirentesCarregados = document.querySelector('input[name="adquirentes-carregados"]');

buttonSalvaCadastro.addEventListener("click", function() {
  const adquirente = document.querySelector('input[name="adquirente"]').value;
  cadastrarAdquirente(adquirente);
});

buttonSalvaEdicao.addEventListener("click", function() {
  const adquirente = document.querySelector('input[name="editar-adquirente"]').value;
  salvarEdicaoAdquirente(adquirente);
});

buttonCadastroAdquirente.addEventListener("click", function() {
  $("#modalCadastroAdquirente").modal({
    show: true
  });
});

adquirentesPesquisados.addEventListener('keydown', function() {
  const adquirentes = JSON.parse(adquirentesCarregados.value);

  setTimeout(function(){
    if(adquirentesPesquisados.value == '') {
      for(adquirente of adquirentes) {
        document.getElementById(adquirente.CODIGO).style = "display: ";
      }
    } else {
      for(adquirente of adquirentes) {

        var regex = new RegExp(adquirentesPesquisados.value, 'gi');
        resultado = adquirente.ADQUIRENTE.match(regex);

        if(resultado) {
          document.getElementById(adquirente.CODIGO).style = "display: ";
        }else{
          document.getElementById(adquirente.CODIGO).style.display = "none";
        }
      }
    }
  }, 300);
})

function editarAdquirente(e, adquirente){
  localStorage.setItem("cod_adquirente", adquirente.CODIGO);

  $("#modalEditarAdquirente").modal({
    show: true
  });
  document.querySelector("input[name='editar-adquirente']").value = `${adquirente.ADQUIRENTE}`;
}

function salvarEdicaoAdquirente(adquirente) {
  const token = document.getElementById("token").value;
  const data = { adquirente };

  fetch("update-adquirente/" + localStorage.getItem('cod_adquirente'), {
    method: "PUT",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": token,
    }),
    body: JSON.stringify(data)
  })
  .then(function(response) {
    response.json().then(function(data) {
      document.querySelector(".success-update-ad").style.display = "block";

      setTimeout(function() {
        $('#modalEditarAdquirente').modal('hide');
        document.querySelector(".success-update-ad").style.display = "none";
        document.querySelector('input[name="editar-adquirente"]').value = "";
      }, 2000);

      atualizaTabela();
    });
  })
  .catch(error => alert("Algo deu errado!"));
}

function cadastrarAdquirente(adquirente) {
  const token = document.getElementById("token").value;
  const data = { adquirente };

  fetch("cadastro-adquirente", {
    method: "POST",
    headers: new Headers({
      "Content-Type": "application/json",
      'X-CSRF-TOKEN': token
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
  fetch("load-adquirentes", {
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
    <td> ${adquirente.CODIGO} </td>
    <td> ${adquirente.ADQUIRENTE} </td>
    <td> ${
      adquirente.HOMOLOGADO
      ? `<i style="color: green" class="fas fa-check"></i>`
      : `<i  style="color: red" class="fas fa-times"></i>`
    }
    <td>
    <a href='#' onclick='editarAdquirente(event, ${JSON.stringify(adquirente)})'><i class='far fa-edit'></i></a>
    <a href='#' onclick='excluirAdquirente(event, ${
      adquirente.CODIGO
    })'><i style='margin-left: 12px' class='far fa-trash-alt'></i></a>"
    </td>
    </tr>`;
  }

  document.querySelector(
    "#qtd-registros"
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
