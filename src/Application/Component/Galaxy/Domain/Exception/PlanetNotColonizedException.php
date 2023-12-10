<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Domain\Exception;

use DomainException;
use TheGame\Application\SharedKernel\Domain\EntityId\PlayerIdInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;

final class PlanetNotColonizedException extends DomainException
{
    public function __construct(
        GalaxyPointInterface $galaxyPoint,
    ) {
        $message = sprintf(
            "Planet %s is not colonized yet",
            $galaxyPoint->format(),
        );

        parent::__construct($message);
    }
}
