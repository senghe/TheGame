<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Event;

use App\Component\SharedKernel\Event;

final class BuildingUpgradeHasBeenCancelled implements Event
{
    private int $planetId;

    private string $buildingCode;

    private array $resourceAmounts;

    public function __construct(
        int $planetId,
        string $buildingCode,
        array $resourceAmounts
    ) {
        $this->planetId = $planetId;
        $this->buildingCode = $buildingCode;
        $this->resourceAmounts = $resourceAmounts;
    }

    public function getPlanetId(): int
    {
        return $this->planetId;
    }

    public function getBuildingCode(): string
    {
        return $this->buildingCode;
    }

    public function getResourceAmounts(): array
    {
        return $this->resourceAmounts;
    }
}