<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain\Exception;

use DomainException;
use TheGame\Application\Component\ResourceStorage\Domain\StorageIdInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceAmountInterface;

final class InsufficientResourcesException extends DomainException
{
    public function __construct(
        PlanetIdInterface|StorageIdInterface $resourcesHolderId,
        ResourceAmountInterface $amount,
    ) {
        $message = 'Cannot use %d amount of resource %s on planet %s';
        if ($resourcesHolderId instanceof StorageIdInterface === true) {
            $message = 'Cannot use %d amount of resource %s on storage %s';
        }

        parent::__construct(
            sprintf(
                $message,
                $amount->getAmount(),
                $resourcesHolderId->getUuid(),
                $amount->getResourceId()->getUuid(),
            ),
        );
    }
}
