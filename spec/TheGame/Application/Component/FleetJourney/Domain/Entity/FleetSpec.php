<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\Domain\Entity;

use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\FleetJourney\Domain\Entity\Journey;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetAlreadyInJourneyException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetAlreadyLoadedException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetHasNotYetReachedTheTargetPointException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetNotInJourneyYetException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughFleetLoadCapacityException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughShipsException;
use TheGame\Application\Component\FleetJourney\Domain\FleetId;
use TheGame\Application\Component\FleetJourney\Domain\MissionType;
use TheGame\Application\Component\FleetJourney\Domain\ShipsGroupInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPoint;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;
use TheGame\Application\SharedKernel\Domain\ResourceId;
use TheGame\Application\SharedKernel\Domain\Resources;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

final class FleetSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->initialize([]);
    }

    private function initialize(array $ships): void
    {
        $fleetId = new FleetId("c5ec0807-a6e0-4555-b1ab-4ca6ec3bfb93");
        $stationingPoint = new GalaxyPoint(1, 2, 3);

        $resourcesLoad = new Resources();
        $resourceAmount1 = new ResourceAmount(
            new ResourceId("7dbe6a5c-e12c-4325-a38a-f2165873c263"),
            500,
        );
        $resourcesLoad->addResource($resourceAmount1);

        $resourceAmount2 = new ResourceAmount(
            new ResourceId("e2a1295c-9390-47b9-99c6-dd5f0798954d"),
            350,
        );
        $resourcesLoad->addResource($resourceAmount2);

        $this->beConstructedWith(
            $fleetId,
            $stationingPoint,
            $resourcesLoad,
            $ships,
        );
    }

    public function it_has_identifier(): void
    {
        $this->getId()->getUuid()->shouldReturn("c5ec0807-a6e0-4555-b1ab-4ca6ec3bfb93");
    }

    public function it_is_stationing_on_the_planet(): void
    {
        $this->getStationingGalaxyPoint()->format()->shouldReturn("[1:2:3]");
    }

    public function it_lands_on_different_planet(): void
    {
        $this->landOnPlanet(new GalaxyPoint(4, 5, 6));

        $this->getStationingGalaxyPoint()->format()->shouldReturn("[4:5:6]");
    }

    public function it_merges_two_fleets(
    ): void {
        throw new SkippingException("Cannot test the behaviour with current implementation");
    }

    public function it_adds_ships_of_new_type(
        ShipsGroupInterface $lightFighterShipsGroup,
        ShipsGroupInterface $warshipShipsGroup,
        ShipsGroupInterface $destroyerShipsGroup,
    ): void {
        $this->initialize([
            $lightFighterShipsGroup->getWrappedObject(),
            $warshipShipsGroup->getWrappedObject(),
        ]);

        $destroyerType = 'destroyer';
        $destroyerShipsGroup->getType()
            ->willReturn($destroyerType);

        $destroyerShipsGroup->hasType($destroyerType)
            ->willReturn(true);

        $lightFighterShipsGroup->hasType($destroyerType)
            ->willReturn(false);

        $warshipShipsGroup->hasType($destroyerType)
            ->willReturn(false);

        $destroyerShipsGroup->hasEnoughShips(10)
            ->willReturn(true);

        $this->addShips([
            $destroyerShipsGroup,
        ]);

        $this->hasEnoughShips([
            $destroyerType => 10,
        ])
            ->shouldReturn(true);
    }

    public function it_adds_ships_of_known_type(
        ShipsGroupInterface $lightFighterShipsGroup,
        ShipsGroupInterface $warshipShipsGroup,
        ShipsGroupInterface $lightFightersToAdd,
    ): void {
        $this->initialize([
            $lightFighterShipsGroup->getWrappedObject(),
            $warshipShipsGroup->getWrappedObject(),
        ]);

        $lightFighterType = 'light-fighter';
        $lightFightersToAdd->getType()
            ->willReturn($lightFighterType);

        $lightFightersToAdd->hasType($lightFighterType)
            ->willReturn(true);

        $lightFighterShipsGroup->hasType($lightFighterType)
            ->willReturn(true);

        $lightFighterShipsGroup->merge($lightFightersToAdd)
            ->shouldBeCalledOnce();

        $warshipShipsGroup->hasType($lightFighterType)
            ->willReturn(false);

        $this->addShips([
            $lightFightersToAdd,
        ]);
    }

    public function it_throws_exception_on_adding_ships_to_a_fleet_already_being_in_journey(
        Journey $journey,
        ShipsGroupInterface $destroyerShipsGroup,
    ): void {
        $this->startJourney($journey);

        $this->shouldThrow(FleetAlreadyInJourneyException::class)
            ->during('addShips', [[$destroyerShipsGroup]]);
    }

    public function it_returns_zero_speed_on_empty_fleet(): void
    {
        $this->initialize([]);

        $this->getSpeed()->shouldReturn(0);
    }

    public function it_returns_speed_of_slowest_ship_in_the_fleet(
        ShipsGroupInterface $lightFighterShipsGroup,
        ShipsGroupInterface $warshipShipsGroup,
    ): void {
        $this->initialize([
            $lightFighterShipsGroup->getWrappedObject(),
            $warshipShipsGroup->getWrappedObject(),
        ]);

        $lightFighterShipsGroup->getSpeed()->willReturn(15);
        $warshipShipsGroup->getSpeed()->willReturn(500);

        $this->getSpeed()->shouldReturn(15);
    }

    public function it_has_enough_ships_comparing_two_groups_of_ships(
        ShipsGroupInterface $warshipShipsGroup,
        ShipsGroupInterface $lightFighterShipsGroup,
    ): void {
        $this->initialize([
            $warshipShipsGroup->getWrappedObject(),
            $lightFighterShipsGroup->getWrappedObject(),
        ]);

        $lightFighterType = 'light-fighter';
        $lightFighterShipsGroup->hasType($lightFighterType)->willReturn(true);
        $warshipShipsGroup->hasType($lightFighterType)->willReturn(false);

        $lightFighterShipsGroup->hasEnoughShips(15)
            ->willReturn(true);

        $this->hasEnoughShips([
            $lightFighterType => 15,
        ])->shouldReturn(true);
    }

    public function it_has_not_enough_ships_comparing_two_groups_of_ships(
        ShipsGroupInterface $warshipShipsGroup,
        ShipsGroupInterface $lightFighterShipsGroup,
    ): void {
        $this->initialize([
            $warshipShipsGroup->getWrappedObject(),
            $lightFighterShipsGroup->getWrappedObject(),
        ]);

        $lightFighterType = 'light-fighter';
        $warshipShipsGroup->hasType($lightFighterType)->willReturn(false);

        $lightFighterShipsGroup->hasType($lightFighterType)->willReturn(true);
        $lightFighterShipsGroup->hasEnoughShips(15)
            ->willReturn(false);

        $this->hasEnoughShips([
            $lightFighterType => 15,
        ])->shouldReturn(false);
    }

    public function it_has_not_enough_ships_when_ship_type_has_not_been_found(
        ShipsGroupInterface $warshipShipsGroup,
    ): void {
        $this->initialize([
            $warshipShipsGroup->getWrappedObject(),
        ]);

        $lightFighterType = 'light-fighter';
        $warshipShipsGroup->hasType($lightFighterType)->willReturn(false);

        $this->hasEnoughShips([
            $lightFighterType => 15,
        ])->shouldReturn(false);
    }

    public function it_has_enough_ships_on_empty_input_array(
        ShipsGroupInterface $warshipShipsGroup,
        ShipsGroupInterface $lightFighterShipsGroup,
    ): void {
        $this->initialize([
            $warshipShipsGroup->getWrappedObject(),
            $lightFighterShipsGroup->getWrappedObject(),
        ]);

        $this->hasEnoughShips([])->shouldReturn(false);
    }

    public function it_has_more_ships_when_empty_array_is_given_as_an_input_but_it_has_any_ships_group(
        ShipsGroupInterface $warshipShipsGroup,
        ShipsGroupInterface $lightFighterShipsGroup,
    ): void {
        $this->initialize([
            $warshipShipsGroup->getWrappedObject(),
            $lightFighterShipsGroup->getWrappedObject(),
        ]);

        $this->hasMoreShipsThan([])->shouldReturn(true);
    }

    public function it_hasnt_more_ships_than_input_when_there_is_not_enough_ships(
        ShipsGroupInterface $warshipShipsGroup,
        ShipsGroupInterface $lightFighterShipsGroup,
    ): void {
        $this->initialize([
            $warshipShipsGroup->getWrappedObject(),
            $lightFighterShipsGroup->getWrappedObject(),
        ]);

        $lightFighterType = 'light-fighter';
        $warshipShipsGroup->hasType($lightFighterType)->willReturn(false);

        $lightFighterShipsGroup->hasType($lightFighterType)->willReturn(true);
        $lightFighterShipsGroup->hasEnoughShips(15)->willReturn(true);
        $lightFighterShipsGroup->hasMoreShipsThan(15)->willReturn(false);

        $this->hasMoreShipsThan([
            $lightFighterType => 15,
        ])->shouldReturn(false);
    }

    public function it_has_more_ships_than_input_when_has_enough_of_all_ships_and_at_least_one_ship_group_has_more_ships(
        ShipsGroupInterface $warshipShipsGroup,
        ShipsGroupInterface $lightFighterShipsGroup,
    ): void {
        $this->initialize([
            $warshipShipsGroup->getWrappedObject(),
            $lightFighterShipsGroup->getWrappedObject(),
        ]);

        $lightFighterType = 'light-fighter';
        $warshipType = 'warship';
        $warshipShipsGroup->hasType($lightFighterType)->willReturn(false);
        $warshipShipsGroup->hasType($warshipType)->willReturn(true);

        $warshipShipsGroup->hasEnoughShips(300)->willReturn(true);
        $warshipShipsGroup->hasMoreShipsThan(300)->willReturn(false);

        $lightFighterShipsGroup->hasType($lightFighterType)->willReturn(true);
        $lightFighterShipsGroup->hasType($warshipType)->willReturn(false);

        $lightFighterShipsGroup->hasEnoughShips(15)->willReturn(true);
        $lightFighterShipsGroup->hasMoreShipsThan(15)->willReturn(true);

        $this->hasMoreShipsThan([
            $lightFighterType => 15,
            $warshipType => 300,
        ])->shouldReturn(true);
    }

    public function it_hasnt_more_ships_than_input_when_no_ship_group_has_more_ships(
        ShipsGroupInterface $warshipShipsGroup,
        ShipsGroupInterface $lightFighterShipsGroup,
    ): void {
        $this->initialize([
            $warshipShipsGroup->getWrappedObject(),
            $lightFighterShipsGroup->getWrappedObject(),
        ]);

        $lightFighterType = 'light-fighter';
        $warshipType = 'warship';
        $warshipShipsGroup->hasType($lightFighterType)->willReturn(false);
        $warshipShipsGroup->hasType($warshipType)->willReturn(true);

        $warshipShipsGroup->hasEnoughShips(300)->willReturn(true);
        $warshipShipsGroup->hasMoreShipsThan(300)->willReturn(false);

        $lightFighterShipsGroup->hasType($lightFighterType)->willReturn(true);
        $lightFighterShipsGroup->hasType($warshipType)->willReturn(false);

        $lightFighterShipsGroup->hasEnoughShips(15)->willReturn(true);
        $lightFighterShipsGroup->hasMoreShipsThan(15)->willReturn(false);

        $this->hasMoreShipsThan([
            $lightFighterType => 15,
            $warshipType => 300,
        ])->shouldReturn(false);
    }

    public function it_throws_exception_when_cannot_split_fleet_having_not_enough_ships(
        ShipsGroupInterface $lightFighterShipsGroup,
    ): void {
        $lightFighterType = 'light-fighter';
        $lightFighterShipsGroup->hasType($lightFighterType)->willReturn(true);
        $lightFighterShipsGroup->hasEnoughShips(15)->willReturn(false);

        $this->shouldThrow(NotEnoughShipsException::class)->during('split', [
            [
                $lightFighterType => 15,
            ],
        ]);
    }

    public function it_splits_ships(
        ShipsGroupInterface $lightFighterShipsGroup,
        ShipsGroupInterface $splitLightFighterShipsGroup,
    ): void {
        $this->initialize([
            $lightFighterShipsGroup,
        ]);

        $lightFighterType = 'light-fighter';
        $lightFighterShipsGroup->hasType($lightFighterType)->willReturn(true);
        $lightFighterShipsGroup->hasEnoughShips(15)->willReturn(true);
        $lightFighterShipsGroup->split(15)->willReturn($splitLightFighterShipsGroup);

        $splitLightFighterShipsGroup->getType()->willReturn($lightFighterType);
        $splitLightFighterShipsGroup->getQuantity()->willReturn(15);

        $splitResult = $this->split([
            $lightFighterType => 15,
        ]);
        $splitResult->shouldBeArray();
        $splitResult->shouldHaveCount(1);
        $splitResult[0]->shouldImplement(ShipsGroupInterface::class);
        $splitResult[0]->getType()->shouldReturn($lightFighterType);
        $splitResult[0]->getQuantity()->shouldReturn(15);
    }

    public function it_does_nothing_on_splitting_fleet_when_requested_quantity_is_zero(
        ShipsGroupInterface $lightFighterShipsGroup,
    ): void {
        $lightFighterType = 'light-fighter';
        $lightFighterShipsGroup->hasType($lightFighterType)->willReturn(true);

        $this->shouldThrow(NotEnoughShipsException::class)->during('split', [
            [
                $lightFighterType => 0,
            ],
        ]);
    }

    public function it_does_nothing_on_splitting_fleet_when_requested_quantity_is_less_than_zero(
        ShipsGroupInterface $lightFighterShipsGroup,
    ): void {
        $lightFighterType = 'light-fighter';
        $lightFighterShipsGroup->hasType($lightFighterType)->willReturn(true);

        $this->shouldThrow(NotEnoughShipsException::class)->during('split', [
            [
                $lightFighterType => -15,
            ],
        ]);
    }

    public function it_omits_splitting_ships_group_when_requested_zero_ships_for_split(
        ShipsGroupInterface $warshipShipsGroup,
        ShipsGroupInterface $lightFighterShipsGroup,
        ShipsGroupInterface $splitLightFighterShipsGroup,
    ): void {
        $this->initialize([
            $warshipShipsGroup,
            $lightFighterShipsGroup,
        ]);

        $lightFighterType = 'light-fighter';
        $warshipType = 'warship';

        $warshipShipsGroup->hasType($lightFighterType)->willReturn(false);
        $warshipShipsGroup->hasType($warshipType)->willReturn(true);
        $warshipShipsGroup->hasEnoughShips(0)->willReturn(true);

        $lightFighterShipsGroup->hasType($warshipType)->willReturn(false);
        $lightFighterShipsGroup->hasType($lightFighterType)->willReturn(true);
        $lightFighterShipsGroup->hasEnoughShips(15)->willReturn(true);
        $lightFighterShipsGroup->split(15)->willReturn($splitLightFighterShipsGroup);

        $splitLightFighterShipsGroup->getType()->willReturn($lightFighterType);
        $splitLightFighterShipsGroup->getQuantity()->willReturn(15);

        $splitResult = $this->split([
            $warshipType => 0,
            $lightFighterType => 15,
        ]);
        $splitResult->shouldBeArray();
        $splitResult->shouldHaveCount(1);
        $splitResult[0]->shouldImplement(ShipsGroupInterface::class);
        $splitResult[0]->getType()->shouldReturn($lightFighterType);
        $splitResult[0]->getQuantity()->shouldReturn(15);
    }

    public function it_is_not_during_journey_when_journey_is_null(): void
    {
        $this->isDuringJourney()->shouldReturn(false);
    }

    public function it_is_during_journey_when_didnt_reach_target_and_return_points(
        Journey $journey,
    ): void {
        $journey->didReachTargetPoint()->willReturn(false);
        $journey->didReachReturnPoint()->willReturn(false);

        $this->startJourney($journey);

        $this->isDuringJourney()->shouldReturn(true);
    }

    public function it_is_not_during_journey_when_did_reach_target_point(
        Journey $journey,
    ): void {
        $journey->didReachTargetPoint()->willReturn(true);
        $journey->didReachReturnPoint()->willReturn(false);

        $this->startJourney($journey);

        $this->isDuringJourney()->shouldReturn(false);
    }

    public function it_is_not_during_journey_when_did_reach_return_point(
        Journey $journey,
    ): void {
        $journey->didReachTargetPoint()->willReturn(false);
        $journey->didReachReturnPoint()->willReturn(true);

        $this->startJourney($journey);

        $this->isDuringJourney()->shouldReturn(false);
    }

    public function it_throws_exception_when_trying_to_start_journey_already_being_in_journey(
        Journey $currentJourney,
        Journey $nextJourney,
    ): void {
        $this->startJourney($currentJourney);

        $currentJourney->didReachTargetPoint()->willReturn(false);
        $currentJourney->didReachReturnPoint()->willReturn(false);

        $this->shouldThrow(FleetAlreadyInJourneyException::class)->during('startJourney', [$nextJourney]);
    }

    public function it_starts_the_journey(
        Journey $journey,
    ): void {
        $this->startJourney($journey);
    }

    public function it_throws_exception_on_returning_mission_type_not_being_in_journey(): void
    {
        $this->shouldThrow(FleetNotInJourneyYetException::class)->during('getJourneyMissionType', []);
    }

    public function it_returns_journey_mission_type(
        Journey $journey,
    ): void {
        $this->startJourney($journey);

        $journey->didReachTargetPoint()->willReturn(false);
        $journey->didReachReturnPoint()->willReturn(false);
        $journey->getMissionType()->willReturn(MissionType::Transport);

        $this->getJourneyMissionType()->shouldReturn(MissionType::Transport);
    }

    public function it_throws_exception_on_returning_journey_start_point_not_being_in_journey(): void
    {
        $this->shouldThrow(FleetNotInJourneyYetException::class)->during('getJourneyStartPoint', []);
    }

    public function it_returns_journey_start_point(
        Journey $journey,
        GalaxyPointInterface $startPoint,
    ): void {
        $this->startJourney($journey);

        $journey->didReachTargetPoint()->willReturn(false);
        $journey->didReachReturnPoint()->willReturn(false);
        $journey->getStartPoint()->willReturn($startPoint);

        $this->getJourneyStartPoint()->shouldReturn($startPoint);
    }

    public function it_throws_exception_on_returning_journey_target_point_not_being_in_journey(): void
    {
        $this->shouldThrow(FleetNotInJourneyYetException::class)->during('getJourneyTargetPoint', []);
    }

    public function it_returns_journey_target_point(
        Journey $journey,
        GalaxyPointInterface $targetPoint,
    ): void {
        $this->startJourney($journey);

        $journey->didReachTargetPoint()->willReturn(false);
        $journey->didReachReturnPoint()->willReturn(false);
        $journey->getTargetPoint()->willReturn($targetPoint);

        $this->getJourneyTargetPoint()->shouldReturn($targetPoint);
    }

    public function it_throws_exception_on_returning_journey_return_point_not_being_in_journey(): void
    {
        $this->shouldThrow(FleetNotInJourneyYetException::class)->during('getJourneyReturnPoint', []);
    }

    public function it_returns_journey_return_point(
        Journey $journey,
        GalaxyPointInterface $returnPoint,
    ): void {
        $this->startJourney($journey);

        $journey->didReachTargetPoint()->willReturn(false);
        $journey->didReachReturnPoint()->willReturn(false);
        $journey->getReturnPoint()->willReturn($returnPoint);

        $this->getJourneyReturnPoint()->shouldReturn($returnPoint);
    }

    public function it_didnt_reach_journey_target_point_when_journey_is_null(): void
    {
        $this->didReachJourneyTargetPoint()->shouldReturn(false);
    }

    public function it_didnt_reach_journey_target_point_when_journey_tells_that(
        Journey $journey,
    ): void {
        $this->startJourney($journey);

        $journey->didReachTargetPoint()->willReturn(false);

        $this->didReachJourneyTargetPoint()->shouldReturn(false);
    }

    public function it_did_reach_journey_target_point_when_journey_tells_that(
        Journey $journey,
    ): void {
        $this->startJourney($journey);

        $journey->didReachTargetPoint()->willReturn(true);

        $this->didReachJourneyTargetPoint()->shouldReturn(true);
    }

    public function it_throws_exception_trying_to_reach_journey_target_point_when_fleet_is_not_in_journey_yet(): void
    {
        $this->shouldThrow(FleetNotInJourneyYetException::class)->during('tryToReachJourneyTargetPoint');
    }

    public function it_throws_exception_trying_to_reach_journey_target_point_when_fleet_has_finished_the_journey(
        Journey $journey,
    ): void {
        $this->startJourney($journey);

        $journey->didReachReturnPoint()->willReturn(true);

        $this->shouldThrow(FleetNotInJourneyYetException::class)->during('tryToReachJourneyTargetPoint');
    }

    public function it_returns_early_when_trying_to_reach_journey_target_point_but_not_reached_it_yet(
        Journey $journey,
    ): void {
        $this->startJourney($journey);

        $journey->didReachTargetPoint()->willReturn(false);
        $journey->didReachReturnPoint()->willReturn(false);

        $this->tryToReachJourneyTargetPoint();
    }

    public function it_reaches_journey_target_point_and_station_there(
        Journey $journey,
        GalaxyPointInterface $journeyTargetPoint,
    ): void {
        $this->startJourney($journey);

        $journey->didReachReturnPoint()->willReturn(false);
        $journey->didReachTargetPoint()->willReturn(true);
        $journey->doesPlanToStationOnTarget()->willReturn(true);
        $journey->getTargetPoint()->willReturn($journeyTargetPoint);

        $journey->reachTargetPoint()->shouldBeCalledOnce();

        $this->tryToReachJourneyTargetPoint();
        $this->getStationingGalaxyPoint()->shouldReturn($journeyTargetPoint);
    }

    public function it_does_nothing_when_trying_to_reach_journey_target_point_but_currently_flies_back(
        Journey $journey,
        GalaxyPointInterface $journeyTargetPoint,
    ): void {
        $this->startJourney($journey);

        $journey->didReachReturnPoint()->willReturn(false);
        $journey->didReachTargetPoint()->willReturn(true);
        $journey->doesPlanToStationOnTarget()->willReturn(false);
        $journey->getTargetPoint()->willReturn($journeyTargetPoint);
        $journey->doesFlyBack()->willReturn(true);

        $this->tryToReachJourneyTargetPoint();
    }

    public function it_tries_to_reach_journey_target_point_but_doesnt_fly_back_yet(
        Journey $journey,
    ): void {
        $this->startJourney($journey);

        $journey->didReachReturnPoint()->willReturn(false);
        $journey->didReachTargetPoint()->willReturn(true);
        $journey->doesPlanToStationOnTarget()->willReturn(false);
        $journey->doesFlyBack()->willReturn(false);

        $journey->reachTargetPoint();

        $this->tryToReachJourneyTargetPoint();
    }

    public function it_throws_exception_trying_to_reach_journey_return_point_when_fleet_is_not_in_the_journey_yet(): void
    {
        $this->shouldThrow(FleetNotInJourneyYetException::class)->during('tryToReachJourneyReturnPoint');
    }

    public function it_throws_exception_trying_to_reach_journey_return_point_when_fleet_did_not_reach_target_point_yet(
        Journey $journey,
    ): void {
        $this->startJourney($journey);

        $journey->didReachTargetPoint()->willReturn(false);

        $this->shouldThrow(FleetHasNotYetReachedTheTargetPointException::class)->during('tryToReachJourneyReturnPoint');
    }

    public function it_returns_early_when_trying_to_reach_journey_return_point_but_not_reached_it_yet(
        Journey $journey,
    ): void {
        $this->startJourney($journey);

        $journey->didReachTargetPoint()->willReturn(true);
        $journey->didReachReturnPoint()->willReturn(false);

        $this->tryToReachJourneyReturnPoint();
    }

    public function it_didnt_return_from_journey_when_journey_is_null(): void
    {
        $this->didReturnFromJourney()->shouldReturn(false);
    }

    public function it_didnt_return_from_journey_when_journey_says_that(
        Journey $journey,
    ): void {
        $this->startJourney($journey);

        $journey->didReachReturnPoint()->willReturn(false);

        $this->didReturnFromJourney()->shouldReturn(false);
    }

    public function it_did_return_from_journey_when_journey_says_that(
        Journey $journey,
    ): void {
        $this->startJourney($journey);

        $journey->didReachReturnPoint()->willReturn(true);

        $this->didReturnFromJourney()->shouldReturn(true);
    }

    public function it_does_no_flyback_when_journey_is_null(): void
    {
        $this->doesFlyBack()->shouldReturn(false);
    }

    public function it_does_no_flyback_when_journey_tells_that(
        Journey $journey,
    ): void {
        $this->startJourney($journey);

        $journey->doesFlyBack()->willReturn(false);

        $this->doesFlyBack()->shouldReturn(false);
    }

    public function it_does_the_flyback_when_journey_tells_that(
        Journey $journey,
    ): void {
        $this->startJourney($journey);

        $journey->doesFlyBack()->willReturn(true);

        $this->doesFlyBack()->shouldReturn(true);
    }

    public function it_throws_exception_on_cancelling_journey_which_is_null(): void
    {
        $this->shouldThrow()->during('cancelJourney', []);
    }

    public function it_throws_exception_on_cancelling_journey_when_it_already_reached_target_point(
        Journey $journey,
    ): void {
        $this->startJourney($journey);
        $journey->didReachTargetPoint()->willReturn(true);

        $this->shouldThrow()->during('cancelJourney', []);
    }

    public function it_cancels_journey(
        Journey $journey,
    ): void {
        $this->startJourney($journey);
        $journey->didReachTargetPoint()->willReturn(false);
        $journey->cancel()->shouldBeCalledOnce();

        $this->cancelJourney();
    }

    public function it_returns_load_capacity(
        ShipsGroupInterface $lightFighterShipsGroup,
        ShipsGroupInterface $warshipShipsGroup,
    ): void {
        $this->initialize([
            $lightFighterShipsGroup->getWrappedObject(),
            $warshipShipsGroup->getWrappedObject(),
        ]);

        $lightFighterShipsGroup->getLoadCapacity()->willReturn(500);
        $warshipShipsGroup->getLoadCapacity()->willReturn(7500);

        $this->getLoadCapacity()->shouldReturn(8000);
    }

    public function it_returns_zero_load_capacity_when_has_no_ships(): void
    {
        $this->getLoadCapacity()->shouldReturn(0);
    }

    public function it_returns_resource_load_as_scalar_array(): void
    {
        $this->getResourcesLoad()->shouldReturn([
            "7dbe6a5c-e12c-4325-a38a-f2165873c263" => 500,
            "e2a1295c-9390-47b9-99c6-dd5f0798954d" => 350,
        ]);
    }

    public function it_throws_exception_when_loading_resources_but_there_is_no_enough_capacity(
        ResourcesInterface $newLoad,
        ShipsGroupInterface $lightFighterShipsGroup,
    ): void {
        $this->initialize([
            $lightFighterShipsGroup->getWrappedObject(),
        ]);
        $newLoad->sum()->willReturn(500);

        $lightFighterShipsGroup->getLoadCapacity()->willReturn(300);

        $this->shouldThrow(NotEnoughFleetLoadCapacityException::class)
            ->during('load', [
                $newLoad,
            ]);
    }

    public function it_throws_exception_when_loading_resources_on_already_loaded_fleet(
        ResourcesInterface $newLoad,
        ShipsGroupInterface $lightFighterShipsGroup,
    ): void {
        $this->initialize([
            $lightFighterShipsGroup->getWrappedObject(),
        ]);
        $newLoad->sum()->willReturn(500);

        $lightFighterShipsGroup->getLoadCapacity()->willReturn(1000);

        $this->shouldThrow(FleetAlreadyLoadedException::class)
            ->during('load', [
                $newLoad,
            ]);
    }

    public function it_loads_resources(
        ResourcesInterface $resourcesLoad,
        ShipsGroupInterface $lightFighterShipsGroup,
    ): void {
        $this->initialize([
            $lightFighterShipsGroup->getWrappedObject(),
        ]);
        $this->unload();

        $lightFighterShipsGroup->getLoadCapacity()->willReturn(500);
        $resourcesLoad->sum()->willReturn(500);

        $this->load($resourcesLoad);
    }

    public function it_returns_unloaded_resources(): void
    {
        $load = $this->unload();

        $load->getAmount(
            new ResourceId("7dbe6a5c-e12c-4325-a38a-f2165873c263")
        )->shouldReturn(500);
        $load->getAmount(
            new ResourceId("e2a1295c-9390-47b9-99c6-dd5f0798954d")
        )->shouldReturn(350);
    }

    public function it_clears_on_unload(): void
    {
        $this->unload();

        $this->getResourcesLoad()->shouldReturn([]);
    }
}
