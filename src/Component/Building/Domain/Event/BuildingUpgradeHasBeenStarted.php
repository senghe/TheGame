<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Event;

use App\Component\Building\Domain\ValueObject\ResourceMiningSpeedInterface;
use App\Component\SharedKernel\Event;
use Doctrine\Common\Collections\Collection;

final class BuildingUpgradeHasBeenStarted implements Event
{
    private int $planetId;

    private string $buildingCode;

    private int $currentLevel;

    private array $resourceAmounts;

    /**
     * @var Collection<ResourceMiningSpeedInterface>
     */
    private Collection $miningSpeeds;

    public function __construct(
        int $planetId,
        string $buildingCode,
        int $currentLevel,
        array $resourceAmounts,
        Collection $miningSpeeds
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

    public function getMiningSpeeds(): Collection
    {
        return $this->miningSpeeds;
    }
}