<?php

namespace TheGame\Application\Component\ResourceStorage\Exception;

use RuntimeException;

final class InvalidDispatchAmountException extends RuntimeException
{
    public function __construct(
        private readonly string $planetId,
        private readonly string $resourceId,
        private readonly int $amount,
    ) {
        parent::__construct(
            sprintf(
                "Invalid dispatch amount %d of resource %s on planet %s",
                $this->amount,
                $this->resourceId,
                $this->planetId,
            ),
        );
    }
}
