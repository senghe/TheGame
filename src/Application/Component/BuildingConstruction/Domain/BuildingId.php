<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain;

final class BuildingId implements BuildingIdInterface
{
    public function __construct(
        private readonly string $id,
    ) {
    }

    public function getUuid(): string
    {
        return $this->id;
    }
}
