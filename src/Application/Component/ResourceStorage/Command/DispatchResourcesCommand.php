<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Command;

use TheGame\Application\Component\ResourceStorage\Exception\InvalidDispatchAmountException;
use TheGame\Application\SharedKernel\CommandInterface;

final class DispatchResourcesCommand implements CommandInterface
{
    public function __construct(
        public readonly string $planetId,
        public readonly string $resourceId,
        public readonly int $amount,
    ) {
        if ($this->amount <= 0) {
            throw new InvalidDispatchAmountException(
                $this->planetId,
                $this->resourceId,
                $this->amount
            );
        }
    }
}
