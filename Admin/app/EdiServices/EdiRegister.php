<?php

namespace App\EdiServices;

use App\EdiServices\EdiService;
use App\Contracts\EdiServices\IEdiRegister;

abstract class EdiRegister extends EdiService implements IEdiRegister {
  public function invoke($accessKey, $params) {}
}
