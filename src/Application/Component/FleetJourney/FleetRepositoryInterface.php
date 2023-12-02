<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney;

use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\UserIdInterface;

interface FleetRepositoryInterface
{
    public function find(FleetIdInterface $fleetId): ?Fleet;

    public function findStationingOnPlanet(PlanetIdInterface $planetId): ?Fleet;

    /** @return array<Fleet> */
    public function findFlyingBackFromJourneyForUser(UserIdInterface $userId): array;

    /** @return array<Fleet> */
    public function findInJourneyForUser(UserIdInterface $userId): array;
}
