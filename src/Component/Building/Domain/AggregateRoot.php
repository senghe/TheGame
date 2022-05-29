<?php

declare(strict_types=1);

namespace App\Component\Building\Domain;

use App\Component\Building\Domain\Entity\BuildingInterface;
use App\Component\Building\Domain\Event\BuildingUpgradeHasBeenCancelled;
use App\Component\Building\Domain\Event\BuildingUpgradeHasBeenStarted;
use App\Component\Building\Domain\Exception\ResourceRequirementsNotMetException;
use App\Component\Building\Domain\Port\EventRecorderInterface;
use App\Component\Building\Domain\Service\BuildingMetadataResolverInterface;
use App\Component\SharedKernel\Domain\Entity\PlanetInterface;
use App\Component\SharedKernel\Exception\AggregateRootNotBuiltException;

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

        $resourceRequirements = $this->buildingMetadataResolver->getNextResourceRequirements($building);
        if ($this->planet->hasEnoughResources($resourceRequirements) === false) {
            throw new ResourceRequirementsNotMetException($building, $this->planet);
        }

        $upgradingEndTime = $this->buildingMetadataResolver->getUpgradingTime($building);
        $building->startUpgrading($upgradingEndTime);

        $resourceAmounts = [];
        foreach ($resourceRequirements as $requirement) {
            $resourceAmounts[$requirement->getResourceCode()] = $requirement->getAmount();
        }

        $this->eventRecorder->record(
            new BuildingUpgradeHasBeenStarted(
                $this->planet->getId(),
                $building->getCode(),
                $building->getLevel(),
                $resourceAmounts,
                $this->getMiningSpeedsArray($building)
            )
        );
    }

    private function getMiningSpeedsArray(BuildingInterface $building): array
    {
        $miningSpeedsArray = [];

        $miningSpeeds = $this->buildingMetadataResolver->getNextMiningSpeeds($building);
        foreach ($miningSpeeds as $miningSpeed) {
            $miningSpeedsArray[$miningSpeed->getResourceCode()] = $miningSpeed->getSpeed();
        }

        return $miningSpeedsArray;
    }

    public function cancelUpgrade(BuildingInterface $building): void
    {
        if ($this->isBuilt === false) {
            throw new AggregateRootNotBuiltException($this);
        }

        $building->cancelUpgrading();

        $resourceRequirements = $this->buildingMetadataResolver->getNextResourceRequirements($building);
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