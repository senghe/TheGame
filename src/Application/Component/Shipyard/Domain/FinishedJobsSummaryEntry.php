<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain;

final class FinishedJobsSummaryEntry
{
    public function __construct(
        private readonly ConstructibleUnit $unit,
        private readonly string $type,
        private int $quantity = 0,
    ) {

    }

    public function getUnit(): ConstructibleUnit
    {
        return $this->unit;
    }

    public function isFor(string $type): bool
    {
        return $this->type === $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function addQuantity(int $quantity): void
    {
        $this->quantity += $quantity;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
