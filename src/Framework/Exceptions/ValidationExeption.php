<?php

declare(strict_types=1);

namespace Framework\Exceptions;

use RuntimeException;
use Throwable;

class ValidationExeption extends RuntimeException
{
    public function __construct(public array $errors, int $code = 422)
    {
        parent::__construct(code: $code);
    }
}