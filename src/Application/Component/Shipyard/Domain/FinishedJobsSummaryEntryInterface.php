<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain;

interface FinishedJobsSummaryEntryInterface
{
    public function getUnit(): ConstructibleUnit;

    public function isFor(string $type): bool;

    public function getType(): string;

    public function addQuantity(int $quantity): void;

    public function getQuantity(): int;
}
