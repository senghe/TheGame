<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain\Exception;

use DomainException;
use TheGame\Application\Component\ResourceStorage\Domain\StorageIdInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceAmountInterface;

final class CannotUseUnsupportedResourceException extends DomainException
{
    public function __construct(
        StorageIdInterface|PlanetIdInterface $resourceHolderId,
        ResourceAmountInterface $amount,
    ) {
        $message = 'Cannot use unsupported resource %s on planet %s';
        if ($resourceHolderId instanceof StorageIdInterface === true) {
            $message = 'Cannot use unsupported resource %s on storage %s';
        }

        parent::__construct(
            sprintf($message, $resourceHolderId->getUuid(), $amount->getResourceId()->getUuid()),
        );
    }
}
