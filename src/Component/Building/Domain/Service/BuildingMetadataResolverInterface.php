<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Service;

use App\Component\Building\Domain\Entity\BuildingInterface;
use App\Component\Building\Domain\ValueObject\ResourceAmountInterface;
use App\Component\Building\Domain\ValueObject\ResourceMiningSpeedInterface;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;

interface BuildingMetadataResolverInterface
{
    /**
     * @return Collection<ResourceAmountInterface>
     */
    public function getNextResourceRequirements(BuildingInterface $building): Collection;

    /**
     * @return Collection<ResourceMiningSpeedInterface>
     */
    public function getNextMiningSpeeds(BuildingInterface $building): Collection;

    public function getUpgradingTime(BuildingInterface $building): DateTimeImmutable;

    public function getSize(BuildingInterface $building): int;
}