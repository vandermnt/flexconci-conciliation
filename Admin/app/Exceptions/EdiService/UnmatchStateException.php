<?php

namespace App\Exceptions\EdiService;
use Illuminate\Support\Facades\Log;

use App\Exceptions\EdiService\EdiServiceException;

/** Reference: https://auth0.com/docs/protocols/state-parameters */
class UnmatchStateException extends EdiServiceException
{
}
