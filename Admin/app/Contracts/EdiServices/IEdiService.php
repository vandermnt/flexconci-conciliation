<?php

namespace App\Contracts\EdiServices;

interface IEdiService {
  public function getClientId();

  public function getClientSecret();

  public function getAuthUrl();

  public function getBaseUrl();

  public function getAuthHeader();

  public function getAuthorizationHeader($headerValue);
}
