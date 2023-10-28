<?php

namespace TheGame\Application\Component\ResourceStorage\Exception;

use RuntimeException;

final class InvalidUseAmountException extends RuntimeException
{
    public function __construct(
        private readonly string $planetId,
        private readonly string $resourceId,
        private readonly int $amount,
    ) {
        parent::__construct(
            sprintf(
                "Invalid use amount %d of resource %s on planet %s",
                $this->amount,
                $this->resourceId,
                $this->planetId,
            ),
        );
    }
}
