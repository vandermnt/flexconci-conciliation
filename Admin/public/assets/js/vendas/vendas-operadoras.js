const checker = new Checker();
const modalFilter = new ModalFilter();
const searchForm = new SearchFormProxy({
  form: '#js-form-pesquisa',
  inputs: ['_token', 'data_inicial', 'data_final'],
  checker,
});
const salesContainer = new SalesContainerProxy({
  id: 'operadoras',
  links: {
    search: searchForm.get('form').dataset.urlOperadoras,
    filter: searchForm.get('form').dataset.urlFiltrarOperadoras,
  }
});
const tableRender = new TableRender({
    id: '#js-tabela-operadoras',
    locale: 'pt-br'
});

checker.addGroups([
  { name: 'empresa', options: { inputName: 'grupos_clientes' } },
  { name: 'adquirente', options: { inputName: 'adquirentes' } },
  { name: 'bandeira', options: { inputName: 'bandeiras' } },
  { name: 'modalidade', options: { inputName: 'modalidades' } },
  { name: 'estabelecimento', options: { inputName: 'estabelecimentos' } },
  { name: 'status-conciliacao', options: { inputName: 'status_conciliacao' } },
  { name: 'status-financeiro', options: { inputName: 'status_financeiro' } },
]);

modalFilter.addGroups([
  'empresa',
  'adquirente',
  'bandeira',
  'modalidade',
  'estabelecimento'
]);

salesContainer.setupApi({
  headers: {
    'X-CSRF-TOKEN': searchForm.getInput('_token').value,
    'Content-Type': 'application/json',
  }
});

salesContainer.onEvent('beforeFetch', () => {
  document.querySelector('#js-loader').classList.toggle('hidden');
});

salesContainer.onEvent('fetch', (sales) => {
  document.querySelector('#js-loader').classList.toggle('hidden');
});

salesContainer.onEvent('search', (sales) => {
  console.log(sales);
});

salesContainer.onEvent('fail', (err) => {
  document.querySelector('#js-loader').classList.remove('hidden');
  document.querySelector('#js-loader').classList.add('hidden');
});

searchForm.onSubmit(async (event) => {
  await salesContainer.search({
    params: {
      por_pagina: document.querySelector('#js-por-pagina').value,
    },
    body: { ...searchForm.serialize() },
  });
});

tableRender.onRenderCell((cell, data) => {
    if(cell.dataset.image) {
        const iconContainer = cell.querySelector('icon-image');
        const imageUrl = data[cell.dataset.image];
        const defaultImageUrl = cell.dataset.defaultImage;

        if(imageUrl || defaultImageUrl) {
            iconContainer.style.backgroundImage = imageUrl || defaultImageUrl;
        } else {
            const text = data[cell.dataset.text];
            const defaultText = cell.dataset.defaultText;
            const title = data[cell.dataset.title];
            const defaultTitle = cell.dataset.defaultTitle;

            cell.dataset.title = title || defaultTitle || '';
            cell.textContent = text || defaultText || '';
        }
T   }
});

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
  buttonDOM.addEventListener('click', e => searchForm.clear());
});

searchForm.get('form').querySelector('button[data-form-action="submit"')
  .addEventListener('click', searchForm.get('onSubmitHandler'));
