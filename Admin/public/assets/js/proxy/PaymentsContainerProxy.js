function PaymentsContainerProxy(options = {}) {
  this.proxy = new Proxy({
    id: options.id || '',
    active: 'search',
    search: new PaymentsProxy({
      id: options.id || 'search',
      type: 'search',
    }),
    filtered: new PaymentsProxy({
      id: options.id || 'filtered',
      type: 'filtered',
    }),
    client: {
      api: new API(),
      config: {
        headers: {}
      },
    },
    _links: options.links || { filter: null, search: null },
    _events: {},
    paginationConfig: {},
    paginationNavigateHandler: () => {}
  }, paymentsContainerProxyHandler());
}

function paymentsContainerProxyHandler() {
  return {
    set: function(target, name, value) {
      if(['_events', 'client'].includes(name)) {
        return;
      }
      
      target[name] = value;
    },
    get: function(target, name) {
      if(name === 'data') {
        return target[target.active];
      }
      if(name === 'api') {
        return target.client[name];
      }

      return target[name];
    }
  }
}

PaymentsContainerProxy.prototype.get = function(prop = null) {
  if(!prop) {
    return this.proxy;
  }
  
  return this.proxy[prop];
}

PaymentsContainerProxy.prototype.set = function(prop = '', value = null) {
  this.proxy[prop] = value;
}

PaymentsContainerProxy.prototype.onEvent = function(eventName, callback = () => {}) {
  this.get('_events')[eventName] = callback; 
}

PaymentsContainerProxy.prototype.setupApi = function(options = { headers: {} }) {
  this.get('client').config.headers = options.headers || {};
}

PaymentsContainerProxy.prototype.setPaginationConfig = function(options = {}, onNavigateHandler = () => {}) {
  this.set('paginationConfig', options);
  this.set('paginationNavigateHandler', onNavigateHandler);
}

PaymentsContainerProxy.prototype._fetch = async function(urlBase, options = {
  method: 'GET',
  params: {},
  body: {},
  headers: {},
}) {
  const onBeforeFetch = this.get('_events').beforeFetch;
  const onFetch = this.get('_events').fetch;
  const onFail = this.get('_events').fail;
  
  if(onBeforeFetch && typeof onBeforeFetch === 'function') {
    onBeforeFetch();
  }

  try {
    const response = await this.get('api').sendRequest(urlBase, {
      ...options,
      headers: {
        ...options.headers,
        ...this.get('client').config.headers,
      },
      body: JSON.stringify(options.body)
    });

    const payments = createPaymentsProxy({
      payments: response.recebimentos.data,
      pagination: response.recebimentos,
      totals: response.totais,
    }, { id: this.get('id') });

    payments.get('pagination').setOptions({ ...this.get('paginationConfig') });
    payments.get('pagination').setNavigateHandler(this.get('paginationNavigateHandler'));
    
    if(onFetch && typeof onFetch === 'function') {
      onFetch(payments);
    }

    return payments;
  } catch(err) {
    if(onFail && typeof onFail === 'function') {
      onFail(err);
    }

    throw err;
  }
}

PaymentsContainerProxy.prototype.search = async function(options) {
  const url = this.get('_links').search || options.urlBase;
  const onSearch = this.get('_events').search;
  
  try {
    const payments = await this._fetch(url, {
      ...options,
      method: 'POST'
    });

    this.set('search', payments);
    this.get('search').set('id', this.get('id'));
    this.get('search').set('type', 'search')
    this.toggleActiveData('search');

    if(onSearch && typeof onSearch === 'function') {
      onSearch(payments);
    }

    return payments;
  } catch(err) {
    throw err;
  }
}

PaymentsContainerProxy.prototype.filter = async function(options) {
  const url = this.get('_links').filter || options.urlBase;
  const onFilter = this.get('_events').filter;
  
  try {
    const payments = await this._fetch(url, {
      ...options,
      method: 'POST'
    });

    this.set('filtered', payments);
    this.get('filtered').set('id', this.get('id'));
    this.get('filtered').set('type', 'filter')
    this.toggleActiveData('filtered');

    if(onFilter && typeof onFilter === 'function') {
      onFilter(payments);
    }

    return payments;
  } catch(err) {
    throw err;
  }
}

PaymentsContainerProxy.prototype.toggleActiveData = function(currentActive) {
  this.set('active', currentActive);
}

PaymentsContainerProxy.prototype.dispatchEvent = function(eventName, params = {}) {
  const eventHandler = this.get('_events')[eventName];
  
  if(eventHandler && typeof eventHandler === 'function') {
    eventHandler(params);
  }

  return;
}