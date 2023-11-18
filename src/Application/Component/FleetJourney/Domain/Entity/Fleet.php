<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Entity;

use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetAlreadyInJourneyException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetNotInJourneyYetException;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;
use TheGame\Application\Component\FleetJourney\Domain\ShipGroupInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\UserIdInterface;

class Fleet
{
    private ?Journey $currentJourney = null;

    /** @var array<ShipGroupInterface> $ships */
    public function __construct(
        private readonly FleetIdInterface $fleetId,
        private readonly UserIdInterface $userId,
        private readonly PlanetIdInterface $currentStationId,
        private array $ships,
    ) {

    }

    public function getId(): FleetIdInterface
    {
        return $this->fleetId;
    }

    /** @var array<ShipGroupInterface> $ships */
    public function addShips(array $ships): void
    {
        if ($this->currentJourney === null) {
            throw new FleetAlreadyInJourneyException($this->fleetId);
        }

        foreach ($ships as $shipsToAdd) {
            foreach ($this->ships as $currentGroup) {
                if ($currentGroup->canBeMerged($shipsToAdd)) {
                    $currentGroup->merge($shipsToAdd);

                    continue 2;
                }
            }

            $this->ships[] = $shipsToAdd;
        }
    }

    /** @var array<ShipGroupInterface> $ships */
    public function startJourney(
        Journey $journey,
        array $ships,
    ): void {
        if ($this->currentJourney === null) {
            throw new FleetAlreadyInJourneyException($this->fleetId);
        }

        foreach ($ships as $shipGroupInJourney) {
            foreach ($this->ships as $shipGroup) {
                if ($shipGroup->canBeSplit($shipGroupInJourney)) {

                }
            }
        }

        $this->currentJourney = $journey;
    }

    public function cancelCurrentJourney(): void
    {
        if ($this->currentJourney === null) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        $this->currentJourney->cancel();
    }

    public function finishJourney(): void
    {
        if ($this->currentJourney === null) {
            throw new FleetNotInJourneyYetException($this->fleetId);
        }

        $this->currentJourney = null;
    }
}
