<?php

namespace App\EdiServices;

use App\EdiServices\EdiService;
use App\Contracts\EdiServices\IEdiAuthorize;

abstract class EdiAuthorize extends EdiService implements IEdiAuthorize {
  public function authenticate($data = []) {}

  public function authorize($params) {}
}
