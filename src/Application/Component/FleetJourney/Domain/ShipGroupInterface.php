<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain;

interface ShipGroupInterface
{
    public function getType(): string;

    public function getQuantity(): int;

    public function hasType(string $type): bool;

    public function merge(ShipGroupInterface $shipGroup): void;

    public function split(int $quantity): ShipGroupInterface;

    public function hasMoreShipsThan(int $quantity): bool;

    public function hasEnoughShips(int $quantity): bool;

    public function getSpeed(): int;

    public function getUnitLoadCapacity(): int;

    public function setEmpty(): void;

    public function isEmpty(): bool;
}
