function Formatter(options = {}) {
  this.proxy = new Proxy({
    locale: options.locale || 'en-US',
    formatterOptions: {
      currency: options.currencyOptions || {
        type: 'USD'
      }
    },
    currency: null,
    number: null,
    date: null,
  }, formatterHandler());
}

function formatterHandler() {
  return {
    set: function(target, name, value) {
      target[name] = value;
    },
    get: function(target, name) {
      return target[name];
    }
  }
}

Formatter.prototype.get = function(prop = null) {
  if(!prop) {
    return this.proxy;
  }
  
  return this.proxy[prop];
}

Formatter.prototype.set = function(prop = '', value = null) {
  this.proxy[prop] = value;
}

Formatter.prototype.format = function(type = 'text', value = '', defaultValue = '') {
  const valueToFormat = value || defaultValue;
  const formatter = this.get(type) || this._createFormatter(type);

  if(!formatter) {
    return valueToFormat;
  }

  return (formatter.format(valueToFormat) || null);
}

Formatter.prototype.setLocale = function(locale) {
  this.set('locale', locale);
  this.set('currency', this._createCurrencyFormatter());
  this.set('number', this._createNumberFormatter());
  this.set('date', this._createDateFormatter());

  return this;
}

Formatter.prototype.setFormatterOptions = function(type, options) {
  this.get('formatterOptions')[type] = options;

  return this;
}

Formatter.prototype._createFormatter = function(type) {
  let formatter = null;
  switch(type) {
    case 'currency':
      formatter = this._createCurrencyFormatter();
      break;
    
    case 'number':
      formatter = this._createNumberFormatter();
      break;

    case 'date':
      formatter = this._createDateFormatter();
      break;
  }

  return formatter;
}

Formatter.prototype._createCurrencyFormatter = function() {
  const currencyFormatter = new Intl.NumberFormat(this.get('locale'), {
    style: 'currency',
    currency: this.get('formatterOptions').currency.type,
  });

  this.set('currency', currencyFormatter);
  return currencyFormatter;
}

Formatter.prototype._createNumberFormatter = function() {
  const numberFormatter = new Intl.NumberFormat(this.get('locale'), {
    maximumFractionDigits: 2,
  });

  this.set('number', numberFormatter);
  return numberFormatter;
}

Formatter.prototype._createDateFormatter = function() {
  const errorRegex = new RegExp(/invalid/gi);
  const locale = this.get('locale');
  const dateFormatter = {
    format: function(date) {
      const formattedDate = new Date(`${date} 00:00:00`).toLocaleDateString(locale);
      return errorRegex.test(formattedDate) ? null : formattedDate;
    },
  }

  this.set('date', dateFormatter);
  return dateFormatter;
}