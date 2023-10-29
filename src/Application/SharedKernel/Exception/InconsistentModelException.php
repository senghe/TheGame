<?php

namespace TheGame\Application\SharedKernel\Exception;

use RuntimeException;

final class InconsistentModelException extends RuntimeException
{
    public function __construct(
        string $message
    ) {
    }
}
