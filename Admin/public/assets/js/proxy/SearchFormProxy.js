function SearchFormProxy(options = {}) {
  this.proxy = new Proxy({
    form: options.form || '',
    checker: options.checker || new Checker(),
    inputs: options.inputs || [],
    onSubmitHandler: function(event) {},
  }, searchFormProxyHandler());
}

SearchFormProxy.prototype.get = function(prop = null) {
  if(!prop) {
    return this.proxy;
  }
  
  return this.proxy[prop];
}

SearchFormProxy.prototype.set = function(prop = '', value = null) {
  this.proxy[prop] = value;
}

SearchFormProxy.prototype.getInput = function(name) {
  const form = this.get('form') ? this.get('form') : { id: '' }
  const formId = typeof form === 'string' ? form : `#${form.id}` || '';

  return document.querySelector(`${formId} input[name="${name}"]`);
}

SearchFormProxy.prototype.clear = function(callback = () => {}) {
  const form = this.get('form');
  if(!form) {
    return;
  }

  form.reset();

  if(callback && typeof callback === 'function') {
    callback();
  }
}

SearchFormProxy.prototype.onSubmit = function(onSubmitHandler = (event) => {}) {
  this.set('onSubmitHandler', onSubmitHandler);
}

SearchFormProxy.prototype.serialize = function() {
  const inputValues = this.get('inputs').reduce((data, inputDOM) => {
    if(!inputDOM) {
      return data;
    }

    const value = inputDOM.value;
    if(value) {
      data[inputDOM.name] = value;
    }

    return data;
  }, {})

  return {
    ...inputValues,
    ...checker.serialize(),
  }
}

function searchFormProxyHandler() {
  return {
    set: function(target, name, value) {
      target[name] = value;
    },
    get: function(target, name) {
      if(name === 'form') {
        if(typeof target[name] !== 'string') {
          return target[name];
        }

        return document.querySelector(target[name]);
      }

      if(name === 'inputs') {
        const inputs = (target[name] || []).map(input => {
          if(typeof input !== 'string') {
            return input;
          }

          const form = target.form ? target.form : { id: '' }
          const formId = typeof form === 'string' ? form : `#${form.id}` || '';

          return document.querySelector(`${formId} input[name="${input}"]`)
        });

        return inputs;
      }

      return target[name];
    },
  }
}