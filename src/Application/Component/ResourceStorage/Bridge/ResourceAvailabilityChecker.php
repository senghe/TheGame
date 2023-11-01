<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Bridge;

use TheGame\Application\Component\ResourceStorage\ResourceStoragesRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceAmountInterface;

final class ResourceAvailabilityChecker
{
    public function __construct(
        private readonly ResourceStoragesRepositoryInterface $storagesRepository
    ) {
    }

    /** @var ResourceAmountInterface[] $resourcesAmounts */
    public function check(
        PlanetIdInterface $planetId,
        array $resourcesAmounts
    ): bool {
        $aggregate = $this->storagesRepository->findForPlanet($planetId);
        foreach ($resourcesAmounts as $resourceAmount) {
            if ($aggregate->hasEnough($resourceAmount) === false) {
                return false;
            }
        }

        return true;
    }
}
