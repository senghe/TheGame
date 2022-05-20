<?php

declare(strict_types=1);

namespace App\Domain\Planet\Service\BuildingMetadata;

use App\Domain\Planet\Entity\BuildingInterface;
use App\Domain\Planet\ValueObject\ResourceAmountInterface;
use Doctrine\Common\Collections\Collection;

final class FusionPowerStationMetadata implements BuildingMetadataInterface
{
    public function supports(BuildingInterface $building): bool
    {
        return $building->getCode() === BuildingInterface::CODE_FUSION_POWER_STATION;
    }

    /**
     * @return Collection<ResourceAmountInterface>
     */
    public function getResourceRequirements(int $level): Collection
    {

    }

    public function getSize(int $level): int
    {

    }

    public function getUpgradingTime(int $level): int
    {

    }
}