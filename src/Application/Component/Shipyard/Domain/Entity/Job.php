<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Entity;

use TheGame\Application\Component\Shipyard\Domain\ConstructibleInterface;
use TheGame\Application\Component\Shipyard\Domain\ConstructibleUnit;
use TheGame\Application\Component\Shipyard\Domain\JobIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

class Job implements ConstructibleInterface
{
    private readonly int $initialQuantity;

    public function __construct(
        private readonly JobIdInterface $jobId,
        private readonly ConstructibleInterface $constructible,
        private int $currentQuantity,
    ) {
        $this->initialQuantity = $this->currentQuantity;
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

    public function getRequirements(): ResourcesInterface
    {
        return $this->constructible->getRequirements()->multipliedBy($this->currentQuantity);
    }

    public function getQuantity(): int
    {
        return $this->currentQuantity;
    }

    public function getInitialQuantity(): int
    {
        return $this->initialQuantity;
    }

    public function getDuration(): int
    {
        return $this->constructible->getDuration() * $this->currentQuantity;
    }

    public function getProductionLoad(): int
    {
        return $this->constructible->getProductionLoad() * $this->currentQuantity;
    }

    public function finishPartially(int $elapsedTime): int
    {
        $finishedQuantity = (int) floor($elapsedTime / $this->constructible->getDuration());
        if ($finishedQuantity > $this->currentQuantity) {
            $this->finish();

            return $this->currentQuantity;
        }

        $this->currentQuantity -= $finishedQuantity;

        return $finishedQuantity;
    }

    public function finish(): void
    {
        $this->currentQuantity = 0;
    }
}
