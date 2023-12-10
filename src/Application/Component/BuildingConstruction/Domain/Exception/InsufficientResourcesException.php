<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Exception;

use DomainException;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingIdInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetIdInterface;

final class InsufficientResourcesException extends DomainException
{
    public function __construct(
        PlanetIdInterface $planetId,
        BuildingIdInterface $buildingId,
    ) {
        parent::__construct(
            sprintf(
                'Insufficient resources for constructing building %s on planet %s',
                $buildingId->getUuid(),
                $planetId->getUuid(),
            ),
        );
    }
}
