<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Service\BuildingMetadata;

use App\Component\Building\Domain\Entity\BuildingInterface;
use App\Component\Building\Domain\ValueObject\ResourceAmountInterface;
use App\Component\Building\Domain\ValueObject\ResourceMiningSpeedInterface;
use Doctrine\Common\Collections\Collection;

final class SolarPowerStationMetadata implements BuildingMetadataInterface
{
    public function supports(BuildingInterface $building): bool
    {
        return $building->getCode() === BuildingInterface::CODE_SOLAR_POWER_STATION;
    }

    /**
     * @return Collection<ResourceAmountInterface>
     */
    public function getResourceRequirements(int $level): Collection
    {

    }

    /**
     * @return Collection<ResourceMiningSpeedInterface>
     */
    public function getMiningSpeeds(int $level): Collection
    {

    }

    public function getSize(int $level): int
    {

    }

    public function getUpgradingTime(int $level): int
    {

    }

}