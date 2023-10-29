<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMiners\Command;

use TheGame\Application\SharedKernel\CommandInterface;

final class ExtractResourcesCommand implements CommandInterface
{
    public function __construct(
        private readonly string $planetId,
    ) {
    }

    public function getPlanetId(): string
    {
        return $this->planetId;
    }
}
