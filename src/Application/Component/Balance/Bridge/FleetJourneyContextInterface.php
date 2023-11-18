<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Balance\Bridge;

interface FleetJourneyContextInterface
{
    public function getShipBaseSpeed(string $shipType): int;

    public function getShipCapacityLoad(string $type): int;

    public function getJourneyDuration(
        int $speed,
        int $fromGalaxy, int $fromSolarSystem, int $fromPlanet,
        int $toGalaxy, int $toSolarSystem, int $toPlanet,
    ): int;
}
