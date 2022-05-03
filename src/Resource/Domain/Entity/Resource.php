<?php

declare(strict_types=1);

namespace App\Resource\Domain\Entity;

use App\SharedKernel\DoctrineEntityTrait;

class Resource implements ResourceInterface
{
    use DoctrineEntityTrait;

    private ?string $code;

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function is(ResourceInterface $resource): bool
    {
        return $this->getCode() === $resource->getCode();
    }
}
