<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Service;

use App\Component\Building\Domain\Entity\BuildingInterface;
use App\Component\Building\Domain\Exception\UnknownBuildingFoundException;
use App\Component\Building\Domain\Service\BuildingMetadata\BuildingMetadataInterface;
use App\Component\Building\Domain\ValueObject\ResourceAmountInterface;
use App\Component\Building\Domain\ValueObject\ResourceMiningSpeedInterface;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class BuildingMetadataResolver implements BuildingMetadataResolverInterface
{
    /**
     * @var Collection<BuildingMetadataInterface>
     */
    private Collection $buildingMetadata;

    public function __construct()
    {
        $this->buildingMetadata = new ArrayCollection();
    }

    public function addBuildingMetadata(BuildingMetadataInterface $buildingMetadata): void
    {
        $this->buildingMetadata->add($buildingMetadata);
    }

    /**
     * @return Collection<ResourceAmountInterface>
     */
    public function getNextResourceRequirements(BuildingInterface $building): Collection
    {
        foreach ($this->buildingMetadata as $template) {
            if ($template->supports($building) === false) {
                continue;
            }

            return $template->getResourceRequirements($building->getLevel()+1);
        }

        throw new UnknownBuildingFoundException($building);
    }

    /**
     * @return Collection<ResourceMiningSpeedInterface>
     */
    public function getNextMiningSpeed(BuildingInterface $building): Collection
    {
        foreach ($this->buildingMetadata as $template) {
            if ($template->supports($building) === false) {
                continue;
            }

            return $template->getMiningSpeed($building->getLevel()+1);
        }

        throw new UnknownBuildingFoundException($building);
    }

    public function getUpgradingTime(BuildingInterface $building): DateTimeImmutable
    {
        if ($building->isUpgrading() === false) {
            return new DateTimeImmutable();
        }

        foreach ($this->buildingMetadata as $template) {
            if ($template->supports($building) === false) {
                continue;
            }

            $duration = $template->getUpgradingTime($building->getLevel());

            if ($duration === 0) {
                return new DateTimeImmutable();
            }

            return (new DateTimeImmutable())
                ->add(new \DateInterval('PT' . $duration));
        }

        throw new UnknownBuildingFoundException($building);
    }

    public function getSize(BuildingInterface $building): int
    {
        foreach ($this->buildingMetadata as $template) {
            if ($template->supports($building) === false) {
                continue;
            }

            return $template->getSize($building->getLevel());
        }

        throw new UnknownBuildingFoundException($building);
    }
}