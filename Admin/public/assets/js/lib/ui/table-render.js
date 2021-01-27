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
          return new Date(`${date} 00:00:00`).toLocaleDateString('pt-br');
        }
      }
    },
    onRenderCell: () => {},
    onSelectRow: () => {},
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
        return new Date(`${date} 00:00:00`).toLocaleDateString(locale);
      }
    }
  });
}

TableRender.prototype.formatCell = function(value, type = 'text', defaultValue = '') {
    const formatter = this.get('_formatters')[type];

    if(!value) {
        return defaultValue || '';
    }

    if(!formatter) {
        return value;
    }

    return formatter.format(value);
}

TableRender.prototype.onRenderCell = function(handler = (element = null, data = {}) => {}) {
    this.set('onRenderCell', handler);
}

TableRender.prototype.onSelectRow = function(handler = (element) => {}) {
    this.set('onSelectRow', handler);
}

TableRender.prototype.render = function() {
    const table = this.get('table');
    const onRenderCell = this.get('onRenderCell');
    const onSelectRow = this.get('onSelectRow');

    if(!table) {
        return;
    }

    const tbody = table.querySelector('tbody');
    const tfooter = table.querySelector('tfooter');
    const templateRow = table.querySelector('tbody .table-row-template').cloneNode(true);

    (this.get('data').body || []).forEach(data => {
        const tableRow = templateRow.cloneNode(true);
        const tableCells = Array.from(tableRow.querySelectorAll('td[data-column]'));

        tableCells.forEach(tableCell => {
            if(onRenderCell && typeof onRenderCell === 'function') {
                onRenderCell(tableCell, data);
            }
        });

        if(onSelectRow && typeof onSelectRow === 'function') {
            tableRow.addEventListener('click', e => {
                this.get('onSelectRow')(e.target);
            });
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
