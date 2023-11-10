<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Exception;

use DomainException;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;

final class InsufficientResourcesException extends DomainException
{
    public function __construct(
        PlanetIdInterface $planetId,
        string $constructibleType,
    ) {
        parent::__construct(
            sprintf(
                'Insufficient resources for constructing %s on planet %s',
                $constructibleType,
                $planetId->getUuid(),
            ),
        );
    }
}
