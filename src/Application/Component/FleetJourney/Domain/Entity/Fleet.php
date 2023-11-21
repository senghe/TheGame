<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Entity;

use TheGame\Application\Component\FleetJourney\Domain\Exception\CannotCancelFleetJourneyOnComeBackException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetAlreadyInJourneyException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetNotInJourneyYetException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughShipsException;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;
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
        private ResourcesInterface $load,
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

    /** @var array<string, int> $shipsToCompare */
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
        return $this->currentJourney !== null;
    }

    public function startJourney(Journey $journey): void
    {
        if ($this->isDuringJourney()) {
            throw new FleetAlreadyInJourneyException($this->fleetId);
        }

        $this->currentJourney = $journey;
    }

    public function reachJourneyTarget(): void
    {
        if ($this->isDuringJourney() === false) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        if ($this->currentJourney->isFinished() === false) {
            return;
        }

        if ($this->currentJourney->doesPlanToStationOnTarget()) {
            $this->stationingPoint = $this->currentJourney->getTargetGalaxyPoint();
            $this->currentJourney = null;

            return;
        } else if ($this->currentJourney->doesComeBack()) {
            $this->currentJourney = null;

            return;
        }

        $this->currentJourney->comeBack();
    }

    public function cancelJourney(): void
    {
        if ($this->isDuringJourney() === false) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        if ($this->currentJourney->doesComeBack() === true) {
            throw new CannotCancelFleetJourneyOnComeBackException($this->fleetId);
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

    public function unload(): ResourcesInterface
    {
        $load = clone $this->load;
        $this->load->clear();

        return $load;
    }
}
