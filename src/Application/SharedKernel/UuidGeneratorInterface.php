<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel;

use TheGame\Application\Component\BuildingConstruction\Domain\BuildingIdInterface;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;
use TheGame\Application\Component\FleetJourney\Domain\JourneyIdInterface;
use TheGame\Application\Component\Galaxy\Domain\SolarSystemIdInterface;
use TheGame\Application\Component\ResourceMines\Domain\MineIdInterface;
use TheGame\Application\Component\ResourceStorage\Domain\StorageIdInterface;
use TheGame\Application\Component\Shipyard\Domain\JobIdInterface;
use TheGame\Application\Component\Shipyard\Domain\ShipyardIdInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetIdInterface;

interface UuidGeneratorInterface
{
    public function generateNewMineId(): MineIdInterface;

    public function generateNewStorageId(): StorageIdInterface;

    public function generateNewBuildingId(): BuildingIdInterface;

    public function generateNewShipyardId(): ShipyardIdInterface;

    public function generateNewShipyardJobId(): JobIdInterface;

    public function generateNewJourneyId(): JourneyIdInterface;

    public function generateNewFleetId(): FleetIdInterface;

    public function generateNewSolarSystemId(): SolarSystemIdInterface;

    public function generateNewPlanetId(): PlanetIdInterface;
}
