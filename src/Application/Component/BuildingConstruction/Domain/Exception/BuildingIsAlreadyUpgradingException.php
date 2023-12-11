<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Exception;

use DomainException;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetIdInterface;

final class BuildingIsAlreadyUpgradingException extends DomainException
{
    public function __construct(
        PlanetIdInterface $planetId,
        BuildingType $buildingType,
    ) {
        $message = sprintf(
            'Building with type %s is already being upgraded on %s planet',
            $buildingType->value,
            $planetId->getUuid(),
        );

        parent::__construct($message);
    }
}
