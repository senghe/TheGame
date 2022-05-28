<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\ValueObject;

use App\Component\SharedKernel\Domain\ResourceInterface;

final class ResourceAmount implements ResourceAmountInterface
{
    private ResourceInterface $resource;

    private int $amount;

    public function __construct(
        ResourceInterface $resource,
        int $amount
    ) {
        $this->resource = $resource;
        $this->amount = $amount;
    }

    public function getResource(): ResourceInterface
    {
        return $this->resource;
    }

    public function getResourceCode(): string
    {
        return $this->resource->getCode();
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}