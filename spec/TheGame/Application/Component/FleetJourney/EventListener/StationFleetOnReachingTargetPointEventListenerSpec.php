<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\EventListener;

use PhpSpec\ObjectBehavior;

final class StationFleetOnReachingTargetPointEventListenerSpec extends ObjectBehavior
{
    public function it_does_nothing_when_mission_type_is_not_stationing(): void
    {

    }

    public function it_throws_exception_when_planet_has_not_been_found(): void
    {

    }

    public function it_lands_on_planet_when_no_fleet_exists_on_the_planet(): void
    {

    }

    public function it_merges_incoming_fleet_with_the_fleet_already_stationing_on_planet(): void
    {

    }
}
