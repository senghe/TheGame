<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain;

use TheGame\Application\Component\FleetJourney\Domain\Exception\CannotMergeShipGroupsOfDifferentShips;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughShipsException;

final class ShipsGroup implements ShipsGroupInterface
{
    public function __construct(
        private readonly string $shipName,
        private readonly ShipClass $shipClass,
        private int $quantity,
        private readonly int $speed,
        private readonly int $unitLoadCapacity,
    ) {
    }

    public function getShipName(): string
    {
        return $this->shipName;
    }

    public function getShipClass(): ShipClass
    {
        return $this->shipClass;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function hasShip(string $name): bool
    {
        return $this->shipName === $name;
    }

    public function hasMoreShipsThan(int $quantity): bool
    {
        return $this->quantity > $quantity;
    }

    public function hasEnoughShips(int $quantity): bool
    {
        return $this->quantity >= $quantity;
    }

    public function merge(ShipsGroupInterface $shipGroup): void
    {
        if ($this->shipName !== $shipGroup->getShipName()) {
            throw new CannotMergeShipGroupsOfDifferentShips($this->shipName, $shipGroup->getShipName());
        }

        $this->quantity += $shipGroup->getQuantity();
        $shipGroup->setEmpty();
    }

    public function split(int $quantity): ShipsGroupInterface
    {
        if ($this->hasEnoughShips($quantity) === false) {
            throw new NotEnoughShipsException($this->shipName);
        }

        $this->quantity -= $quantity;

        return new ShipsGroup(
            $this->shipName,
            $this->shipClass,
            $quantity,
            $this->speed,
            $this->unitLoadCapacity,
        );
    }

    public function getSpeed(): int
    {
        return $this->speed;
    }

    public function getLoadCapacity(): int
    {
        return $this->getUnitLoadCapacity() * $this->getQuantity();
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
