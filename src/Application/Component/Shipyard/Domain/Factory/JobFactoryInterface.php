<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Factory;

use TheGame\Application\Component\Shipyard\Domain\Entity\Job;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

interface JobFactoryInterface
{
    public function createNewCannonsJob(
        string $cannonType,
        int $quantity,
        int $shipyardLevel,
        int $cannonConstructionTime,
        int $cannonProductionLoad,
        ResourcesInterface $cannonResourceRequirements,
    ): Job;

    public function createNewShipsJob(
        string $shipType,
        int $quantity,
        int $shipyardLevel,
        int $shipConstructionTime,
        int $shipProductionLoad,
        ResourcesInterface $shipResourceRequirements,
    ): Job;
}
