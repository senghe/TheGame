<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Entity;

use App\Component\SharedKernel\DoctrineEntityTrait;

class OperationValue implements OperationValueInterface
{
    use DoctrineEntityTrait;

    private ResourceInterface $resource;

    private int $value;

    public function __construct(
        ResourceInterface $resource,
        int $value
    ) {
        $this->resource = $resource;
        $this->value = $value;
    }

    public function isFor(ResourceInterface $resource): bool
    {
        return $this->resource->is($resource);
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
