<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain;

use TheGame\Application\SharedKernel\Domain\Exception\InvalidResourceAmountException;

final class ResourceAmount
{
    public function __construct(
        public readonly ResourceIdInterface $resourceId,
        public readonly int $amount,
    ) {
        if ($this->amount <= 0) {
            throw new InvalidResourceAmountException(
                $this->resourceId,
                $this->amount
            );
        }
    }
}
