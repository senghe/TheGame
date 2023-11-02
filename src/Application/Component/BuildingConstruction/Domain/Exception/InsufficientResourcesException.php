<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Exception;

use DomainException;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;

final class InsufficientResourcesException extends DomainException
{
    public function __construct(
        PlanetIdInterface $planetId,
        BuildingType $buildingType,
    ) {
        parent::__construct(
            sprintf(
                'Insufficient resources for constructing building %s on planet %s',
                $buildingType->getValue(),
                $planetId->getUuid(),
            ),
        );
    }
}
