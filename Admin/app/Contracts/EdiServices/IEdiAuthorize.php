<?php

namespace App\Contracts\EdiServices;

interface IEdiAuthorize {
  public function authenticate($data = []);

  public function authorize($params);
}
