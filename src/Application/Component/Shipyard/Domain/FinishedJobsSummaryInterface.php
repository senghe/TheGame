<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain;

interface FinishedJobsSummaryInterface
{
    public function addEntry(
        ConstructibleUnit $unit,
        string $type,
        int $quantity
    ): void;

    public function getSummary(): array;
}
