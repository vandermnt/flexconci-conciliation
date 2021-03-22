<?php

namespace App\EdiServices\Cielo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use App\Exceptions\EdiService\UnmatchStateException;
use App\Exceptions\EdiService\AuthorizationDeniedException;
use App\Exceptions\EdiService\EdiServiceException;

class CieloEdiAuthorize {
  private $service = 'cielo';

  public function getClientId() {
    return config('ediservice.'.$this->service.'.client_id');
  }

  public function getClientSecret() {
    return config('ediservice.'.$this->service.'.client_secret');
  }

  public function getAuthUrl() {
    return config('ediservice.'.$this->service.'.auth_url');
  }

  public function getAuthHeader() {
    return 'Basic '.base64_encode($this->getClientId().':'.$this->getClientSecret());
  }

  public function getBaseUrl() {
    return config('ediservice.'.$this->service.'.base_url');
  }

  public function generateState() {
    return bin2hex(random_bytes(16));
  }

  public function authenticate($params = []) {
    $queryParams = Arr::except($params, ['client_id', 'client_secret', 'mode']);
    $queryParams = Arr::collapse([
      [
        'mode' => 'redirect',
        'client_id' => $this->getClientId(),
        'redirect_uri' => route('cielo.callback'),
      ],
      $params,
    ]);

    $queryString = Arr::query($queryParams);

    return $this->getAuthUrl().'?'.$queryString;
  }

  public function handleAuthError($states, $error) {
    $sessionState = $states['sessionState'];
    $returnedState = $states['returnedState'];

    throw_if($sessionState !== $returnedState, new UnmatchStateException('O estado entre requisições não são correspondentes.'));
    throw_if($error === 'access_denied', new AuthorizationDeniedException('Acesso negado.'));
    throw_if($error, new EdiServiceException('Um problema ocorreu. Tente novamente!'));
  }

  public function authorize($code) {
    $response = Http::withHeaders([
      'Content-Type' => 'application/json',
      'Authorization' => $this->getAuthHeader(),
    ])
    ->post($this->getBaseUrl().'/consent/v1/oauth/access-token', [
      'grant_type' => 'authorization_code',
      'code' => $code
    ]);

    throw_if($response->failed(), new EdiServiceException('Autorização não concedida. Falha ao gerar chave de acesso!'));

    $data = $response->json();
    return [
      'status' => 'success',
      'data' => [
        'access_token' => $data['access_token']
      ]
    ];
  }
}
