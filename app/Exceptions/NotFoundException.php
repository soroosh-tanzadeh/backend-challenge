<?php

namespace App\Exceptions;

use RuntimeException;

class NotFoundException extends RuntimeException
{
    public function __construct(string $message)
    {
        $this->message = $message;
    }
}
