const checker = new Checker();
const modalFilter = new ModalFilter();

checker.addGroups([
  'empresa',
  'adquirente',
  'bandeira',
  'modalidade', 
  'estabelecimento',
  'status-conciliacao',
  'status-financeiro'
]);

modalFilter.addGroups([
  'empresa',
  'adquirente',
  'bandeira',
  'modalidade',
  'estabelecimento'
]);

function clearForm(event) {
  const buttonDOM = event.target;
  const form = buttonDOM.closest('form');

  if(!form) {
    return;
  }

  form.reset();
}

function onCancelModalSelection(event) {
  const buttonDOM = event.target;
  const groupName = buttonDOM.dataset.group;
  
  checker.uncheckAll(groupName);
  checker.setValuesToTextElement(groupName, 'descricao');
}

function onConfirmModalSelection(event) {
  const buttonDOM = event.target;
  const groupName = buttonDOM.dataset.group;

  checker.setValuesToTextElement(groupName, 'descricao');
};

Array.from(
  document.querySelectorAll('.modal button[data-action="confirm"]')
).forEach(buttonDOM => {
  buttonDOM.addEventListener('click', onConfirmModalSelection);
});

Array.from(
  document.querySelectorAll('.modal button[data-action="cancel"]')
).forEach(buttonDOM => {
  buttonDOM.addEventListener('click', onCancelModalSelection);
});

Array.from(
  document.querySelectorAll('form button[data-form-action="clear"]')
).forEach(buttonDOM => {
  buttonDOM.addEventListener('click', clearForm);
});