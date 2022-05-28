<?php

declare(strict_types=1);

namespace App\Component\SharedKernel\Domain;

use App\Component\SharedKernel\DoctrineEntityTrait;

class Resource implements ResourceInterface
{
    use DoctrineEntityTrait;

    protected string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}