<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Factory;

use TheGame\Application\Component\Shipyard\Domain\Entity\Job;
use TheGame\Application\SharedKernel\Domain\ResourceRequirementsInterface;

interface JobFactoryInterface
{
    public function createNewCannonsJob(
        string $cannonType,
        int $quantity,
        int $shipyardLevel,
        int $cannonConstructionTime,
        int $cannonProductionLoad,
        ResourceRequirementsInterface $cannonResourceRequirements,
    ): Job;

    public function createNewShipsJob(
        string $shipType,
        int $quantity,
        int $shipyardLevel,
        int $shipConstructionTime,
        int $shipProductionLoad,
        ResourceRequirementsInterface $shipResourceRequirements,
    ): Job;
}
