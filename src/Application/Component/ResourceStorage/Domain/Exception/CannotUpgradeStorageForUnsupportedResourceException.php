<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain\Exception;

use DomainException;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceIdInterface;

final class CannotUpgradeStorageForUnsupportedResourceException extends DomainException
{
    public function __construct(
        PlanetIdInterface $planetId,
        ResourceIdInterface $resourceId,
    ) {
        parent::__construct(
            sprintf(
                'Cannot upgrade storage for unsupported resource %s on planet %s',
                $planetId->getUuid(),
                $resourceId->getUuid(),
            ),
        );
    }
}
