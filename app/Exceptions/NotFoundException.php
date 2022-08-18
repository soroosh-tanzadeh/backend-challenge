<?php

namespace App\Exceptions;

use RuntimeException;

class NotFoundException extends RuntimeException
{
    public readonly string $resource;

    public function __construct(string $message, $resource = null)
    {
        $this->message = $message;
        $this->resource = $resource;
    }
}
