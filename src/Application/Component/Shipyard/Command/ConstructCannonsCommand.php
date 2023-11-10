<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Command;

use TheGame\Application\SharedKernel\CommandInterface;

final class ConstructCannonsCommand implements CommandInterface
{
    public function __construct(
        private readonly string $shipyardId,
        private readonly string $type,
        private readonly int $quantity,
    ) {
    }

    public function getShipyardId(): string
    {
        return $this->shipyardId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
