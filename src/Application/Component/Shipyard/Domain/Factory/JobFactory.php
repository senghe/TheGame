<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Factory;

use TheGame\Application\Component\Shipyard\Domain\ConstructibleInterface;
use TheGame\Application\Component\Shipyard\Domain\Entity\Job;
use TheGame\Application\Component\Shipyard\Domain\ValueObject\Cannon;
use TheGame\Application\Component\Shipyard\Domain\ValueObject\Ship;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class JobFactory implements JobFactoryInterface
{
    public function __construct(
        private readonly UuidGeneratorInterface $uuidGenerator,
    ) {
    }

    public function createNewCannonsJob(
        string             $cannonType,
        int                $quantity,
        int                $shipyardLevel,
        int                $cannonConstructionTime,
        int                $cannonProductionLoad,
        ResourcesInterface $cannonResourceRequirements,
    ): Job {
        $cannon = new Cannon(
            $cannonType,
            $cannonResourceRequirements,
            $cannonConstructionTime,
            $cannonProductionLoad,
        );

        return $this->create($cannon, $quantity);
    }

    public function createNewShipsJob(
        string             $shipType,
        int                $quantity,
        int                $shipyardLevel,
        int                $shipConstructionTime,
        int                $shipProductionLoad,
        ResourcesInterface $shipResourceRequirements,
    ): Job {
        $ship = new Ship(
            $shipType,
            $shipResourceRequirements,
            $shipConstructionTime,
            $shipProductionLoad,
        );

        return $this->create($ship, $quantity);
    }

    private function create(
        ConstructibleInterface $constructible,
        int $quantity,
    ): Job {
        $jobId = $this->uuidGenerator->generateNewShipyardJobId();

        return new Job($jobId, $constructible, $quantity);
    }
}
