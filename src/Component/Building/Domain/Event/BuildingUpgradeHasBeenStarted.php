<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Event;

use App\Component\SharedKernel\EventInterface;

final class BuildingUpgradeHasBeenStarted implements EventInterface
{
    private int $planetId;

    private string $buildingCode;

    private int $currentLevel;

    private array $resourceAmounts;

    private array $miningSpeeds;

    public function __construct(
        int $planetId,
        string $buildingCode,
        int $currentLevel,
        array $resourceAmounts,
        array $miningSpeeds
    ) {
        $this->planetId = $planetId;
        $this->buildingCode = $buildingCode;
        $this->currentLevel = $currentLevel;
        $this->resourceAmounts = $resourceAmounts;
        $this->miningSpeeds = $miningSpeeds;
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

    public function getMiningSpeeds(): array
    {
        return $this->miningSpeeds;
    }
}