<?php

declare(strict_types=1);

namespace App\Domain\Planet;

use App\Domain\Planet\Entity\BuildingInterface;
use App\Domain\Planet\Entity\PlanetInterface;
use App\Domain\Planet\Event\BuildingUpgradeHasBeenCancelled;
use App\Domain\Planet\Event\BuildingUpgradeHasBeenStarted;
use App\Domain\Planet\Exception\PlaceRequirementsNotMetException;
use App\Domain\Planet\Exception\ResourceRequirementsNotMetException;
use App\Domain\Planet\Port\EventRecorderInterface;
use App\Domain\Planet\Service\BuildingMetadataResolverInterface;
use App\Domain\SharedKernel\Exception\AggregateRootNotBuiltException;

final class AggregateRoot implements AggregateRootInterface
{
    private EventRecorderInterface $eventRecorder;

    private BuildingMetadataResolverInterface $buildingMetadataResolver;

    private bool $isBuilt = false;

    private PlanetInterface $planet;

    public function __construct(
        EventRecorderInterface $eventRecorder,
        BuildingMetadataResolverInterface $buildingMetadataResolver
    ) {
        $this->eventRecorder = $eventRecorder;
        $this->buildingMetadataResolver = $buildingMetadataResolver;
    }

    public function build(PlanetInterface $planet): void
    {
        $this->planet = $planet;
        $this->isBuilt = true;
    }

    public function upgrade(BuildingInterface $building): void
    {
        if ($this->isBuilt === false) {
            throw new AggregateRootNotBuiltException($this);
        }

        $resourceRequirements = $this->buildingMetadataResolver->getResourceRequirements($building);
        if ($this->planet->hasEnoughResources($resourceRequirements) === false) {
            throw new ResourceRequirementsNotMetException($building, $this->planet);
        }

        $neededPlace = $this->buildingMetadataResolver->getSize($building);
        if ($this->planet->hasEnoughPlace($neededPlace) === false) {
            throw new PlaceRequirementsNotMetException($building, $this->planet);
        }

        $upgradingEndTime = $this->buildingMetadataResolver->getUpgradingTime($building);
        $building->startUpgrading($upgradingEndTime, $neededPlace);

        $resourceAmounts = [];
        foreach ($resourceRequirements as $requirement) {
            $resourceAmounts[$requirement->getResourceCode()] = $requirement->getAmount();
        }

        $this->eventRecorder->record(
            new BuildingUpgradeHasBeenStarted(
                $this->planet->getId(),
                $building->getCode(),
                $building->getLevel(),
                $resourceAmounts
            )
        );
    }

    public function cancelUpgrade(BuildingInterface $building): void
    {
        if ($this->isBuilt === false) {
            throw new AggregateRootNotBuiltException($this);
        }

        $building->cancelUpgrading();

        $resourceRequirements = $this->buildingMetadataResolver->getResourceRequirements($building);
        $resourceAmounts = [];
        foreach ($resourceRequirements as $requirement) {
            $resourceAmounts[$requirement->getResourceCode()] = $requirement->getAmount();
        }

        $this->eventRecorder->record(
            new BuildingUpgradeHasBeenCancelled(
                $this->planet->getId(),
                $building->getCode(),
                $resourceAmounts
            )
        );
    }
}