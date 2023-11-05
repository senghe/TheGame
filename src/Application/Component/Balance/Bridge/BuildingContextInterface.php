<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Balance\Bridge;

use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\ResourceRequirementsInterface;

interface BuildingContextInterface
{
    public function getBuildingDuration(
        int $level,
        BuildingType $buildingType
    ): int;

    public function getResourceRequirements(
        int $level,
        BuildingType $buildingType
    ): ResourceRequirementsInterface;
}
