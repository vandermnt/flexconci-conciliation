function handleError(response) {
  toggleElementVisibility('#js-error-alert');
  const errorMessageElement = document.querySelector('#js-error-alert p');
  errorMessageElement.textContent = response.error;

  if(!response.retry) return;

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

function redirectToHome(e) {
  redirectTo(e.target.dataset.redirectTo);
}

function onLoadHandler() {
  swal('O Registro EDI está iniciando.', 'Aguarde um momento. O processo pode levar algum tempo para ser concluído.', 'warning');

  toggleElementVisibility('#cielo-loader');

  sendRegisterRequest()
    .then(response => {
      console.log(response);
      if(response.error) {
        handleError(response);
        return;
      }

      updateLists(response);
    })
    .finally(() => {
      document.querySelector('#js-next').removeAttribute('disabled');
      toggleElementVisibility('#cielo-loader');
    })

  recreateNode('#js-next').addEventListener('click', redirectToHome);
}

window.addEventListener('load', onLoadHandler);
