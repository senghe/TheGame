<?php

declare(strict_types=1);

namespace App\Domain\Planet\Service;

use App\Domain\Planet\Entity\BuildingInterface;
use App\Domain\Planet\ValueObject\ResourceAmountInterface;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;

interface BuildingMetadataResolverInterface
{
    /**
     * @return Collection<ResourceAmountInterface>
     */
    public function getResourceRequirements(BuildingInterface $building): Collection;

    public function getUpgradingTime(BuildingInterface $building): DateTimeImmutable;

    public function getSize(BuildingInterface $building): int;
}