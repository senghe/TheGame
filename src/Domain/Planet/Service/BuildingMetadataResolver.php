<?php

declare(strict_types=1);

namespace App\Domain\Planet\Service;

use App\Domain\Planet\Entity\BuildingInterface;
use App\Domain\Planet\Exception\UnknownBuildingFoundException;
use App\Domain\Planet\Service\BuildingMetadata\BuildingMetadataInterface;
use App\Domain\Planet\ValueObject\ResourceAmountInterface;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;

final class BuildingMetadataResolver implements BuildingMetadataResolverInterface
{
    /**
     * @var Collection<BuildingMetadataInterface>
     */
    private Collection $buildingTemplates;

    public function __construct(Collection $buildingTemplates)
    {
        $this->buildingTemplates = $buildingTemplates;
    }

    /**
     * @return Collection<ResourceAmountInterface>
     */
    public function getResourceRequirements(BuildingInterface $building): Collection
    {
        foreach ($this->buildingTemplates as $template) {
            if ($template->supports($building) === false) {
                continue;
            }

            return $template->getResourceRequirements($building->getLevel());
        }

        throw new UnknownBuildingFoundException($building);
    }

    public function getUpgradingTime(BuildingInterface $building): DateTimeImmutable
    {
        if ($building->isUpgrading() === false) {
            return new DateTimeImmutable();
        }

        foreach ($this->buildingTemplates as $template) {
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
        foreach ($this->buildingTemplates as $template) {
            if ($template->supports($building) === false) {
                continue;
            }

            return $template->getSize($building->getLevel());
        }

        throw new UnknownBuildingFoundException($building);
    }
}