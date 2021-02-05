function createSearchForm({ form, inputs, checker }) {
  const searchForm = new SearchFormProxy({
    form,
    inputs,
    checker,
  });

  Array.from(
    searchForm.get('form').querySelectorAll('button[data-form-action="clear"]')
  ).forEach(buttonDOM => {
    buttonDOM.addEventListener('click', e => searchForm.clear());
  });

  return searchForm;
}

function createTableRender({ table = '', locale = 'pt-br', formatter }) {
  const tableRender = new TableRender({
    table,
    locale,
    formatter,
  });

  tableRender.onRenderRow(row => {
    const selectedRows = tableRender.get('selectedRows');
    row.classList.remove('marcada');
    if (selectedRows.includes(row.dataset.id)) {
      row.classList.add('marcada');
    }
  });

  tableRender.onRenderCell((cell, data) => {
    if (cell.classList.contains('tooltip-hint')) {
      const title = data[cell.dataset.title];
      const defaultTitle = cell.dataset.defaultTitle;
  
      cell.dataset.title = tableRender.formatCell(title, 'text', defaultTitle);
    }
  
    if (cell.dataset.image) {
      const iconContainer = cell.querySelector('.icon-image');
      const imageUrl = data[cell.dataset.image];
      const defaultImageUrl = cell.dataset.defaultImage;
  
      if (imageUrl || defaultImageUrl) {
        iconContainer.style.backgroundImage = `url("${imageUrl || defaultImageUrl}")`;
        const title = data[iconContainer.dataset.title];
        const defaultTitle = iconContainer.dataset.defaultTitle;
  
        iconContainer.dataset.title = tableRender.formatCell(title, 'text', defaultTitle);
        return;
      }
      iconContainer.classList.toggle('hidden');
    }
  
    const cellValue = data[cell.dataset.column];
    const defaultCellValue = data[cell.dataset.defaultValue];
    const format = cell.dataset.format || 'text';

    if(cell.dataset.reverseValue) {
      const reverseValue = tableRender.formatCell(cellValue * -1, format, defaultCellValue * -1);
      cell.textContent = reverseValue;
      return;
    }

    const value =  tableRender.formatCell(cellValue, format, defaultCellValue);
    cell.textContent = value;
  });

  tableRender.onSelectRow((elementDOM, selectedRows) => {
    let tr = elementDOM;
    if (['a', 'i'].includes(elementDOM.tagName.toLowerCase())) {
      return;
    }
  
    if (elementDOM.tagName.toLowerCase() !== 'tr') {
      tr = elementDOM.closest('tr');
    }
  
    if (!tr) {
      return;
    }
  
    tr.classList.remove('marcada');
    if (selectedRows.includes(tr.dataset.id)) {
      tr.classList.add('marcada');
    } else {
      tr.classList.remove('marcada');
    }
  });

  return tableRender;
}

function toggleElementVisibility(selector = '') {
  const element = document.querySelector(selector);

  if(element) {
    element.classList.toggle('hidden');
  }
}

function getBoxes() {
  const boxes = [];

  Array.from(document.querySelectorAll('.box')).forEach(boxDOM => {
    const box = new Box({
      element: boxDOM,
      defaultValue: 0,
      format: boxDOM.dataset.format,
      formatter,
    });
    boxes.push(box);
  });

  return boxes;
}

function updateBoxes(boxes, totals) {
  boxes.forEach(box => {
    box.set('value', totals[box.get('element').dataset.key]);
    box.render();
  });
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

function openUrl(baseUrl, params) {
  const url = api.urlBuilder(baseUrl, params);
  const a = document.createElement('a');

  a.href = url;
  a.target = '_blank';
  a.click();
}

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