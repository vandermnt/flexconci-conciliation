function SalesContainerProxy(options = {}) {
  this.proxy = new Proxy({
    id: options.id || '',
    active: 'search',
    search: new SalesProxy({
      id: options.id || 'search',
      type: 'search',
    }),
    filtered: new SalesProxy({
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
  }, salesContainerProxyHandler());
}

function salesContainerProxyHandler() {
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

SalesContainerProxy.prototype.get = function(prop = null) {
  if(!prop) {
    return this.proxy;
  }
  
  return this.proxy[prop];
}

SalesContainerProxy.prototype.set = function(prop = '', value = null) {
  this.proxy[prop] = value;
}

SalesContainerProxy.prototype.onEvent = function(eventName, callback = () => {}) {
  this.get('_events')[eventName] = callback; 
}

SalesContainerProxy.prototype.setupApi = function(options = { headers: {} }) {
  this.get('client').config.headers = options.headers || {};
}

SalesContainerProxy.prototype.setPaginationConfig = function(options = {}, onNavigateHandler = () => {}) {
  this.set('paginationConfig', options);
  this.set('paginationNavigateHandler', onNavigateHandler);
}

SalesContainerProxy.prototype._fetch = async function(urlBase, options = {
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

    const sales = createSalesProxy({
      sales: response.vendas.data,
      pagination: response.vendas,
      totals: response.totais,
    }, { id: this.get('id') });

    sales.get('pagination').setOptions({ ...this.get('paginationConfig') });
    sales.get('pagination').setNavigateHandler(this.get('paginationNavigateHandler'));
    
    if(onFetch && typeof onFetch === 'function') {
      onFetch(sales);
    }


  
    return sales;
  } catch(err) {
    if(onFail && typeof onFail === 'function') {
      onFail(err);
    }

    throw err;
  }
}

SalesContainerProxy.prototype.search = async function(options) {
  const url = this.get('_links').search || options.urlBase;
  const onSearch = this.get('_events').search;
  
  try {
    const sales = await this._fetch(url, {
      ...options,
      method: 'POST'
    });

    this.set('search', sales);
    this.get('search').set('id', this.get('id'));
    this.get('search').set('type', 'search')
    this.toggleActiveData('search');

    if(onSearch && typeof onSearch === 'function') {
      onSearch(sales);
    }

    return sales;
  } catch(err) {
    throw err;
  }
}

SalesContainerProxy.prototype.filter = async function(options) {
  const url = this.get('_links').filter || options.urlBase;
  const onFilter = this.get('_events').filter;
  
  try {
    const sales = await this._fetch(url, {
      ...options,
      method: 'POST'
    });

    this.set('filtered', sales);
    this.get('filtered').set('id', this.get('id'));
    this.get('filtered').set('type', 'filter')
    this.toggleActiveData('filtered');

    if(onFilter && typeof onFilter === 'function') {
      onFilter(sales);
    }

    return sales;
  } catch(err) {
    throw err;
  }
}

SalesContainerProxy.prototype.toggleActiveData = function(currentActive) {
  this.set('active', currentActive);
}

SalesContainerProxy.prototype.dispatchEvent = function(eventName, params = {}) {
  const eventHandler = this.get('_events')[eventName];
  
  if(eventHandler && typeof eventHandler === 'function') {
    eventHandler(params);
  }

  return;
}