<?php

namespace App\EdiServices\Cielo;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use App\EdiServices\EdiService;

class CieloEdiService extends EdiService {
  protected $service = 'cielo';

  public function getAuthHeader() {
    $clientCredentials = $this->getClientId().':'.$this->getClientSecret();
    return 'Basic '.base64_encode($clientCredentials);
  }

  public function getAuthorizationHeader($token) {
    return 'Bearer '.$token;
  }
}
