document.querySelector(".success-save-bandeira").style.display = "none";
document.querySelector(".success-update-bandeira").style.display = "none";

const buttonCadastroBandeira = document.querySelector(".bt-cadastro-bandeira");
const buttonSalvaCadastro = document.querySelector(".bt-salva-bandeira");
const buttonSalvaEdicao = document.querySelector(".bt-salva-edicao-bandeira");
const buttonSimExclusao = document.querySelector("#sim");
const bandeirasPesquisados = document.querySelector('input[name="bandeiras-pesquisados"]');
const bandeirasCarregados = document.querySelector('input[name="bandeiras-carregados"]');

buttonSalvaCadastro.addEventListener("click", function() {
  const bandeira = document.querySelector('input[name="bandeira"]').value;
  cadastrarBanco(bandeira);
});

buttonSalvaEdicao.addEventListener("click", function() {
  const bandeira = document.querySelector('input[name="editar-bandeira"]').value;
  salvarEdicaoBandeira(bandeira);
});

buttonCadastroBandeira.addEventListener("click", function() {
  $("#modalCadastroBandeira").modal({
    show: true
  });
});

bandeirasPesquisados.addEventListener('keydown', function() {
  const bandeiras = JSON.parse(bandeirasCarregados.value);

  setTimeout(function(){
    if(bandeirasPesquisados.value == '') {
      for(bandeira of bandeiras) {
        document.getElementById(bandeira.CODIGO).style = "display: "
      }
    } else {
      for(bandeira of bandeiras) {

        var regex = new RegExp(bandeirasPesquisados.value, 'gi');
        resultado = bandeira.BANDEIRA.match(regex);

        if(resultado) {
          console.log(resultado);
          document.getElementById(bandeira.CODIGO).style = "display: ";
        }else{
          document.getElementById(bandeira.CODIGO).style.display = "none";
        }
      }
    }
  }, 300);
})

function editarBandeira(e, bandeira){
  localStorage.setItem("cod_bandeira", bandeira.CODIGO);

  $("#modalEditarBandeira").modal({
    show: true
  });
  document.querySelector("input[name='editar-bandeira']").value = `${bandeira.BANDEIRA}`;
}

function salvarEdicaoBandeira(bandeira) {
  const data = { bandeira };

  fetch("update-bandeira/" + localStorage.getItem('cod_bandeira'), {
    method: "PUT",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    }),
    body: JSON.stringify(data)
  })
  .then(function(response) {
    response.json().then(function(data) {
      document.querySelector(".success-update-bandeira").style.display = "block";

      setTimeout(function() {
        $('#modalEditarBandeira').modal('hide');
        document.querySelector(".success-update-bandeira").style.display = "none";
        document.querySelector('input[name="editar-bandeira"]').value = "";
      }, 2000);

      atualizaTabela();
    });
  })
  .catch(error => alert("Algo deu errado!"));
}

function cadastrarBanco(bandeira) {
  const data = { bandeira };

  fetch("cadastro-bandeira", {
    method: "POST",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    }),
    body: JSON.stringify(data)
  })
  .then(function(response) {
    response.json().then(function(data) {
      document.querySelector(".success-save-bandeira").style.display = "block";

      setTimeout(function() {
        document.querySelector(".success-save-bandeira").style.display = "none";
        document.querySelector('input[name="bandeira"]').value = "";
      }, 2500);

      atualizaTabela();
    });
  })
  .catch(error => alert("Algo deu errado!"));
}

function atualizaTabela() {
  buscarTodasBandeiras();
}

function buscarTodasBandeiras() {
  fetch("load-bandeiras", {
    method: "GET",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      Accept: "application/json"
    })
  }).then(function(response) {
    response.json().then(function(data) {
      renderizaTabela(data.bandeiras);
    });
  });
}

function renderizaTabela(bandeiras) {
  document.querySelector("#conteudo_tabe").innerHTML = "";

  let html = "";

  for (bandeira of bandeiras) {
    html += `<tr id='${bandeira.CODIGO}'>
    <td> ${bandeira.CODIGO} </td>
    <td> ${bandeira.BANDEIRA} </td>
    <td>
    <a href='#' onclick='editarBandeira(event, ${JSON.stringify(bandeira)})'><i class='far fa-edit'></i></a>
    <a href='#' onclick='excluirBandeira(event, ${bandeira.CODIGO})'><i style='margin-left: 12px' class='far fa-trash-alt'></i></a>"
    </td>
    </tr>`;
  }

  document.querySelector(
    "#qtd-registros"
  ).innerHTML = `Total de bandeiras (${bandeiras.length} registros)`;
  document.querySelector("#conteudo_tabe").innerHTML = html;
}

function excluirBandeira(e, codigo_bandeira) {
  e.preventDefault();

  localStorage.setItem("cod_bandeira", codigo_bandeira);
  $("#modalExcluirBandeira").modal({
    show: true
  });
}

document.getElementById("sim").addEventListener("click", function() {
  const cod_bandeira = localStorage.getItem("cod_bandeira");

  fetch(`delete-bandeira/${cod_bandeira}`, {
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    })
  }).then(function(response) {
    response.json().then(function(data) {
      buscarTodasBandeiras();
    });
  });
});
