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

function confirmarSelecao(event) {
  const botaoDOM = event.target;
  const acao = botaoDOM.dataset.acao;
  const nomeGrupo = botaoDOM.dataset.group;

  if(String(acao).toLowerCase() === 'cancelar') {
    checker.uncheckAll(nomeGrupo);
  }

  checker.setValuesToTextElement(nomeGrupo, 'descricao');
}

window.addEventListener('load', () => {
  document.querySelector('#pagina-conciliacao').classList.remove('hidden');
});

[...document.querySelectorAll('button[data-acao]')].forEach(botaoDOM => {
  botaoDOM.addEventListener('click', confirmarSelecao);
});

document.querySelector('#js-reset-form')
  .addEventListener('click', limpar);