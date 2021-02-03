function PaymentsProxy(values) {
  this.proxy = new Proxy({
    id: values.id || '',
    type: values.type || '',
    payments: values.payments || [],
    totals: values.totals || {},
    pagination: values.pagination || new Pagination([], {
      currentPage: 1,
      total: 0,
    }),
  }, paymentsProxyHandler());
}

function paymentsProxyHandler() {
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

PaymentsProxy.prototype.get = function(prop = null) {
  if(!prop) {
    return this.proxy;
  }
  
  return this.proxy[prop];
}

PaymentsProxy.prototype.set = function(prop = '', value = null) {
  this.proxy[prop] = value;
}

PaymentsProxy.prototype._defaultSerializer = function(data = {}) {
  const payments = [...(data.payments || [])];
  const pagination = { ...(data.pagination || {}) };
  const totals = { ...(data.totals || {}) };
  delete pagination.data;

  const serializedData = {
    payments,
    pagination: pagination,
    totals,
  };

  return serializedData;
}

PaymentsProxy.prototype._serialize = function(data = {}) {
  if(!PaymentsProxy.prototype._serializer) {
    return PaymentsProxy.prototype._defaultSerializer(data);
  }

  return PaymentsProxy.prototype._serializer(data);
}

function setPaymentsProxySerializer(serializer = null) {
  if(serializer && typeof serializer === 'function') {
    PaymentsProxy.prototype._serializer = serializer;
  }

  return PaymentsProxy;
}

function createPaymentsProxy(data, proxyValues) {
  const serializedData = PaymentsProxy.prototype._serialize(data);
  return new PaymentsProxy({
    proxyValues,
    ...serializedData,
    pagination: createPagination(serializedData.payments, serializedData.pagination)
  });
}