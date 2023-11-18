<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain;

use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

interface ConstructibleInterface
{
    public function getConstructionUnit(): ConstructibleUnit;

    public function getType(): string;

    public function getRequirements(): ResourcesInterface;

    public function getDuration(): int;

    public function getProductionLoad(): int;
}
