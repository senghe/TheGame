<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Entity;

use DateTime;
use DateTimeInterface;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingIdInterface;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\BuildingIsAlreadyUpgradingException;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\BuildingIsNotUpgradingYetException;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\BuildingTimeHasNotPassedException;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceIdInterface;

class Building
{
    private int $currentLevel = 0;

    private bool $duringUpgrade = false;

    private ?DateTimeInterface $finishUpgradeAt;

    public function __construct(
        protected readonly PlanetIdInterface $planetId,
        protected readonly BuildingIdInterface $buildingId,
        protected readonly BuildingType $type,
        protected readonly ResourceIdInterface $resourceContextId,
    ) {
    }

    public function getId(): BuildingIdInterface
    {
        return $this->buildingId;
    }

    public function getPlanetId(): PlanetIdInterface
    {
        return $this->planetId;
    }

    public function getCurrentLevel(): int
    {
        return $this->currentLevel;
    }

    public function getType(): BuildingType
    {
        return $this->type;
    }

    public function startUpgrading(DateTimeInterface $finishAt): void
    {
        if ($this->duringUpgrade === true) {
            throw new BuildingIsAlreadyUpgradingException(
                $this->planetId,
                $this->type,
            );
        }

        $this->duringUpgrade = true;
        $this->finishUpgradeAt = $finishAt;
    }

    public function cancelUpgrading(): void
    {
        if ($this->duringUpgrade === false) {
            throw new BuildingIsNotUpgradingYetException(
                $this->planetId,
                $this->type,
            );
        }

        $this->duringUpgrade = false;
        $this->finishUpgradeAt = null;
    }

    public function finishUpgrading(): void
    {
        if ($this->duringUpgrade === false) {
            throw new BuildingIsNotUpgradingYetException(
                $this->planetId,
                $this->type,
            );
        }

        $now = new DateTime();
        if ($this->finishUpgradeAt > $now) {
            throw new BuildingTimeHasNotPassedException(
                $this->planetId,
                $this->type,
            );
        }

        $this->duringUpgrade = false;
        $this->finishUpgradeAt = null;
        $this->currentLevel++;
    }

    public function getResourceContextId(): ResourceIdInterface
    {
        return $this->resourceContextId;
    }
}
