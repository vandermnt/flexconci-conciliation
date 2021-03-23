function handleError(error) {
  toggleElementVisibility('#js-error-alert');
  const errorMessageElement = document.querySelector('#js-error-alert p');
  errorMessageElement.textContent = error;

  return;
}
async function sendRegisterRequest() {
  const accessToken = document.querySelector('input#js-access-token').value;
  const registerUrl = document.querySelector('input#js-access-token').dataset.registerUrl;

  const url = api.urlBuilder(registerUrl, {access_token: accessToken});
  const response = await api.get(url)
    .catch(err => {
      throw err
    });
  return response;
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

function onLoadHandler() {
  swal('O Registro EDI está iniciando.', 'Aguarde um momento. O processo pode levar algum tempo para ser concluído.', 'warning');

  toggleElementVisibility('#cielo-loader');

  sendRegisterRequest()
    .then(response => {
      if(response.error) {
        handleError(response.error);
        return;
      }
      updateLists(response);
    })
    .finally(() => {
      document.querySelector('#js-next').removeAttribute('disabled');
      toggleElementVisibility('#cielo-loader');
    })
}

window.addEventListener('load', onLoadHandler);
document.querySelector('#js-next').addEventListener('click', (e) => {
  redirectTo(e.target.dataset.redirectTo);
})
