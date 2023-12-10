<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\Domain\Entity;

use DateTime;
use DateTimeImmutable;
use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\FleetJourney\Domain\Exception\CannotCancelFleetJourneyOnFlyBackException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\CannotCancelFleetJourneyOnReachingTargetPointException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetHasNotYetReachedTheTargetPointException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetNotOnFlyBackException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetOnFlyBackException;
use TheGame\Application\Component\FleetJourney\Domain\FleetId;
use TheGame\Application\Component\FleetJourney\Domain\JourneyId;
use TheGame\Application\SharedKernel\Domain\GalaxyPoint;
use TheGame\Application\SharedKernel\Domain\FleetMissionType;

final class JourneySpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->initialize(FleetMissionType::Transport, 50);
    }

    private function initialize(FleetMissionType $missionType, int $duration): void
    {
        $journeyId = "35f6e0a6-e9bb-4344-b1f1-299cbaeb1f25";
        $fleetId = "282294b8-ba92-46d1-b86a-4768e2a664b9";
        $startPoint = new GalaxyPoint(1, 2, 3);
        $targetPoint = new GalaxyPoint(4, 5, 6);

        $this->beConstructedWith(
            new JourneyId($journeyId),
            new FleetId($fleetId),
            $missionType,
            $startPoint,
            $targetPoint,
            $duration,
        );
    }

    public function it_has_identifier(): void
    {
        $this->getId()->getUuid()->shouldReturn("35f6e0a6-e9bb-4344-b1f1-299cbaeb1f25");
    }

    public function it_has_mission_type(): void
    {
        $this->getMissionType()->shouldReturn(FleetMissionType::Transport);
    }

    public function it_has_start_point(): void
    {
        $this->getStartPoint()->format()->shouldReturn("[1:2:3]");
    }

    public function it_has_target_point(): void
    {
        $this->getTargetPoint()->format()->shouldReturn("[4:5:6]");
    }

    public function it_has_return_point(): void
    {
        $this->getReturnPoint()->format()->shouldReturn("[1:2:3]");
    }

    public function it_remembers_when_started_mission(): void
    {
        $now = new DateTime();
        $this->getStartedAt()->getTimestamp()->shouldReturn($now->getTimestamp());
    }

    public function it_knows_when_planned_to_reach_the_target_point(): void
    {
        $now = new DateTime();
        $this->getReachesTargetAt()->getTimestamp()->shouldReturn($now->getTimestamp() + 50);
    }

    public function it_knows_the_default_value_of_real_time_for_reaching_the_target_point(): void
    {
        $now = new DateTime();
        $this->getPlannedReachTargetAt()->getTimestamp()->shouldReturn($now->getTimestamp() + 50);
    }

    public function it_knows_when_planned_to_reach_the_return_point(): void
    {
        $now = new DateTime();
        $this->getPlannedReturnAt()->getTimestamp()->shouldReturn($now->getTimestamp() + 100);
    }

    public function it_knows_the_default_value_of_real_time_for_reaching_the_return_point(): void
    {
        $now = new DateTime();
        $this->getReturnsAt()->getTimestamp()->shouldReturn($now->getTimestamp() + 100);
    }

    public function it_does_plan_to_station_on_the_target_point(): void
    {
        $this->initialize(FleetMissionType::Stationing, 50);

        $this->doesPlanToStationOnTarget()->shouldReturn(true);
    }

    public function it_doesnt_plan_to_station_on_the_target_point(): void
    {
        $this->doesPlanToStationOnTarget()->shouldReturn(false);
    }

    public function it_does_attack(): void
    {
        $this->initialize(FleetMissionType::Attack, 50);

        $this->doesAttack()->shouldReturn(true);
    }

    public function it_doesnt_attach(): void
    {
        $this->doesAttack()->shouldReturn(false);
    }

    public function it_does_transport_resources(): void
    {
        $this->doesTransportResources()->shouldReturn(true);
    }

    public function it_doesnt_transport_resources(): void
    {
        $this->initialize(FleetMissionType::Attack, 50);

        $this->doesTransportResources()->shouldReturn(false);
    }

    public function it_doesnt_do_flyback_by_default(): void
    {
        $this->doesFlyBack()->shouldReturn(false);
    }

    public function it_does_flyback_after_reaching_target_point(): void
    {
        $this->initialize(FleetMissionType::Transport, 0);

        $this->reachTargetPoint();
        $this->doesFlyBack()->shouldReturn(true);
    }

    public function it_does_not_flyback_after_reaching_return_point(): void
    {
        $this->initialize(FleetMissionType::Transport, 0);

        $this->reachTargetPoint();
        $this->reachReturnPoint();
        $this->doesFlyBack()->shouldReturn(false);
    }

    public function it_does_not_flyback_after_cancelling_journey(): void
    {
        $this->cancel();
        $this->doesFlyBack()->shouldReturn(true);
    }

    public function it_did_reach_target_point(): void
    {
        $this->initialize(FleetMissionType::Transport, 0);

        $this->didReachTargetPoint()->shouldReturn(true);
    }

    public function it_didnt_reach_target_point_yet(): void
    {
        $this->didReachTargetPoint()->shouldReturn(false);
    }

    public function it_throws_exception_when_cant_reach_target_point_on_flyback(): void
    {
        $this->initialize(FleetMissionType::Transport, 0);
        $this->reachTargetPoint();

        $this->shouldThrow(FleetOnFlyBackException::class)
            ->during('reachTargetPoint', []);
    }

    public function it_throws_exception_when_reaching_target_point_but_flying_time_didnt_pass(): void
    {
        $this->shouldThrow(FleetHasNotYetReachedTheTargetPointException::class)
            ->during('reachTargetPoint', []);
    }

    public function it_reaches_target_point_and_station_on_planet(): void
    {
        $this->initialize(FleetMissionType::Stationing, 0);
        $this->reachTargetPoint();

        $now = new DateTimeImmutable();
        $this->getReturnsAt()->getTimestamp()->shouldReturn($now->getTimestamp());
        $this->doesFlyBack()->shouldReturn(false);
    }

    public function it_reaches_target_point_and_turns_around(): void
    {
        $this->initialize(FleetMissionType::Transport, 0);
        $this->reachTargetPoint();

        $this->doesFlyBack()->shouldReturn(true);
    }

    public function it_did_reach_return_point(): void
    {
        $this->initialize(FleetMissionType::Transport, 0);

        $this->didReachReturnPoint()->shouldReturn(true);
    }

    public function it_didnt_reach_return_point_yet(): void
    {
        $this->didReachReturnPoint()->shouldReturn(false);
    }

    public function it_throws_exception_when_reaching_return_point_not_being_on_flyback(): void
    {
        $this->shouldThrow(FleetNotOnFlyBackException::class)
            ->during('reachReturnPoint', []);
    }

    public function it_throws_exception_when_reaching_return_point_but_flying_time_didnt_pass(): void
    {
        throw new SkippingException("Cannot test the behaviour with current implementation");
    }

    public function it_reaches_return_point(): void
    {
        throw new SkippingException("Cannot test the behaviour with current implementation");
    }

    public function it_is_cancelled(): void
    {
        $this->cancel();

        $this->isCancelled()->shouldReturn(true);
    }

    public function it_is_not_cancelled(): void
    {
        $this->isCancelled()->shouldReturn(false);
    }

    public function it_throws_exception_when_cancelling_fleet_on_flyback(): void
    {
        $this->initialize(FleetMissionType::Transport, 0);
        $this->reachTargetPoint();

        $this->shouldThrow(CannotCancelFleetJourneyOnFlyBackException::class)
            ->during('cancel', []);
    }

    public function it_throws_exception_when_cancelling_fleet_which_did_reach_target_point(): void
    {
        $this->initialize(FleetMissionType::Transport, 0);

        $this->shouldThrow(CannotCancelFleetJourneyOnReachingTargetPointException::class)
            ->during('cancel', []);
    }

    public function it_throws_exception_when_cancelling_fleet_which_did_reach_return_point(): void
    {
        throw new SkippingException("Cannot test the behaviour with current implementation");
    }

    public function it_cancels_journey_and_turns_around(): void
    {
        $this->cancel();

        $this->doesFlyBack()->shouldReturn(true);
    }
}
