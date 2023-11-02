<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Entity;

use TheGame\Application\Component\BuildingConstruction\Domain\BuildingIdInterface;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingType;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\BuildingIsAlreadyUpgradingException;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\BuildingIsNotUpgradingYetException;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceRequirementsInterface;

class Building
{
    private int $currentLevel = 0;

    private bool $duringUpgrade = false;

    public function __construct(
        protected readonly PlanetIdInterface $planetId,
        protected readonly BuildingIdInterface $buildingId,
        protected readonly BuildingType $type,
    ) {
    }

    public function startUpgrading(): void
    {
        if ($this->duringUpgrade === true) {
            throw new BuildingIsAlreadyUpgradingException(
                $this->planetId,
                $this->type,
            );
        }

        $this->duringUpgrade = true;
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
    }

    public function finishUpgrading(): void
    {
        if ($this->duringUpgrade === false) {
            throw new BuildingIsNotUpgradingYetException(
                $this->planetId,
                $this->type,
            );
        }

        $this->duringUpgrade = false;
        $this->currentLevel++;
    }

    public function getCosts(): ResourceRequirementsInterface
    {
    }
}
