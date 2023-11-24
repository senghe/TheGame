<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Entity;

use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetAlreadyInJourneyException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetAlreadyLoadedException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetNotInJourneyYetException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughFleetLoadCapacityException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughShipsException;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;
use TheGame\Application\Component\FleetJourney\Domain\MissionType;
use TheGame\Application\Component\FleetJourney\Domain\ShipsGroupInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

class Fleet
{
    private ?Journey $currentJourney = null;

    /** @var array<ShipsGroupInterface> $ships */
    private array $ships;

    public function __construct(
        private readonly FleetIdInterface $fleetId,
        private GalaxyPointInterface $stationingPoint,
        private ResourcesInterface $resourcesLoad,
    ) {
    }

    public function getId(): FleetIdInterface
    {
        return $this->fleetId;
    }

    public function getStationingGalaxyPoint(): GalaxyPointInterface
    {
        return $this->stationingPoint;
    }

    public function landOnPlanet(GalaxyPointInterface $newStationingPoint): void
    {
        $this->stationingPoint = $newStationingPoint;
    }

    public function merge(Fleet $fleet): void
    {
        foreach ($fleet->ships as $shipsGroupToMerge) {
            foreach ($this->ships as $currentShipsGroup) {
                if ($currentShipsGroup->hasType($shipsGroupToMerge->getType())) {
                    $currentShipsGroup->merge($shipsGroupToMerge);

                    continue 2;
                }
            }
        }
    }

    /** @var array<ShipsGroupInterface> $ships */
    public function addShips(array $ships): void
    {
        if ($this->currentJourney === null) {
            throw new FleetAlreadyInJourneyException($this->fleetId);
        }

        foreach ($ships as $shipsToAdd) {
            foreach ($this->ships as $currentGroup) {
                if ($currentGroup->hasType($shipsToAdd->getType())) {
                    $currentGroup->merge($shipsToAdd);

                    continue 2;
                }
            }

            $this->ships[] = $shipsToAdd;
        }
    }

    public function getSpeed(): int
    {
        if (count($this->ships) === 0) {
            return 0;
        }

        $lowestSpeed = $this->ships[0]->getSpeed();
        foreach ($this->ships as $shipGroup) {
            if ($lowestSpeed > $shipGroup->getSpeed()) {
                $lowestSpeed = $shipGroup->getSpeed();
            }
        }

        return $lowestSpeed;
    }

    /** @var array<string, int> $shipsToCompare */
    public function hasMoreShipsThan(
        array $shipsToCompare,
    ): bool {
        if ($this->hasEnoughShips($shipsToCompare) === false) {
            return false;
        }

        foreach ($shipsToCompare as $shipType => $quantity) {
            foreach ($this->ships as $shipGroup) {
                if ($shipGroup->hasType($shipType) === false) {
                    continue;
                }

                if ($shipGroup->hasMoreShipsThan($quantity) === true) {
                    return true;
                }
            }
        }

        return false;
    }

    /** @var array<string, int> $shipsToCompare */
    public function hasEnoughShips(
        array $shipsToCompare,
    ): bool {
        foreach ($shipsToCompare as $shipType => $quantity) {
            $shipTypeFound = false;
            foreach ($this->ships as $shipGroup) {
                if ($shipGroup->hasType($shipType) === false) {
                    continue;
                }

                $shipTypeFound = true;
                if ($shipGroup->hasEnoughShips($quantity) === false) {
                    return false;
                }
            }

            if ($shipTypeFound === false) {
                return false;
            }
        }

        return true;
    }

    /** @var array<string, int> $shipsToSplit */
    public function split(
        array $shipsToSplit,
    ): array {
        if ($this->hasEnoughShips($shipsToSplit) === false) {
            throw new NotEnoughShipsException($this->fleetId);
        }

        $splitShips = [];

        foreach ($shipsToSplit as $shipType => $quantity) {
            foreach ($this->ships as $shipGroup) {
                if ($shipGroup->hasType($shipType) === false) {
                    continue;
                }

                $splitShips[] = $shipGroup->split($quantity);
            }
        }

        return $splitShips;
    }

    public function isDuringJourney(): bool
    {
        if ($this->currentJourney === null) {
            return false;
        }

        if ($this->currentJourney->didReachTargetPoint() === false && $this->currentJourney->didReachReturnPoint() === false) {
            return true;
        }

        return false;
    }

    public function startJourney(Journey $journey): void
    {
        if ($this->isDuringJourney()) {
            throw new FleetAlreadyInJourneyException($this->fleetId);
        }

        $this->currentJourney = $journey;
    }

    public function getJourneyMissionType(): MissionType
    {
        if ($this->isDuringJourney() === false) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        return $this->currentJourney->getMissionType();
    }

    public function getJourneyStartPoint(): GalaxyPointInterface
    {
        if ($this->isDuringJourney() === false) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        return $this->currentJourney->getStartPoint();
    }

    public function getJourneyTargetPoint(): GalaxyPointInterface
    {
        if ($this->isDuringJourney() === false) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        return $this->currentJourney->getTargetPoint();
    }

    public function didReachJourneyTargetPoint(): bool
    {
        return $this->currentJourney !== null
            && $this->currentJourney->didReachTargetPoint();
    }

    public function reachJourneyTargetPoint(): void
    {
        if ($this->isDuringJourney() === false) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        if ($this->currentJourney->didReachTargetPoint() === false) {
            return;
        }

        if ($this->currentJourney->doesPlanToStationOnTarget()) {
            $this->stationingPoint = $this->currentJourney->getTargetPoint();

            return;
        } elseif ($this->currentJourney->doesFlyBack()) {
            return;
        }

        $this->currentJourney->reachTargetPoint();
    }

    public function reachJourneyReturnPoint(): void
    {
        if ($this->isDuringJourney() === false) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        if ($this->currentJourney->didReachReturnPoint() === false) {
            return;
        }

        $this->currentJourney->reachReturnPoint();
    }

    public function didReturnFromJourney(): bool
    {
        return $this->currentJourney !== null
            && $this->currentJourney->didReachReturnPoint();
    }

    public function doesFlyBack(): bool
    {
        if ($this->currentJourney === null) {
            return false;
        }

        return $this->currentJourney->doesFlyBack();
    }

    public function cancelJourney(): void
    {
        if ($this->isDuringJourney() === false) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        $this->currentJourney->cancel();
    }

    public function getLoadCapacity(): int
    {
        $capacity = 0;
        foreach ($this->ships as $shipGroup) {
            $capacity += $shipGroup->getLoadCapacity();
        }

        return $capacity;
    }

    public function getResourcesLoad(): array
    {
        return $this->resourcesLoad->toScalarArray();
    }

    public function load(
        ResourcesInterface $resourcesLoad,
        ResourcesInterface $fuel,
    ): void {
        $loadTotal = $resourcesLoad->sum() + $fuel->sum();
        if ($loadTotal > $this->getLoadCapacity()) {
            throw new NotEnoughFleetLoadCapacityException(
                $this->fleetId,
                $this->getLoadCapacity(),
                $loadTotal,
            );
        }

        if ($this->resourcesLoad !== null) {
            throw new FleetAlreadyLoadedException($this->fleetId);
        }

        $this->resourcesLoad = $resourcesLoad;
    }

    public function unload(): ResourcesInterface
    {
        $load = clone $this->resourcesLoad;
        $this->resourcesLoad->clear();

        return $load;
    }
}
