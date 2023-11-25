<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\Domain\Entity;

use PhpSpec\ObjectBehavior;

final class JourneySpec extends ObjectBehavior
{
    public function it_has_identifier(): void
    {
    }

    public function it_has_mission_type(): void
    {
    }

    public function it_has_start_point(): void
    {
    }

    public function it_has_target_point(): void
    {
    }

    public function it_has_return_point(): void
    {
    }

    public function it_remembers_when_started_mission(): void
    {
    }

    public function it_knows_when_planned_to_reach_the_target_point(): void
    {
    }

    public function it_knows_the_real_time_of_reaching_the_target_point(): void
    {
    }

    public function it_knows_when_planned_to_reach_the_return_point(): void
    {
    }

    public function it_knows_the_real_time_of_reaching_the_return_point(): void
    {
    }

    public function it_does_plan_to_station_on_the_target_point(): void
    {
    }

    public function it_doesnt_plan_to_station_on_the_target_point(): void
    {
    }

    public function it_does_attack(): void
    {
    }

    public function it_doesnt_attach(): void
    {
    }

    public function it_does_transport_resources(): void
    {
    }

    public function it_doesnt_transport_resources(): void
    {
    }

    public function it_does_flyback(): void
    {
    }

    public function it_did_reach_target_point(): void
    {
    }

    public function it_didnt_reach_target_point_yet(): void
    {
    }

    public function it_throws_exception_when_reaching_target_point_but_flying_time_didnt_pass(): void
    {
    }

    public function it_reaches_target_point_and_station_on_planet(): void
    {
    }

    public function it_reaches_target_point_and_turns_around(): void
    {
    }

    public function it_did_reach_return_point(): void
    {
    }

    public function it_didnt_reach_return_point_yet(): void
    {
    }

    public function it_throws_exception_when_reaching_return_point_not_being_on_flyback(): void
    {
    }

    public function it_throws_exception_when_reaching_return_point_but_flying_time_didnt_pass(): void
    {
    }

    public function it_reaches_return_point(): void
    {
    }

    public function it_is_cancelled(): void
    {
    }

    public function it_is_not_cancelled(): void
    {
    }

    public function it_throws_exception_when_cancelling_fleet_on_flyback(): void
    {
    }

    public function it_throws_exception_when_cancelling_fleet_which_did_reach_target_point(): void
    {
    }

    public function it_throws_exception_when_cancelling_fleet_which_did_reach_return_point(): void
    {
    }

    public function it_cancels_journey_and_turns_around(): void
    {
    }
}
