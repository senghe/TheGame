<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMiners\Domain\Event;

use TheGame\Application\SharedKernel\EventInterface;

final class ResourceHasBeenExtractedEvent implements EventInterface
{
    public function __construct(
        public readonly string $planetId,
        public readonly string $resourceId,
        public readonly int $amount,
    ) {
    }
}
