<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain\Event;

use TheGame\Application\SharedKernel\EventInterface;

final class StorageAmountHasChangedEvent implements EventInterface
{
    public function __construct(
        public readonly string $planetId,
        public readonly string $resourceId,
    ) {

    }
}
