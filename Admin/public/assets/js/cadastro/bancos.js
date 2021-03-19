document.querySelector(".success-save-banco").style.display = "none";
document.querySelector(".success-update-banco").style.display = "none";

const buttonCadastroAdquirente = document.querySelector(".bt-cadastro-ad");
const buttonSalvaCadastro = document.querySelector(".bt-salva-banco");
const buttonSalvaEdicao = document.querySelector(".bt-salva-edicao-banco");
const buttonSimExclusao = document.querySelector("#sim");
const bancosPesquisados = document.querySelector('input[name="bancos-pesquisados"]');
const bancosCarregados = document.querySelector('input[name="bancos-carregados"]');

buttonSalvaCadastro.addEventListener("click", function() {
  const banco = document.querySelector('input[name="banco"]').value;
  cadastrarBanco(banco);
});

buttonSalvaEdicao.addEventListener("click", function() {
  const banco = document.querySelector('input[name="editar-banco"]').value;
  salvarEdicaoBanco(banco);
});

buttonCadastroAdquirente.addEventListener("click", function() {
  $("#modalCadastroBanco").modal({
    show: true
  });
});

bancosPesquisados.addEventListener('keydown', function() {
  const bancos = JSON.parse(bancosCarregados.value);

  setTimeout(function(){
    if(bancosPesquisados.value == '') {
      for(banco of bancos) {
        document.getElementById(banco.CODIGO).style = "display: ";
      }
    } else {
      for(banco of bancos) {

        var regex = new RegExp(bancosPesquisados.value, 'gi');
        resultado = banco.BANCO.match(regex);

        if(resultado) {
          document.getElementById(banco.CODIGO).style = "display: ";
        }else{
          document.getElementById(banco.CODIGO).style.display = "none";
        }
      }
    }
  }, 300);
})

function editarBanco(e, banco){
  localStorage.setItem("cod_banco", banco.CODIGO);

  $("#modalEditarBanco").modal({
    show: true
  });
  document.querySelector("input[name='editar-banco']").value = `${banco.BANCO}`;
}

function salvarEdicaoBanco(banco) {
  const headers = new Headers();
  const data = { banco };

  fetch("update-banco/" + localStorage.getItem('cod_banco'), {
    method: "PUT",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    }),
    body: JSON.stringify(data)
  })
  .then(function(response) {
    response.json().then(function(data) {
      document.querySelector(".success-update-banco").style.display = "block";

      setTimeout(function() {
        $('#modalEditarBanco').modal('hide');
        document.querySelector(".success-update-banco").style.display = "none";
        document.querySelector('input[name="editar-banco"]').value = "";
      }, 2000);

      atualizaTabela();
    });
  })
  .catch(error => alert("Algo deu errado!"));
}

function cadastrarBanco(banco) {
  const headers = new Headers();
  const data = { banco };

  fetch("cadastro-banco", {
    method: "POST",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    }),
    body: JSON.stringify(data)
  })
  .then(function(response) {
    response.json().then(function(data) {
      document.querySelector(".success-save-banco").style.display = "block";

      setTimeout(function() {
        document.querySelector(".success-save-banco").style.display = "none";
        document.querySelector('input[name="banco"]').value = "";
      }, 2500);

      atualizaTabela();
    });
  })
  .catch(error => alert("Algo deu errado!"));
}

function atualizaTabela() {
  buscarTodosBancos();
}

function buscarTodosBancos() {
  fetch("load-bancos", {
    method: "GET",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      Accept: "application/json"
    })
  }).then(function(response) {
    response.json().then(function(data) {
      renderizaTabela(data.bancos);
      console.log(data.bancos);
    });
  });
}

function renderizaTabela(bancos) {
  document.querySelector("#conteudo_tabe").innerHTML = "";

  let html = "";

  for (banco of bancos) {
    html += `<tr id='${banco.CODIGO}'>
    <td> ${banco.CODIGO} </td>
    <td> ${banco.BANCO} </td>
    <td>
    <a href='#' onclick='editarBanco(event, ${JSON.stringify(banco)})'><i class='far fa-edit'></i></a>
    <a href='#' onclick='excluirBanco(event, ${banco.CODIGO})'><i style='margin-left: 12px' class='far fa-trash-alt'></i></a>"
    </td>
    </tr>`;
  }

  document.querySelector(
    "#qtd-registros"
  ).innerHTML = `Total de bancos (${bancos.length} registros)`;
  document.querySelector("#conteudo_tabe").innerHTML = html;
}

function excluirBanco(e, codigo_banco) {
  e.preventDefault();

  localStorage.setItem("cod_banco", codigo_banco);
  $("#modalExcluirBanco").modal({
    show: true
  });
}

document.getElementById("sim").addEventListener("click", function() {
  const cod_banco = localStorage.getItem("cod_banco");

  fetch(`delete-banco/${cod_banco}`, {
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    })
  }).then(function(response) {
    response.json().then(function(data) {
      buscarTodosBancos();
    });
  });
});
