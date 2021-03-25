<?php

namespace App\EdiServices\Cielo;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class CieloEdiService {
  protected $service = 'cielo';

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

  public function getAuthHeader() {
    $clientCredentials = $this->getClientId().':'.$this->getClientSecret();
    return 'Basic '.base64_encode($clientCredentials);
  }

  public function getAuthorizationHeader($token) {
    return 'Bearer '.$token;
  }
}
