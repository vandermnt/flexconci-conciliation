<?php

namespace App\EdiServices\Cielo;
use Illuminate\Support\Arr;

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

  public function getBaseUrl() {
    return config('ediservice.'.$this->service.'.base_url');
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
}
