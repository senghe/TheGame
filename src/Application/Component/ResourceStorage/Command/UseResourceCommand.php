<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Command;

use TheGame\Application\Component\ResourceStorage\Exception\InvalidUseAmountException;
use TheGame\Application\SharedKernel\CommandInterface;

final class UseResourceCommand implements CommandInterface
{
    public function __construct(
        private readonly string $planetId,
        private readonly string $resourceId,
        private readonly int $amount,
    ) {
        if ($this->amount <= 0) {
            throw new InvalidUseAmountException(
                $this->planetId,
                $this->resourceId,
                $this->amount
            );
        }
    }

    public function getPlanetId(): string
    {
        return $this->planetId;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
