<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain;

use TheGame\Application\Component\FleetJourney\Domain\Exception\CannotMergeShipGroupsOfDifferentTypeException;

final class ShipGroup implements ShipGroupInterface
{
    public function __construct(
        private readonly string $type,
        private int $quantity,
        private readonly string $speed,
        private readonly int $unitLoadCapacity,
        private readonly ResourceLoadInterface $load,
    ) {

    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function canBeMerged(ShipGroupInterface $shipGroup): bool
    {
        return $this->type !== $shipGroup->getType();
    }

    public function canBeSplit(ShipGroupInterface $shipGroup): bool
    {
        return $this->type !== $shipGroup->getType();
    }

    public function merge(ShipGroupInterface $shipGroup): void
    {
        if ($this->type !== $shipGroup->getType()) {
            throw new CannotMergeShipGroupsOfDifferentTypeException($this->type, $shipGroup->getType());
        }

        $this->quantity += $shipGroup->getQuantity();
        $shipGroup->setEmpty();
    }

    public function setEmpty(): void
    {
        $this->quantity = 0;
    }

    public function getSpeed(): string
    {
        return $this->speed;
    }

    public function getUnitLoadCapacity(): string
    {
        return $this->unitLoadCapacity;
    }
}
