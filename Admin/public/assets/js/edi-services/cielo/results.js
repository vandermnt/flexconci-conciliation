function handleError(error) {
  toggleElementVisibility('#js-error-alert');
  const errorMessageElement = document.querySelector('#js-error-alert p');
  errorMessageElement.textContent = error;

  const retryButton = recreateNode('#js-next');
  const buttonSpan = retryButton.querySelector('span');
  const buttonIcon = retryButton.querySelector('i');
  buttonSpan.textContent = 'TENTAR NOVAMENTE';
  buttonIcon.classList.remove('fa-check');
  buttonIcon.classList.add('fa-undo');
  retryButton.addEventListener('click', reload);
  return;
}

function reload() {
  window.location.reload();
}

function getAccessToken() {
  return document.querySelector('input#js-access-token').value;
}

function getBaseUrl() {
  return document.querySelector('input#js-access-token').dataset.baseUrl;
}

function getCheckoutUrl() {
  return document.querySelector('input#js-access-token').dataset.checkoutUrl;
}

function getMerchantEmail() {
  return document.querySelector('input#js-access-token').dataset.merchantEmail;
}

async function requestEndpoint(baseUrl = '', {method = 'GET', params = {}, body = {}, headers = {} }) {
  const statusErrors = {
    '502': 'A comunicação com a Cielo falhou (Connection Timeout). Tente novamente!'
  }

  const requestData = {
    method,
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      ...headers,
    },
  }

  if(method !== 'GET') {
    requestData.body = JSON.stringify(body);
  }

  const url = api.urlBuilder(baseUrl, params);
  const response = await fetch(url, {
    ...requestData
  }).catch(err => {
    throw Error('Um erro de conexão ocorreu. Verifique sua conexão com a internet e tente novamente!');
  });

  if(!response.ok) {
    throw Error(statusErrors[response.status] || 'Um erro ocorreu. Tente novamente!');
  }

  return await response.json().catch(err => {
    throw new Error('Um erro ocorreu. Tente novamente!')
  });
}

async function getAllMerchants() {
  const accessToken = getAccessToken();
  const baseUrl = getBaseUrl();

  const response = await requestEndpoint(`${baseUrl}/merchantgroup`, {
    headers: {
      'Authorization': `Bearer ${accessToken}`,
    }
  }).catch(err => { throw err; });

  return response;
}

async function getMainMerchants() {
  const accessToken = getAccessToken();
  const baseUrl = getBaseUrl();

  const response = await requestEndpoint(`${baseUrl}/mainmerchants`, {
    headers: {
      'Authorization': `Bearer ${accessToken}`,
    }
  }).catch(err => { throw err; });

  return response;
}

async function registerMerchants(merchants = []) {
  if(merchants.length === 0) return { merchants: merchants };

  const accessToken = getAccessToken();
  const baseUrl = getBaseUrl();
  const merchantEmail = getMerchantEmail();
  const merchantIds = merchants.map(merchant => merchant.merchantID);

  await requestEndpoint(`${baseUrl}/registers`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${accessToken}`,
    },
    body: {
      merchantEmail,
      merchants: merchantIds,
      type: [
        'SELL',
        'PAYMENT',
        'ANTECIPATION_CIELO',
        'ASSIGNMENT',
        'BALANCE',
        'ANTECIPATION_ALELO',
      ]
    }
  }).catch(err => { throw err; });

  return { merchants: merchantIds }
}

async function duplicateMainMerchants(mainMerchants = []) {
  if(mainMerchants.length === 0) return mainMerchants;

  const accessToken = getAccessToken();
  const baseUrl = getBaseUrl();

  const responses = await Promise.all(
    mainMerchants.map(mainMerchant => {
      return requestEndpoint(`${baseUrl}/`, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${accessToken}`,
        },
        body: {
          mainMerchantID: mainMerchant.mainMerchantID,
          registerID: mainMerchant.registerID,
          merchants: mainMerchant.merchants,
          type: mainMerchant.type,
        }
      })
    })
  ).catch(err => {
    throw err;
  });

  const duplicatedMainMerchants = responses.map(response => ({
      mainMerchantID: response.mainMerchantID,
      merchants: response.merchants,
    })
  );

  return duplicatedMainMerchants;
}

async function registerCheckout(successfullMerchantIds = []) {
  const checkoutUrl = getCheckoutUrl();

  const response = await requestEndpoint(checkoutUrl, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
    },
    body: {
      merchants: successfullMerchantIds || [],
    }
  }).catch(err => {
    throw err;
  });

  return response;
}

function groupByStatus(merchants = [], statusKey = 'status') {
  return merchants.reduce((groups, merchant) => {
    const status = merchant[statusKey].toLowerCase();
    groups[status] = [...(groups[status] || []), merchant];

    return groups;
  }, { available: [], unavailable: [] });
}

async function dispatch() {
  try {
    const merchants = await getAllMerchants().catch(err =>  { throw err; });
    const registeredMerchants = await registerMerchants(
      groupByStatus(merchants.branches).available
    ).catch(err =>  { throw err; });

    const mainMerchants = await getMainMerchants().catch(err =>  { throw err; });
    const groupedMainMerchants = groupByStatus(mainMerchants, 'editStatus');
    const duplicatedMainMerchants = await duplicateMainMerchants(groupedMainMerchants.available).catch(err =>  { throw err; });
    const duplicatedMerchants = duplicatedMainMerchants
      .reduce((data, duplicated) => [...data, ...duplicated.merchants], [])

    const allMerchantsIds = Array.from(
      new Set([
        ...registeredMerchants.merchants,
        ...duplicatedMerchants,
      ])
    )
      .map(id => ({ merchantID: id }));


    const data = {
      registeredMerchants: allMerchantsIds,
      duplicatedMerchants: {
        successfull: duplicatedMainMerchants,
        failed: groupedMainMerchants.unavailable
      }
    };

    const checkout = await registerCheckout(data.registeredMerchants).catch(err =>  { throw err; });

    updateLists(data);
    return data;
  } catch(err) {
    throw err;
  }
}

function updateLists({ registeredMerchants,  duplicatedMerchants }) {
  const registeredIds = registeredMerchants.map(merchant => merchant.merchantID);
  const duplicatedMainIds = duplicatedMerchants.successfull.map(merchant => merchant.mainMerchantID);
  const notDuplicatedMainIds = duplicatedMerchants.failed.map(merchant => merchant.mainMerchantID);

  updateList("#js-estabelecimentos-registrados", registeredIds);
  updateList("#js-matrizes-duplicadas", duplicatedMainIds);
  updateList("#js-matrizes-nao-duplicadas", notDuplicatedMainIds);
}

function updateList(elementId, data) {
  toggleElementVisibility(elementId);
  const ul = document.querySelector(`${elementId} ul`);
  const p = document.querySelector(`${elementId} p`);
  const liTemplate = ul.querySelector(`li[data-template]`).cloneNode(true);

  p.textContent += ` ${data.length} registro(s)`;

  ul.innerHTML = "";
  ul.appendChild(liTemplate);
  data.forEach(value => {
    const liElement = liTemplate.cloneNode(true);
    liElement.textContent = value;
    liElement.classList.remove('hidden');
    ul.appendChild(liElement);
  });
}

function redirectToHome(e) {
  redirectTo(e.target.dataset.redirectTo);
}

function onLoadHandler() {
  swal('O Registro EDI está iniciando.', 'Aguarde um momento. O processo pode levar algum tempo para ser concluído.', 'warning');

  toggleElementVisibility('#cielo-loader');

  dispatch()
    .then(response => console.log(response))
    .catch(error => {
      handleError(error.message);
     })
    .finally(() => {
      document.querySelector('#js-next').removeAttribute('disabled');
      toggleElementVisibility('#cielo-loader');
    })

  recreateNode('#js-next').addEventListener('click', redirectToHome);
}

window.addEventListener('load', onLoadHandler);
