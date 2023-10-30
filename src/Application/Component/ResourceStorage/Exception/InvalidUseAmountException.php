<?php

namespace TheGame\Application\Component\ResourceStorage\Exception;

use RuntimeException;

final class InvalidUseAmountException extends RuntimeException
{
    public function __construct(
        string $planetId,
        string $resourceId,
        int $amount,
    ) {
        parent::__construct(
            sprintf(
                "Invalid use amount %d of resource %s on planet %s",
                $amount,
                $resourceId,
                $planetId,
            ),
        );
    }
}
