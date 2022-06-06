<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Service\BuildingMetadata;

use App\Component\Building\Domain\Entity\BuildingInterface;
use App\Component\Building\Domain\ValueObject\ResourceAmountInterface;
use App\Component\Building\Domain\ValueObject\ResourceMiningSpeedInterface;
use App\SharedKernel\Port\CollectionInterface;

final class NaniteFactoryMetadata implements BuildingMetadataInterface
{
    public function supports(BuildingInterface $building): bool
    {
        return $building->getCode() === BuildingInterface::CODE_NANITE_FACILITY;
    }

    public function isMine(): bool
    {
        return false;
    }

    /**
     * @return CollectionInterface<ResourceAmountInterface>
     */
    public function getResourceRequirements(int $level): CollectionInterface
    {

    }

    /**
     * @return CollectionInterface<ResourceMiningSpeedInterface>
     */
    public function getMiningSpeeds(int $level): CollectionInterface
    {

    }

    public function getSize(int $level): int
    {

    }

    public function getUpgradingTime(int $level): int
    {

    }
}