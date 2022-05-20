<?php

declare(strict_types=1);

namespace App\Domain\Planet\Event;

use App\Domain\SharedKernel\Event;

final class BuildingUpgradeHasBeenStarted implements Event
{
    private int $planetId;

    private string $buildingCode;

    private int $currentLevel;

    private array $resourceAmounts;

    public function __construct(
        int $planetId,
        string $buildingCode,
        int $currentLevel,
        array $resourceAmounts
    ) {
        $this->planetId = $planetId;
        $this->buildingCode = $buildingCode;
        $this->currentLevel = $currentLevel;
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

    public function getCurrentLevel(): int
    {
        return $this->currentLevel;
    }

    public function getResourceAmounts(): array
    {
        return $this->resourceAmounts;
    }
}