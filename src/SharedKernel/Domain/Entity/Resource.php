<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\Entity;

use App\SharedKernel\DoctrineEntityTrait;

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