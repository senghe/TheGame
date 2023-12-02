<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Balance\Bridge;

use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

interface ShipyardContextInterface
{
    public function getShipyardProductionLimit(int $level): int;

    public function getCannonConstructionTime(string $type, int $shipyardLevel): int;

    public function getCannonProductionLoad(string $type): int;

    public function getCannonResourceRequirements(string $type): ResourcesInterface;

    public function getShipConstructionTime(string $type, int $shipyardLevel): int;

    public function getShipProductionLoad(string $type): int;

    public function getShipResourceRequirements(string $type): ResourcesInterface;
}
