function TableRender(options = {}) {
  this.proxy = new Proxy({
    table: options.table || '#js-table',
    data: options.data || { body: {}, footer: {} },
    locale: options.locale || 'pt-br',
    _formatters: {
      currency: options.currencyFormatter || new Intl.NumberFormat('pt-br', {
        style: 'currency',
        currency: 'BRL'
      }),
      number: options.numberFormatter || new Intl.NumberFormat('pt-br', {
        maximumFractionDigits: 2
      }),
      date: options.dateFormatter || {
        format: function(date) {
          const formattedDate = new Date(`${date} 00:00:00`).toLocaleDateString('pt-br');
          if(formattedDate === 'Invalid Date') {
            return '';
          }

          return formattedDate;
        }
      }
    },
    selectedRows: [],
    onRenderRow: () => {},
    onRenderCell: () => {},
    onSelectRow: () => {},
    onFilter: () => {},
  }, tableRenderHandler());
}

TableRender.prototype.get = function(prop = null) {
  if(!prop) {
    return this.proxy;
  }

  return this.proxy[prop];
}

TableRender.prototype.set = function(prop = '', value = null) {
  this.proxy[prop] = value;
}

TableRender.prototype.setFormatLocale = function(locale) {
  this.set('locale', locale);
  this.set('_formatters', {
    currency: new Intl.NumberFormat(locale, {
      style: 'currency',
      currency: 'BRL'
    }),
    number: new Intl.NumberFormat(locale, {
      maximumFractionDigits: 2
    }),
    date: {
      format: function(date) {
        const formattedDate = new Date(`${date} 00:00:00`).toLocaleDateString('pt-br');
        if(formattedDate === 'Invalid Date') {
          return '';
        }

        return formattedDate;
      }
    }
  });
}

TableRender.prototype.formatCell = function(value, type = 'text', defaultValue = '') {
    const formatter = this.get('_formatters')[type];
    let valueToFormat = value;

    if(!value) {
        valueToFormat = defaultValue || '';
    }

    if(!formatter) {
        return valueToFormat;
    }

    return formatter.format(valueToFormat);
}

TableRender.prototype.onRenderRow = function(handler = (row = null) => {}) {
  this.set('onRenderRow', handler);
}

TableRender.prototype.onRenderCell = function(handler = (element = null, data = {}) => {}) {
  this.set('onRenderCell', handler);
}

TableRender.prototype.onSelectRow = function(handler = (element) => {}) {
  this.set('onSelectRow', handler);
}

TableRender.prototype.onFilter = function(handler = (filters = {}) => {}) {
  this.set('onFilter', handler);
  this.addTableFiltersListener();
}

TableRender.prototype.addTableFiltersListener = function() {
  const table = this.get('table');
  const tableInputs = Array.from(table.querySelectorAll('thead input[name]'));
  const onFilter = this.get('onFilter');

  tableInputs.forEach(input => {
    input.addEventListener('keyup', event => {
      if(event.key === 'Enter') {
        if(onFilter && typeof onFilter === 'function') {
          onFilter({ ...this.serializeTableFilters() });
        }
      }
    })
  });
}

TableRender.prototype.serializeTableFilters = function() {
  const table = this.get('table');
  const tableInputs = Array.from(table.querySelectorAll('thead input[name]'));

  const filters = tableInputs.reduce((data, input) => {
    const value = input.value.trim();

    if(value) {
      data[input.name] = value;
    }

    return data;
  }, {});

  return filters;
}

TableRender.prototype.render = function() {
    const table = this.get('table');
    const onRenderRow = this.get('onRenderRow');
    const onRenderCell = this.get('onRenderCell');
    const onSelectRow = this.get('onSelectRow');

    if(!table) {
        return;
    }

    const tbody = table.querySelector('tbody');
    const tfooter = table.querySelector('tfoot');
    const templateRow = table.querySelector('tbody .table-row-template').cloneNode(true);
    
    templateRow.classList.remove('hidden');
    templateRow.classList.add('hidden');

    tbody.innerHTML = '';
    tbody.appendChild(templateRow);
    (this.get('data').body || []).forEach(data => {
        const tableRow = templateRow.cloneNode(true);
        const tableCells = Array.from(tableRow.querySelectorAll('td[data-column]'));

        tableRow.dataset.id = data[tableRow.dataset.id];

        tableCells.forEach(tableCell => {
            if(onRenderCell && typeof onRenderCell === 'function') {
                onRenderCell(tableCell, data);
            }
        });

        if(onSelectRow && typeof onSelectRow === 'function') {
            tableRow.addEventListener('click', e => {
              const selectedRows = this.get('selectedRows');
              if(selectedRows.includes(tableRow.dataset.id)) {
                this.set('selectedRows', selectedRows.filter(value => value != tableRow.dataset.id));
              } else {
                selectedRows.push(tableRow.dataset.id);
              }

              this.get('onSelectRow')(e.target, this.get('selectedRows'));
            });
        }

        tableRow.classList.remove('hidden');
        tableRow.classList.remove('table-row-template');

        if(onRenderRow && typeof onRenderRow === 'function') {
          onRenderRow(tableRow);
        }

        tbody.appendChild(tableRow);
    });

    const tableFooterCells = Array.from(tfooter.querySelectorAll('tr td[data-column]'));
    tableFooterCells.forEach(tableCell => {
        if(onRenderCell && typeof onRenderCell === 'function') {
            onRenderCell(tableCell, (this.get('data').footer || {}));
        }
    });
}

function tableRenderHandler() {
  return {
    set: function(target, name, value) {
        if(['_formatters'].includes(name)) {
            return;
        }

        target[name] = value;
    },
    get: function(target, name) {
      if(['currency', 'number', 'date'].includes(name)) {
        return target._formatters[name];
      }

      if(name === 'table') {
          if(typeof target[name] !== 'string') {
              return target[name];
          }

          return document.querySelector(target[name]);
      }

      return target[name];
    }
  }
}
