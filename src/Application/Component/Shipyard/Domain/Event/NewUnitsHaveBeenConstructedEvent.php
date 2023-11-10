<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Event;

use TheGame\Application\SharedKernel\EventInterface;

final class NewUnitsHaveBeenConstructedEvent implements EventInterface
{
    public function __construct(
        private readonly string $unit,
        private readonly string $type,
        private readonly int $quantity,
    ) {

    }

    public function getUnit(): string
    {
        return $this->unit;
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
