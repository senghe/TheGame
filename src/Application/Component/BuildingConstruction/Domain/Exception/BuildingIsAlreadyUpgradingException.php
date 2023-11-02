<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Exception;

use DomainException;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;

final class BuildingIsAlreadyUpgradingException extends DomainException
{
    public function __construct(
        PlanetIdInterface $planetId,
        BuildingType $buildingType,
    ) {
        $message = sprintf(
            'Building with type %s is already being upgraded on %s planet',
            $buildingType->getValue(),
            $planetId->getUuid(),
        );

        parent::__construct($message);
    }
}
