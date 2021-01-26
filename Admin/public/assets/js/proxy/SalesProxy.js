function SalesProxy(values) {
  this.proxy = new Proxy({
    id: values.id || '',
    type: values.type || '',
    sales: values.sales || [],
    totals: values.totals || {},
    pagination: values.pagination || new Pagination([], {
      currentPage: 1,
      total: 0,
    }),
  }, salesProxyHandler());
}

function salesProxyHandler() {
  return {
    set: function(target, name, value) {
      if(['id', 'type'].includes(name) && typeof value === 'string') {
        target[name] = value;
        return;
      }

      target[name] = value;
    },
    get: function(target, name) {
      return target[name];
    }
  }
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

SalesProxy.prototype._defaultSerializer = function(data = {}) {
  const sales = [...(data.sales || [])];
  const pagination = { ...(data.pagination || {}) };
  const totals = { ...(data.totals || {}) };
  delete pagination.data;

  const serializedData = {
    sales,
    pagination: pagination,
    totals,
  };

  return serializedData;
}

SalesProxy.prototype._serialize = function(data = {}) {
  if(!SalesProxy.prototype._serializer) {
    return SalesProxy.prototype._defaultSerializer(data);
  }

  return SalesProxy.prototype._serializer(data);
}

function setSalesProxySerializer(serializer = null) {
  if(serializer && typeof serializer === 'function') {
    SalesProxy.prototype._serializer = serializer;
  }

  return SalesProxy;
}

function createSalesProxy(data, proxyValues) {
  const serializedData = SalesProxy.prototype._serialize(data);
  return new SalesProxy({
    proxyValues,
    ...serializedData,
    pagination: createPagination(serializedData.sales, serializedData.pagination)
  });
}