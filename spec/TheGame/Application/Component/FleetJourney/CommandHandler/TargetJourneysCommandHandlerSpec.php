<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\CommandHandler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\FleetJourney\Command\TargetJourneysCommand;
use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasReachedJourneyTargetPointEvent;
use TheGame\Application\Component\FleetJourney\Domain\FleetId;
use TheGame\Application\Component\FleetJourney\FleetRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlayerId;
use TheGame\Application\SharedKernel\Domain\GalaxyPoint;
use TheGame\Application\SharedKernel\Domain\FleetMissionType;
use TheGame\Application\SharedKernel\EventBusInterface;

final class TargetJourneysCommandHandlerSpec extends ObjectBehavior
{
    public function let(
        FleetRepositoryInterface $fleetRepository,
        EventBusInterface $eventBus,
    ): void {
        $this->beConstructedWith($fleetRepository, $eventBus);
    }

    public function it_reaches_journey_target_for_two_fleets(
        FleetRepositoryInterface $fleetRepository,
        EventBusInterface $eventBus,
        Fleet $fleet1,
        Fleet $fleet2,
    ): void {
        $playerId = "f313d56d-1a27-46fb-a1a5-1fb4b8a1e88b";

        $fleetRepository->findInJourneyForPlayer(new PlayerId($playerId))
            ->willReturn([
                $fleet1->getWrappedObject(),
                $fleet2->getWrappedObject(),
            ]);

        $fleet1->tryToReachJourneyTargetPoint()->shouldBeCalledOnce();
        $fleet1->didReachJourneyTargetPoint()->willReturn(true);
        $fleet1->getId()->willReturn(new FleetId("dfdd04c6-9243-4775-834c-ad702003ef6b"));
        $fleet1->getJourneyMissionType()->willReturn(FleetMissionType::Transport);
        $fleet1->getJourneyTargetPoint()->willReturn(new GalaxyPoint(1, 2, 3));
        $fleet1->getResourcesLoad()->willReturn([
            "8de65203-ad4c-4ce7-bced-cfeda9107b5d" => 300,
            "42bb40d7-1d8c-4a88-86db-c10ffd68570e" => 200,
        ]);

        $fleet2->tryToReachJourneyTargetPoint()->shouldBeCalledOnce();
        $fleet2->didReachJourneyTargetPoint()->willReturn(true);
        $fleet2->getId()->willReturn(new FleetId("6da596f9-f66a-4912-9603-06f538621695"));
        $fleet2->getJourneyMissionType()->willReturn(FleetMissionType::Stationing);
        $fleet2->getJourneyTargetPoint()->willReturn(new GalaxyPoint(2, 3, 4));
        $fleet2->getResourcesLoad()->willReturn([]);

        $eventBus->dispatch(Argument::type(FleetHasReachedJourneyTargetPointEvent::class))
            ->shouldBeCalledTimes(2);

        $command = new TargetJourneysCommand($playerId);
        $this->__invoke($command);
    }

    public function it_skips_one_fleet_which_didnt_reach_target_point(
        FleetRepositoryInterface $fleetRepository,
        EventBusInterface $eventBus,
        Fleet $fleet1,
        Fleet $fleet2,
    ): void {
        $playerId = "f313d56d-1a27-46fb-a1a5-1fb4b8a1e88b";

        $fleetRepository->findInJourneyForPlayer(new PlayerId($playerId))
            ->willReturn([
                $fleet1->getWrappedObject(),
                $fleet2->getWrappedObject(),
            ]);

        $fleet1->tryToReachJourneyTargetPoint()->shouldBeCalledOnce();
        $fleet1->didReachJourneyTargetPoint()->willReturn(true);
        $fleet1->getId()->willReturn(new FleetId("dfdd04c6-9243-4775-834c-ad702003ef6b"));
        $fleet1->getJourneyMissionType()->willReturn(FleetMissionType::Transport);
        $fleet1->getJourneyTargetPoint()->willReturn(new GalaxyPoint(1, 2, 3));
        $fleet1->getResourcesLoad()->willReturn([
            "8de65203-ad4c-4ce7-bced-cfeda9107b5d" => 300,
            "42bb40d7-1d8c-4a88-86db-c10ffd68570e" => 200,
        ]);

        $fleet2->tryToReachJourneyTargetPoint()->shouldBeCalledOnce();
        $fleet2->didReachJourneyTargetPoint()->willReturn(false);

        $eventBus->dispatch(Argument::type(FleetHasReachedJourneyTargetPointEvent::class))
            ->shouldBeCalledOnce();

        $command = new TargetJourneysCommand($playerId);
        $this->__invoke($command);
    }

    public function it_does_nothing_when_no_fleet_reaching_target_found(
        FleetRepositoryInterface $fleetRepository,
        EventBusInterface $eventBus,
    ): void {
        $playerId = "f313d56d-1a27-46fb-a1a5-1fb4b8a1e88b";

        $fleetRepository->findInJourneyForPlayer(new PlayerId($playerId))
            ->willReturn([]);

        $eventBus->dispatch(Argument::type(FleetHasReachedJourneyTargetPointEvent::class))
            ->shouldNotBeCalled();

        $command = new TargetJourneysCommand($playerId);
        $this->__invoke($command);
    }
}
