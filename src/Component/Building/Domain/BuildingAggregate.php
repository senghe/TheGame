<?php

declare(strict_types=1);

namespace App\Component\Building\Domain;

use App\Component\Building\Domain\Entity\BuildingInterface;
use App\Component\Building\Domain\Event\BuildingUpgradeHasBeenCancelled;
use App\Component\Building\Domain\Event\BuildingUpgradeHasBeenStarted;
use App\Component\Building\Domain\Exception\ResourceRequirementsNotMetException;
use App\Component\Building\Domain\Service\BuildingMetadataResolverInterface;
use App\Component\Building\Port\EventRecorderInterface;
use App\SharedKernel\Domain\Entity\PlanetInterface;
use App\SharedKernel\EntityInterface;
use App\SharedKernel\Exception\AggregateRootNotBuiltException;
use InvalidArgumentException;

final class BuildingAggregate implements AggregateInterface
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

    public function setAggregateRoot(EntityInterface $planet): void
    {
        if ($planet instanceof PlanetInterface::class === false) {
            throw new InvalidArgumentException(sprintf('%s class accepts only %s entities', self::class, PlanetInterface::class));
        }

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
                $this->buildingMetadataResolver->isMine($building),
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
                $building->getLevel(),
                $resourceAmounts,
                $this->buildingMetadataResolver->isMine($building),
                $this->getMiningSpeedsArray($building)
            )
        );
    }
}