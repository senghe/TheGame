<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Balance\Bridge;

use TheGame\Application\SharedKernel\Domain\ResourceRequirementsInterface;

interface ShipyardContextInterface
{
    public function getCannonConstructionTime(string $type): int;

    public function getCannonResourceRequirements(string $type): ResourceRequirementsInterface;

    public function getCannonProductionLoad(string $type): int;

    public function getShipConstructionTime(string $type): int;

    public function getShipResourceRequirements(string $type): ResourceRequirementsInterface;

    public function getShipProductionLoad(string $type): int;
}