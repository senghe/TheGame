<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Exception;

use RuntimeException;
use TheGame\Application\Component\Shipyard\Domain\ShipyardIdInterface;

final class ShipyardHasNotBeenFoundException extends RuntimeException
{
    public function __construct(
        ShipyardIdInterface $shipyardId,
    ) {
        $message = sprintf("Shipyard %s has not be found", $shipyardId->getUuid());

        parent::__construct($message);
    }
}
