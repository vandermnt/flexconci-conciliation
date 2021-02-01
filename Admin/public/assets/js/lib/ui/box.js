function Box(options = {}) {
  this.proxy = new Proxy({
    element: options.element || '#js-box',
    value: options.value || (options.defaultValue === 0 ? '0' : '') || '',
    defaultValue: options.defaultValue || (options.defaultValue === 0 ? '0' : '') || '',
    format: options.format || 'text',
    formatter: options.formatter || new Formatter({
      locale: options.locale || 'en-US',
      currencyOptions: {
        type: 'USD',
      }
    }),
  }, boxHandler());
}

function boxHandler() {
  return {
    set: function(target, name, value) {
      target[name] = value;
    },
    get: function(target, name) {
      if(name === 'element') {
        if(typeof element === 'string') {
          return document.querySelector(target[name]);
        }
      }

      return target[name];
    }
  }
}

Box.prototype.get = function(prop = null) {
  if(!prop) {
    return this.proxy;
  }

  return this.proxy[prop];
}

Box.prototype.set = function(prop = '', value = null) {
  this.proxy[prop] = value;
}

Box.prototype.render = function() {
  if(!this.get('element')) {
    return;
  }
  
  const box = this.get('element');
  const formatter = this.get('formatter');
  const value = this.get('value');
  const defaultValue = this.get('defaultValue');
  const format = this.get('format');

  const boxContent = box.querySelector('.content');
  boxContent.textContent = formatter.format(format, value, defaultValue);
}