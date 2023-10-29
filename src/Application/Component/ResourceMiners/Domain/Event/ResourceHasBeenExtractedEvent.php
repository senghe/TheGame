<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMiners\Domain\Event;

use TheGame\Application\SharedKernel\EventInterface;

final class ResourceHasBeenExtractedEvent implements EventInterface
{
    public function __construct(
        private readonly string $planetId,
        private readonly string $resourceId,
        private readonly int $amount,
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

    public function getAmount(): int
    {
        return $this->amount;
    }
}
