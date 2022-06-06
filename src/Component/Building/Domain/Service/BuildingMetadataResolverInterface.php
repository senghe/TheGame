<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Service;

use App\Component\Building\Domain\Entity\BuildingInterface;
use App\Component\Building\Domain\ValueObject\ResourceAmountInterface;
use App\Component\Building\Domain\ValueObject\ResourceMiningSpeedInterface;
use App\SharedKernel\Port\CollectionInterface;
use DateTimeImmutable;

interface BuildingMetadataResolverInterface
{
    public function isMine(BuildingInterface $building): bool;

    /**
     * @return CollectionInterface<ResourceAmountInterface>
     */
    public function getNextResourceRequirements(BuildingInterface $building): CollectionInterface;

    /**
     * @return CollectionInterface<ResourceMiningSpeedInterface>
     */
    public function getNextMiningSpeeds(BuildingInterface $building): CollectionInterface;

    public function getUpgradingTime(BuildingInterface $building): DateTimeImmutable;

    public function getSize(BuildingInterface $building): int;
}