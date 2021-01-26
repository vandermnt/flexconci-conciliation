function TableRender(options) {
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
    }
  }, tableRenderHandler());
}

SalesProxy.prototype.get = function(prop = null) {
  if(!prop) {
    return this.proxy;
  }
  
  return this.proxy[prop];
}

SalesProxy.prototype.set = function(prop = '', value = null) {
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

function tableRenderHandler() {
  return {
    set: function(target, name, value) {},
    get: function(target, name) {
      if(['currency', 'number', 'date'].includes(name)) {
        return target._formatters[name];
      }
    }
  }
}