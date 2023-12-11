<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain\EntityId;

final class PlanetId implements PlanetIdInterface
{
    public function __construct(
        private readonly string $id
    ) {
    }

    public function getUuid(): string
    {
        return $this->id;
    }
}