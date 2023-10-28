<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Command;

use TheGame\Application\Component\ResourceStorage\Exception\InvalidUseAmountException;

final class UseResourcesCommand
{
    public function __construct(
        public readonly string $planetId,
        public readonly string $resourceId,
        public readonly int $amount,
    ) {
        if ($this->amount <= 0) {
            throw new InvalidUseAmountException(
                $this->planetId,
                $this->resourceId,
                $this->amount
            );
        }
    }
}
