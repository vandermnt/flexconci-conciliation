<?php

namespace App\EdiServices;

use App\Contracts\EdiServices\IEdiService;

abstract class EdiService implements IEdiService {
  protected $service = '';

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

  public function getAuthHeader() {}

  public function getAuthorizationHeader($headerValue) {}
}
