<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain;

use TheGame\Application\SharedKernel\Domain\ResourceRequirementsInterface;

interface ConstructibleInterface
{
    public function getConstructionUnit(): ConstructibleUnit;

    public function getType(): string;

    public function getRequirements(): ResourceRequirementsInterface;

    public function getDuration(): int;

    public function getProductionLoad(): int;
}
