<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Exception;

use RuntimeException;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingIdInterface;
use TheGame\Application\Component\Shipyard\Domain\ShipyardIdInterface;

final class ShipyardHasNotBeenFoundException extends RuntimeException
{
    public function __construct(
        ShipyardIdInterface|BuildingIdInterface $id,
    ) {
        $message = $id instanceof ShipyardIdInterface
            ? sprintf("Shipyard %s has not be found", $id->getUuid())
            : sprintf("Shipyard for building %s has not be found", $id->getUuid());

        parent::__construct($message);
    }
}
