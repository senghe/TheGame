<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain\Exception;

use DomainException;
use TheGame\Application\Component\ResourceStorage\Domain\StorageIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceIdInterface;

final class CannotUpgradeStorageLimitForLowerValueException extends DomainException
{
    public function __construct(
        StorageIdInterface $storageId,
        ResourceIdInterface $resourceId,
        int $previousLimit,
        int $newLimit,
    ) {
        parent::__construct(
            sprintf(
                'Cannot upgrade storage %s of resource %s (from %d to %d)',
                $storageId->getUuid(),
                $resourceId->getUuid(),
                $previousLimit,
                $newLimit
            ),
        );
    }
}
