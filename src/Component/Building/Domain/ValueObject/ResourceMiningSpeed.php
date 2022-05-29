<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\ValueObject;

use App\Component\SharedKernel\Domain\Entity\ResourceInterface;

final class ResourceMiningSpeed implements ResourceMiningSpeedInterface
{
    private ResourceInterface $resource;

    private int $speed;

    public function __construct(
        ResourceInterface $resource,
        int $speed
    ) {
        $this->resource = $resource;
        $this->speed = $speed;
    }

    public function getResource(): ResourceInterface
    {
        return $this->resource;
    }

    public function getResourceCode(): string
    {
        return $this->resource->getCode();
    }

    public function getSpeed(): int
    {
        return $this->speed;
    }
}