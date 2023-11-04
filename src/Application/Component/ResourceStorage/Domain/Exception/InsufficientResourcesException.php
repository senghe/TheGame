<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain\Exception;

use DomainException;
use TheGame\Application\Component\ResourceStorage\Domain\StorageIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceAmountInterface;

final class InsufficientResourcesException extends DomainException
{
    public function __construct(
        StorageIdInterface $storageId,
        ResourceAmountInterface $amount,
    ) {
        parent::__construct(
            sprintf(
                'Cannot use %d amount of resource %s on storage %s',
                $amount->getAmount(),
                $storageId->getUuid(),
                $amount->getResourceId()->getUuid(),
            ),
        );
    }
}
