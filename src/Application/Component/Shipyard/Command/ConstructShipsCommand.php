<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Command;

use TheGame\Application\SharedKernel\CommandInterface;

final class ConstructShipsCommand implements CommandInterface
{
    public function __construct(
        private readonly string $shipyardId,
        private readonly string $shipType,
        private readonly int $quantity,
    ) {
    }

    public function getShipyardId(): string
    {
        return $this->shipyardId;
    }

    public function getShipType(): string
    {
        return $this->shipType;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
