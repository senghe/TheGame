<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain;

interface ShipsGroupInterface
{
    public function getShipName(): string;

    public function getQuantity(): int;

    public function hasShip(string $name): bool;

    public function merge(ShipsGroupInterface $shipGroup): void;

    public function split(int $quantity): ShipsGroupInterface;

    public function hasMoreShipsThan(int $quantity): bool;

    public function hasEnoughShips(int $quantity): bool;

    public function getSpeed(): int;

    public function getLoadCapacity(): int;

    public function getUnitLoadCapacity(): int;

    public function setEmpty(): void;

    public function isEmpty(): bool;
}
