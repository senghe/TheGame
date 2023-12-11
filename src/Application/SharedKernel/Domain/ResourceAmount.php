<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain;

use TheGame\Application\SharedKernel\Domain\EntityId\ResourceIdInterface;
use TheGame\Application\SharedKernel\Domain\Exception\InvalidResourceAmountException;

final class ResourceAmount implements ResourceAmountInterface
{
    public function __construct(
        private readonly ResourceIdInterface $resourceId,
        private readonly int $amount,
    ) {
        if ($this->amount <= 0) {
            throw new InvalidResourceAmountException(
                $this->resourceId,
                $this->amount
            );
        }
    }

    public function getResourceId(): ResourceIdInterface
    {
        return $this->resourceId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
