const btEnviarExtrato = document.querySelector("button[name='submit-extrato']");

btEnviarExtrato.addEventListener("click", function() {
  uploadExtrato();
});

function uploadExtrato() {
  const file = new FormData(document.getElementById("upload-file"));

  fetch("enviar-extrato", {
    method: "POST",
    headers: new Headers({
      "X-CSRF-Token": document.querySelector("input[name=_token]").value
    }),
    processData: false,
    contentType: false,
    body: file
  })
    .then(function(response) {
      response.json().then(function(data) {
        alert("Histórico Enviado!");
      });
    })
    .catch(error => alert("Algo deu errado!"));
}
