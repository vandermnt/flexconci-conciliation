const btEnviarExtrato = document.querySelector("button[name='submit-extrato']");
const btCloseModal = document.querySelector(".close-modal");
const btCancelModal = document.querySelector("#js-extrato-bancario button[class='close']");

btEnviarExtrato.addEventListener("click", function() {
  const isValid = document.querySelector("input[name='extratos[]']").files.length

  if (isValid > 0) {
    document.getElementById("label-modal-progress").style.display = "block";
    uploadExtrato();
  } else {
    alert("Escolha um arquivo!");
  }
});

btCloseModal.addEventListener("click", function() {
  document.getElementById("label-modal-progress").style.display = "none";
  document.getElementById("label-modal-success").style.display = "none";
});

btCancelModal.addEventListener("click", function() {
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
