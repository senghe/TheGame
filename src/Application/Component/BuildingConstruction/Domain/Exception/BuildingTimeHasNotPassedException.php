<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Exception;

use DomainException;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;

final class BuildingTimeHasNotPassedException extends DomainException
{
    public function __construct(
        PlanetIdInterface $planetId,
        BuildingType $buildingType,
    ) {
        $message = sprintf(
            'Buildings time with type %s has not passed on planet %s',
            $buildingType->getValue(),
            $planetId->getUuid(),
        );

        parent::__construct($message);
    }
}
