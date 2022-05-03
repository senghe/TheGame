<?php

declare(strict_types=1);

namespace App\Resource\Domain\Entity;

use App\SharedKernel\DoctrineEntityTrait;

class OperationValue implements OperationValueInterface
{
    use DoctrineEntityTrait;

    private int $value;

    private ?ResourceInterface $resource;

    private ?OperationInterface $operation;

    public function isFor(ResourceInterface $resource): bool
    {
        return $this->resource->is($resource);
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
