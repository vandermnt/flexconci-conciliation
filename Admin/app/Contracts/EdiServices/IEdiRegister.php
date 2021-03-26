<?php

namespace App\Contracts\EdiServices;

interface IEdiRegister {
  public function invoke($accessKey, $params);
}
