<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney;

use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;

interface FleetRepositoryInterface
{
    public function find(FleetIdInterface $fleetId): ?Fleet;

    public function findStationingOnPlanet(PlanetIdInterface $planetId): ?Fleet;
}
