<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\ValueObject;

use TheGame\Application\Component\Shipyard\Domain\ConstructibleInterface;
use TheGame\Application\Component\Shipyard\Domain\ConstructibleUnit;
use TheGame\Application\SharedKernel\Domain\ResourceRequirementsInterface;

abstract class AbstractConstructible implements ConstructibleInterface
{
    public function __construct(
        private readonly string $type,
        private readonly ResourceRequirementsInterface $requirements,
        private readonly int $duration,
        private readonly int $productionLoad,
    ) {
    }

    abstract public function getConstructionUnit(): ConstructibleUnit;

    public function getType(): string
    {
        return $this->type;
    }

    public function getRequirements(): ResourceRequirementsInterface
    {
        return $this->requirements;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function getProductionLoad(): int
    {
        return $this->productionLoad;
    }
}
