<?php

namespace App\Exceptions;

use RuntimeException;

class ExpiredChargeCodeException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Invalid ChargeCode");
    }
}
