<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Bridge;

use TheGame\Application\Component\ResourceStorage\ResourceStoragesRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceRequirementsInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class ResourceAvailabilityChecker
{
    public function __construct(
        private readonly ResourceStoragesRepositoryInterface $storagesRepository,
    ) {
    }

    public function check(
        PlanetIdInterface $planetId,
        ResourceRequirementsInterface $requirements
    ): bool {
        $aggregate = $this->storagesRepository->findForPlanet($planetId);
        if ($aggregate === null) {
            throw new InconsistentModelException(
                sprintf("Planet %d has no storages collection attached", $planetId->getUuid()),
            );
        }

        return $aggregate->hasEnough($requirements);
    }
}
