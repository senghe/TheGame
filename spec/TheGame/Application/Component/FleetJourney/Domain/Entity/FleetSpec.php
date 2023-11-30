<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\Domain\Entity;

use PhpSpec\ObjectBehavior;

final class FleetSpec extends ObjectBehavior
{
    public function it_has_identifier(): void
    {
    }

    public function it_is_stationing_on_the_planet(): void
    {
    }

    public function it_lands_on_different_planet(): void
    {
    }

    public function it_merges_two_fleets_with_identical_ships(): void
    {
    }

    public function it_throws_exception_adding_ships_when_fleet_is_during_journey(): void
    {
    }

    public function it_adds_ships_of_new_type(): void
    {
    }

    public function it_adds_ships_of_known_type(): void
    {
    }

    public function it_returns_zero_speed_on_empty_fleet(): void
    {
    }

    public function it_returns_speed_of_slowest_ship_in_the_fleet(): void
    {
    }

    public function it_has_enough_ships_comparing_two_groups_of_ships(): void
    {
    }

    public function it_has_not_enough_ships_comparing_two_groups_of_ships(): void
    {
    }

    public function it_has_not_enough_ships_when_ship_type_has_not_been_found(): void
    {
    }

    public function it_has_enough_ships_on_empty_array(): void
    {
    }

    public function it_has_more_ships_when_empty_array_is_given_as_an_input_but_it_has_any_ships_group(): void
    {
    }

    public function it_hasnt_more_ships_than_input_when_there_is_not_enough_ships(): void
    {
    }

    public function it_has_more_ships_than_input_when_at_least_one_ship_group_has_more_ships(): void
    {
    }

    public function it_hasnt_more_ships_than_input_when_no_ship_group_has_more_ships(): void
    {
    }

    public function it_throws_exception_when_cannot_split_fleet_having_not_enough_ships(): void
    {
    }

    public function it_splits_ships(): void
    {
    }

    public function it_omits_splitting_ships_group_when_requested_zero_ships_for_split(): void
    {
    }

    public function it_is_not_during_journey_when_journey_is_null(): void
    {
    }

    public function it_is_during_journey_when_didnt_reach_target_and_return_points(): void
    {
    }

    public function it_is_not_during_journey_when_did_reach_target_point(): void
    {
    }

    public function it_is_not_during_journey_when_did_reach_return_point(): void
    {
    }

    public function it_throws_exception_when_trying_to_start_journey_already_being_in_journey(): void
    {
    }

    public function it_starts_the_journey(): void
    {
    }

    public function it_throws_exception_on_returning_mission_type_not_being_in_journey(): void
    {
    }

    public function it_returns_journey_mission_type(): void
    {
    }

    public function it_throws_exception_on_returning_journey_start_point_not_being_in_journey(): void
    {
    }

    public function it_returns_journey_start_point(): void
    {
    }

    public function it_throws_exception_on_returning_journey_target_point_not_being_in_journey(): void
    {
    }

    public function it_returns_journey_target_point(): void
    {
    }

    public function it_throws_exception_on_returning_journey_return_point_not_being_in_journey(): void
    {
    }

    public function it_returns_journey_return_point(): void
    {
    }

    public function it_didnt_reach_journey_target_point_when_journey_is_null(): void
    {
    }

    public function it_didnt_reach_journey_target_point_when_journey_tells_that(): void
    {
    }

    public function it_did_reach_journey_target_point_when_journey_tells_that(): void
    {
    }

    public function it_throws_exception_trying_to_reach_journey_target_point_when_fleet_is_not_in_journey_yet(): void
    {
    }

    public function it_returns_early_when_trying_to_reach_journey_target_point_but_not_reached_it_yet(): void
    {
    }

    public function it_reaches_journey_target_point_and_station_there(): void
    {
    }

    public function it_reached_journey_target_point_and_currently_flies_back(): void
    {
    }

    public function it_reaches_journey_target_point_but_doesnt_fly_back(): void
    {
    }

    public function it_throws_exception_trying_to_reach_journey_return_point_when_fleet_is_not_in_the_journey_yet(): void
    {
    }

    public function it_returns_early_when_trying_to_reach_journey_return_point_but_not_reached_it_yet(): void
    {
    }

    public function it_reaches_journey_return_point(): void
    {
    }

    public function it_didnt_return_from_journey_when_journey_is_null(): void
    {
    }

    public function it_didnt_return_from_journey_when_journey_says_that(): void
    {
    }

    public function it_did_return_from_journey_when_journey_says_that(): void
    {
    }

    public function it_does_no_flyback_when_journey_is_null(): void
    {
    }

    public function it_does_no_flyback_when_journey_tells_that(): void
    {
    }

    public function it_does_the_flyback_when_journey_tells_that(): void
    {
    }

    public function it_throws_exception_on_cancelling_journey_which_is_null(): void
    {
    }

    public function it_cancels_journey(): void
    {
    }

    public function it_returns_load_capacity(): void
    {
    }

    public function it_returns_zero_load_capacity_when_has_no_ships(): void
    {
    }

    public function it_returns_resource_load_as_scalar_array(): void
    {
    }

    public function it_throws_exception_when_loading_resources_and_fuel_but_there_is_no_enough_capacity(): void
    {
    }

    public function it_throws_exception_when_loading_resources_on_already_loaded_fleet(): void
    {
    }

    public function it_loads_resources_and_fuel(): void
    {
    }

    public function it_unloads_resources(): void
    {
    }
}
