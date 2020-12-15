const checker = new Checker();
const modalFilter = new ModalFilter();

checker.addGroup('empresa');
modalFilter.addGroup('empresa');

function limpar() {
  const form = document.querySelector('#js-form-pesquisar');
  const dataInputs = document.querySelectorAll('#js-form-pesquisar input[type=date]');
  
  form.reset();
  [...dataInputs].forEach(input => {
    input.value = "";
  });
}

window.addEventListener('load', () => {
  document.querySelector('#pagina-conciliacao').classList.remove('hidden');
});

document.querySelector('#js-reset-form')
  .addEventListener('click', limpar);