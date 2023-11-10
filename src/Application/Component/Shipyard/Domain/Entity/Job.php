<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Entity;

use TheGame\Application\Component\Shipyard\Domain\ConstructibleInterface;
use TheGame\Application\Component\Shipyard\Domain\ConstructibleUnit;
use TheGame\Application\Component\Shipyard\Domain\JobIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceRequirementsInterface;

class Job implements ConstructibleInterface
{
    public function __construct(
        private readonly JobIdInterface $jobId,
        private readonly ConstructibleInterface $constructible,
        private int $quantity,
    ) {

    }

    public function getId(): JobIdInterface
    {
        return $this->jobId;
    }

    public function getConstructionUnit(): ConstructibleUnit
    {
        return $this->constructible->getConstructionUnit();
    }


    public function getType(): string
    {
        return $this->constructible->getType();
    }

    public function getConstructionType(): string
    {
        return $this->getType();
    }

    public function getRequirements(): ResourceRequirementsInterface
    {
        return $this->constructible->getRequirements()->multipliedBy($this->quantity);
    }

    public function getQuantity(): int
    {
        return $this->constructible->getQuantity() * $this->quantity;
    }

    public function getDuration(): int
    {
        return $this->constructible->getDuration() * $this->quantity;
    }

    public function getProductionLoad(): int
    {
        return $this->constructible->getProductionLoad() * $this->quantity;
    }

    public function finishPartially(int $elapsedTime): int
    {
        $finishedQuantity = 0;

        return $finishedQuantity;
    }

    public function finish(): void
    {

    }
}
