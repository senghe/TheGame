<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Exception;

use DomainException;

final class ProductionLimitHasBeenReachedException extends DomainException
{
    public function __construct(
        int $productionLimit,
        int $quantity
    ) {
        $message = sprintf('Cannot add %d quantity to queue due to %d limit', $quantity, $productionLimit);

        parent::__construct($message);
    }
}
