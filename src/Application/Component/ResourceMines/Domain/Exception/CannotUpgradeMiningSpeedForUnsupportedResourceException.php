<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMines\Domain\Exception;

use DomainException;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceIdInterface;

final class CannotUpgradeMiningSpeedForUnsupportedResourceException extends DomainException
{
    public function __construct(
        PlanetIdInterface $planetId,
        ResourceIdInterface $resourceId,
        int $newSpeed,
    ) {
        parent::__construct(
            sprintf(
                'Cannot upgrade mining speed to %d for unsupported resource %s on planet %s ',
                $newSpeed,
                $planetId->getUuid(),
                $resourceId->getUuid(),
            ),
        );
    }
}
