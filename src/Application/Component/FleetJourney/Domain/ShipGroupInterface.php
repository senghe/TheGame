<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain;

interface ShipGroupInterface
{
    public function getType(): string;

    public function getQuantity(): int;

    public function canBeMerged(ShipGroupInterface $shipGroup): bool;

    public function merge(ShipGroupInterface $shipGroup): void;

    public function canBeSplit(ShipGroupInterface $shipGroup): bool;

    public function setEmpty(): void;

    public function getSpeed(): string;

    public function getUnitLoadCapacity(): string;
}
