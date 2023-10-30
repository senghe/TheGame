<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain\Exception;

use DomainException;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceAmountInterface;

final class CannotUseUnsupportedResourceException extends DomainException
{
    public function __construct(
        PlanetIdInterface $planetId,
        ResourceAmountInterface $amount,
    ) {
        parent::__construct(
            sprintf(
                'Cannot use unsupported resource %s on planet %s',
                $planetId->getUuid(),
                $amount->getResourceId()->getUuid(),
            ),
        );
    }
}
