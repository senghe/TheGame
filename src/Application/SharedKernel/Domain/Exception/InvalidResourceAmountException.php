<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain\Exception;

use DomainException;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceIdInterface;

final class InvalidResourceAmountException extends DomainException
{
    public function __construct(
        ResourceIdInterface $resourceId,
        int $amount,
    ) {
        parent::__construct(
            sprintf(
                'Invalid amount %d of resource %s',
                $amount,
                $resourceId->getUuid(),
            ),
        );
    }
}
