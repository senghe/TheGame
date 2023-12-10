<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\CommandHandler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\FleetJourney\Command\ReturnJourneysCommand;
use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasReachedJourneyReturnPointEvent;
use TheGame\Application\Component\FleetJourney\Domain\FleetId;
use TheGame\Application\Component\FleetJourney\FleetRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlayerId;
use TheGame\Application\SharedKernel\Domain\GalaxyPoint;
use TheGame\Application\SharedKernel\EventBusInterface;

final class ReturnJourneysCommandHandlerSpec extends ObjectBehavior
{
    public function let(
        FleetRepositoryInterface $fleetRepository,
        EventBusInterface $eventBus,
    ): void {
        $this->beConstructedWith($fleetRepository, $eventBus);
    }

    public function it_returns_journey_of_two_fleets(
        FleetRepositoryInterface $fleetRepository,
        EventBusInterface $eventBus,
        Fleet $fleet1,
        Fleet $fleet2,
    ): void {
        $playerId = "8e807726-4a48-489e-9706-16061705bb6a";

        $fleetRepository->findFlyingBackFromJourneyForPlayer(new PlayerId($playerId))
            ->willReturn([
                $fleet1->getWrappedObject(),
                $fleet2->getWrappedObject(),
            ]);

        $fleet1->tryToReachJourneyReturnPoint()->shouldBeCalledOnce();
        $fleet1->didReturnFromJourney()->willReturn(true);
        $fleet1->getId()->willReturn(new FleetId("78131199-faa2-456f-9419-10d77ac92e13"));
        $fleet1->getJourneyStartPoint()->willReturn(new GalaxyPoint(1, 2, 3));
        $fleet1->getJourneyTargetPoint()->willReturn(new GalaxyPoint(2, 3, 4));
        $fleet1->getJourneyReturnPoint()->willReturn(new GalaxyPoint(7, 8, 9));
        $fleet1->getResourcesLoad()->willReturn([
            "8de65203-ad4c-4ce7-bced-cfeda9107b5d" => 300,
            "42bb40d7-1d8c-4a88-86db-c10ffd68570e" => 200,
        ]);

        $fleet2->tryToReachJourneyReturnPoint()->shouldBeCalledOnce();
        $fleet2->didReturnFromJourney()->willReturn(true);
        $fleet2->getId()->willReturn(new FleetId("a63c152f-86d5-4ed6-8eb0-c7beb05ee517"));
        $fleet2->getJourneyStartPoint()->willReturn(new GalaxyPoint(3, 4, 5));
        $fleet2->getJourneyTargetPoint()->willReturn(new GalaxyPoint(4, 5, 6));
        $fleet2->getJourneyReturnPoint()->willReturn(new GalaxyPoint(7, 8, 9));
        $fleet2->getResourcesLoad()->willReturn([]);

        $eventBus->dispatch(Argument::type(FleetHasReachedJourneyReturnPointEvent::class))
            ->shouldBeCalledTimes(2);

        $this->__invoke(new ReturnJourneysCommand($playerId));
    }

    public function it_skips_fleet_when_it_has_not_returned_from_journey(
        FleetRepositoryInterface $fleetRepository,
        EventBusInterface $eventBus,
        Fleet $fleet1,
        Fleet $fleet2,
    ): void {
        $playerId = "8e807726-4a48-489e-9706-16061705bb6a";

        $fleetRepository->findFlyingBackFromJourneyForPlayer(new PlayerId($playerId))
            ->willReturn([
                $fleet1->getWrappedObject(),
                $fleet2->getWrappedObject(),
            ]);

        $fleet1->tryToReachJourneyReturnPoint()->shouldBeCalledOnce();
        $fleet1->didReturnFromJourney()->willReturn(false);

        $fleet2->tryToReachJourneyReturnPoint()->shouldBeCalledOnce();
        $fleet2->didReturnFromJourney()->willReturn(true);
        $fleet2->getId()->willReturn(new FleetId("a63c152f-86d5-4ed6-8eb0-c7beb05ee517"));
        $fleet2->getJourneyStartPoint()->willReturn(new GalaxyPoint(3, 4, 5));
        $fleet2->getJourneyTargetPoint()->willReturn(new GalaxyPoint(4, 5, 6));
        $fleet2->getJourneyReturnPoint()->willReturn(new GalaxyPoint(7, 8, 9));
        $fleet2->getResourcesLoad()->willReturn([]);

        $eventBus->dispatch(Argument::type(FleetHasReachedJourneyReturnPointEvent::class))
            ->shouldBeCalledTimes(1);

        $this->__invoke(new ReturnJourneysCommand($playerId));
    }

    public function it_does_nothing_when_no_returning_fleet_found(
        FleetRepositoryInterface $fleetRepository,
        EventBusInterface $eventBus,
    ): void {
        $playerId = "8e807726-4a48-489e-9706-16061705bb6a";

        $fleetRepository->findFlyingBackFromJourneyForPlayer(new PlayerId($playerId))
            ->willReturn([]);

        $eventBus->dispatch(Argument::type(FleetHasReachedJourneyReturnPointEvent::class))
            ->shouldNotBeCalled();

        $this->__invoke(new ReturnJourneysCommand($playerId));
    }
}
