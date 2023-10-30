<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain\Event;

use TheGame\Application\SharedKernel\EventInterface;

final class StorageAmountHasChangedEvent implements EventInterface
{
    public function __construct(
        private readonly string $planetId,
        private readonly string $resourceId,
    ) {
    }

    public function getPlanetId(): string
    {
        return $this->planetId;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }
}
