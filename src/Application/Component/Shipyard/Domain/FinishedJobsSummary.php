<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain;

final class FinishedJobsSummary implements FinishedJobsSummaryInterface
{
    /** @var array<int, FinishedJobsSummaryEntryInterface> */
    private array $summary = [];

    public function addEntry(
        ConstructibleUnit $unit,
        string $type,
        int $quantity
    ): void {
        foreach ($this->summary as $entry) {
            if ($entry->isFor($type) === true) {
                $entry->addQuantity($quantity);

                return;
            }
        }

        $newEntry = new FinishedJobsSummaryEntry($unit, $type);
        $newEntry->addQuantity($quantity);
        $this->summary[] = $newEntry;
    }

    /** @return array<int, FinishedJobsSummaryEntryInterface> */
    public function getSummary(): array
    {
        return $this->summary;
    }
}
