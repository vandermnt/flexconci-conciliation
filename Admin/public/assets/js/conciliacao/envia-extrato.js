const btEnviarExtrato = document.querySelector("button[name='submit-extrato']");
const btCloseModal = document.querySelector(".close-modal");

btEnviarExtrato.addEventListener("click", function() {
  document.getElementById("label-modal-progress").style.display = "block";
  uploadExtrato();
});

btCloseModal.addEventListener("click", function() {
  document.getElementById("label-modal-progress").style.display = "none";
  document.getElementById("label-modal-success").style.display = "none";
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
        document.getElementById("label-modal-progress").style.display = "none"
        document.getElementById("label-modal-success").style.display = "block"
      });
    })
    .catch(error => alert("Algo deu errado!"));
}
