<?php

namespace App\EdiServices\Cielo;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use App\EdiServices\Cielo\CieloEdiService;
use App\Exceptions\EdiService\UnmatchStateException;
use App\Exceptions\EdiService\AuthorizationDeniedException;
use App\Exceptions\EdiService\EdiServiceException;

class CieloEdiAuthorize extends CieloEdiService {
  public function authenticate($params = []) {
    $queryParams = collect($params)
      ->except([
        'client_id',
        'client_secret',
        'mode'
      ])
      ->merge([
        'mode' => 'redirect',
        'client_id' => $this->getClientId(),
        'redirect_uri' => route('cielo.callback'),
      ])
      ->toArray();

    $queryString = Arr::query($queryParams);

    return $this->getAuthUrl().'?'.$queryString;
  }

  public function authorize($code) {
    try {
      $accessTokenUrl = $this->getBaseUrl().'/consent/v1/oauth/access-token';
      $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => $this->getAuthHeader(),
      ])
      ->post($accessTokenUrl, [
        'grant_type' => 'authorization_code',
        'code' => $code
      ]);

      $response->throw();

      $data = $response->json();
      return [
        'status' => 'success',
        'data' => $data
      ];
    } catch(Exception $exception) {
      throw new EdiServiceException('Autorização não concedida. Falha ao gerar chave de acesso!');
    }
  }

  public function handleAuthError($states, $error) {
    list($sessionState, $returnedState) = [$states['sessionState'], $states['returnedState']];

    throw_if($sessionState !== $returnedState, new UnmatchStateException('O estado entre requisições não são correspondentes.'));
    throw_if($error === 'access_denied', new AuthorizationDeniedException('Acesso negado.'));
    throw_if($error, new EdiServiceException('Um problema ocorreu. Tente novamente!'));
  }

  public function generateState() {
    return bin2hex(random_bytes(16));
  }
}
