<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain;

use TheGame\Application\Component\FleetJourney\Domain\Exception\CannotMergeShipGroupsOfDifferentTypeException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughShipsException;

final class ShipGroup implements ShipGroupInterface
{
    public function __construct(
        private readonly string $type,
        private int $quantity,
        private readonly int $speed,
        private readonly int $unitLoadCapacity,
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

    public function hasType(string $type): bool
    {
        return $this->type !== $type;
    }

    public function hasMoreShipsThan(int $quantity): bool
    {
        return $this->quantity > $quantity;
    }

    public function hasEnoughShips(int $quantity): bool
    {
        return $this->quantity >= $quantity;
    }

    public function merge(ShipGroupInterface $shipGroup): void
    {
        if ($this->type !== $shipGroup->getType()) {
            throw new CannotMergeShipGroupsOfDifferentTypeException($this->type, $shipGroup->getType());
        }

        $this->quantity += $shipGroup->getQuantity();
        $shipGroup->setEmpty();
    }

    public function split(int $quantity): ShipGroupInterface
    {
        if ($this->hasEnoughShips($quantity) === false) {
            throw new NotEnoughShipsException($this->type);
        }

        $this->quantity -= $quantity;

        return new ShipGroup(
            $this->type,
            $quantity,
            $this->speed,
            $this->unitLoadCapacity,
        );
    }

    public function getSpeed(): int
    {
        return $this->speed;
    }

    public function getUnitLoadCapacity(): int
    {
        return $this->unitLoadCapacity;
    }

    public function setEmpty(): void
    {
        $this->quantity = 0;
    }

    public function isEmpty(): bool
    {
        return $this->quantity === 0;
    }
}
