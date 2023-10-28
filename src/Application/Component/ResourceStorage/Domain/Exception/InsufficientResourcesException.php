<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain\Exception;

use DomainException;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;

final class InsufficientResourcesException extends DomainException
{
    public function __construct(
        PlanetIdInterface $planetId,
        ResourceAmount $amount,
    ) {
        parent::__construct(
            sprintf(
                'Cannot use %d amount of resource %s on planet %s',
                $amount->amount,
                $planetId->getUuid(),
                $amount->resourceId->getUuid(),
            ),
        );
    }
}
