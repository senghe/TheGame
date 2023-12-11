<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Entity;

use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetAlreadyInJourneyException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetAlreadyLoadedException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetHasNotYetReachedTheTargetPointException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetNotInJourneyYetException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughFleetLoadCapacityException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughShipsException;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;
use TheGame\Application\Component\FleetJourney\Domain\ShipClass;
use TheGame\Application\Component\FleetJourney\Domain\ShipsGroupInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\FleetMissionType;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

class Fleet
{
    private ?Journey $currentJourney = null;

    /** @param array<ShipsGroupInterface> $ships */
    public function __construct(
        private readonly FleetIdInterface $fleetId,
        private GalaxyPointInterface $stationingPoint,
        private ResourcesInterface $resourcesLoad,
        private array $ships = [],
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
        $this->addShips($fleet->ships);
    }

    public function containsShipsOfClass(ShipClass $class): bool
    {
        foreach ($this->ships as $shipsGroup) {
            if ($shipsGroup->getShipClass() === $class) {
                return $shipsGroup->getQuantity() > 0;
            }
        }

        return false;
    }

    /** @param array<ShipsGroupInterface> $ships */
    public function addShips(array $ships): void
    {
        if ($this->currentJourney !== null) {
            throw new FleetAlreadyInJourneyException($this->fleetId);
        }

        foreach ($ships as $shipsToAdd) {
            foreach ($this->ships as $currentGroup) {
                if ($currentGroup->hasShip($shipsToAdd->getShipName())) {
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

    /** @param array<string, int> $shipsToCompare */
    public function hasEnoughShips(
        array $shipsToCompare,
    ): bool {
        if (count($shipsToCompare) === 0) {
            return false;
        }

        foreach ($shipsToCompare as $shipName => $quantity) {
            $shipNameFound = false;
            foreach ($this->ships as $shipGroup) {
                if ($shipGroup->hasShip($shipName) === false) {
                    continue;
                }

                $shipNameFound = true;
                if ($shipGroup->hasEnoughShips($quantity) === false) {
                    return false;
                }
            }

            if ($shipNameFound === false) {
                return false;
            }
        }

        return true;
    }

    /** @param array<string, int> $shipsToCompare */
    public function hasMoreShipsThan(
        array $shipsToCompare,
    ): bool {
        if (count($shipsToCompare) === 0 && count($this->ships) > 0) {
            return true;
        }

        if ($this->hasEnoughShips($shipsToCompare) === false) {
            return false;
        }

        foreach ($shipsToCompare as $shipName => $quantity) {
            foreach ($this->ships as $shipGroup) {
                if ($shipGroup->hasShip($shipName) === false) {
                    continue;
                }

                if ($shipGroup->hasMoreShipsThan($quantity) === true) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param array<string, int> $shipsToSplit
     * @return array<ShipsGroupInterface>
     */
    public function split(
        array $shipsToSplit,
    ): array {
        if ($this->hasEnoughShips($shipsToSplit) === false) {
            throw new NotEnoughShipsException($this->fleetId);
        }

        $splitShips = [];
        foreach ($shipsToSplit as $shipName => $quantity) {
            if ($quantity <= 0) {
                continue;
            }

            foreach ($this->ships as $shipGroup) {
                if ($shipGroup->hasShip($shipName) === false) {
                    continue;
                }

                $splitShips[] = $shipGroup->split($quantity);
            }
        }

        return $splitShips;
    }

    private function getCurrentJourney(): Journey
    {
        if ($this->currentJourney === null) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        return $this->currentJourney;
    }

    public function isDuringJourney(): bool
    {
        if ($this->currentJourney === null) {
            return false;
        }

        if ($this->getCurrentJourney()->didReachTargetPoint() === false && $this->getCurrentJourney()->didReachReturnPoint() === false) {
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

    public function getJourneyMissionType(): FleetMissionType
    {
        if ($this->isDuringJourney() === false) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        return $this->getCurrentJourney()->getMissionType();
    }

    public function getJourneyStartPoint(): GalaxyPointInterface
    {
        if ($this->isDuringJourney() === false) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        return $this->getCurrentJourney()->getStartPoint();
    }

    public function getJourneyTargetPoint(): GalaxyPointInterface
    {
        if ($this->isDuringJourney() === false) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        return $this->getCurrentJourney()->getTargetPoint();
    }

    public function getJourneyReturnPoint(): GalaxyPointInterface
    {
        if ($this->isDuringJourney() === false) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        return $this->getCurrentJourney()->getReturnPoint();
    }

    public function didReachJourneyTargetPoint(): bool
    {
        return $this->currentJourney !== null
            && $this->getCurrentJourney()->didReachTargetPoint();
    }

    public function tryToReachJourneyTargetPoint(): void
    {
        $hasStartedJourney = $this->currentJourney !== null;
        if (! $hasStartedJourney) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        $hasFinishedJourney = $this->getCurrentJourney()->didReachReturnPoint() === true;
        if ($hasFinishedJourney) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        if ($this->getCurrentJourney()->didReachTargetPoint() === false) {
            return;
        }

        if ($this->getCurrentJourney()->doesPlanToStationOnTarget()) {
            $this->stationingPoint = $this->getCurrentJourney()->getTargetPoint();
            $this->getCurrentJourney()->reachTargetPoint();

            return;
        } elseif ($this->getCurrentJourney()->doesFlyBack()) {
            return;
        }

        $this->getCurrentJourney()->reachTargetPoint();
    }

    public function tryToReachJourneyReturnPoint(): void
    {
        $hasStartedJourney = $this->currentJourney !== null;

        if (! $hasStartedJourney) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        if ($this->getCurrentJourney()->didReachTargetPoint() === false) {
            throw new FleetHasNotYetReachedTheTargetPointException($this->fleetId);
        }

        if ($this->getCurrentJourney()->didReachReturnPoint() === false) {
            return;
        }

        $this->getCurrentJourney()->reachReturnPoint();
    }

    public function didReturnFromJourney(): bool
    {
        return $this->currentJourney !== null
            && $this->getCurrentJourney()->didReachReturnPoint();
    }

    public function doesFlyBack(): bool
    {
        if ($this->currentJourney === null) {
            return false;
        }

        return $this->getCurrentJourney()->doesFlyBack();
    }

    public function cancelJourney(): void
    {
        if ($this->currentJourney !== null && $this->getCurrentJourney()->didReachTargetPoint()) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        $this->getCurrentJourney()->cancel();
    }

    public function getLoadCapacity(): int
    {
        $capacity = 0;
        foreach ($this->ships as $shipGroup) {
            $capacity += $shipGroup->getLoadCapacity();
        }

        return $capacity;
    }

    /** @return array<string, int> */
    public function getResourcesLoad(): array
    {
        return $this->resourcesLoad->toScalarArray();
    }

    public function load(
        ResourcesInterface $resourcesLoad,
    ): void {
        $loadTotal = $resourcesLoad->sum();
        if ($loadTotal > $this->getLoadCapacity()) {
            throw new NotEnoughFleetLoadCapacityException(
                $this->fleetId,
                $this->getLoadCapacity(),
                $loadTotal,
            );
        }

        if ($this->resourcesLoad->sum() > 0) {
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
