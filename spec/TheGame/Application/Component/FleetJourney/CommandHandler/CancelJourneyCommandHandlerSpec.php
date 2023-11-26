<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\CommandHandler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\FleetJourney\Command\CancelJourneyCommand;
use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasCancelledJourneyEvent;
use TheGame\Application\Component\FleetJourney\Domain\FleetId;
use TheGame\Application\Component\FleetJourney\FleetRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;
use TheGame\Application\SharedKernel\EventBusInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class CancelJourneyCommandHandlerSpec extends ObjectBehavior
{
    public function let(
        FleetRepositoryInterface $fleetRepository,
        EventBusInterface $eventBus,
    ): void {
        $this->beConstructedWith($fleetRepository, $eventBus);
    }

    public function it_cancels_journey(
        FleetRepositoryInterface $fleetRepository,
        EventBusInterface $eventBus,
        Fleet $fleet,
        GalaxyPointInterface $fleetTargetPoint,
    ): void {
        $fleetId = "f164e51d-2398-44fb-9c9d-7744681de1fe";
        $fleetRepository->find(new FleetId($fleetId))->willReturn($fleet);

        $fleet->cancelJourney()->shouldBeCalledOnce();

        $fleet->getJourneyTargetPoint()->willReturn($fleetTargetPoint);
        $fleetTargetPoint->format()->willReturn("[1:2:3]");

        $eventBus->dispatch(Argument::type(FleetHasCancelledJourneyEvent::class))
            ->shouldBeCalledOnce();
        $fleet->getResourcesLoad()->willReturn([
            "3cacc092-af4a-45ca-846e-0c7060a60c43" => 500,
        ]);

        $this->__invoke(
            new CancelJourneyCommand($fleetId),
        );
    }

    public function it_throws_exception_when_fleet_has_not_been_found(
        FleetRepositoryInterface $fleetRepository,
    ): void {
        $fleetId = "f164e51d-2398-44fb-9c9d-7744681de1fe";

        $fleetRepository->find(new FleetId($fleetId))->willReturn(null);

        $this->shouldThrow(InconsistentModelException::class)
            ->during('__invoke', [
                new CancelJourneyCommand($fleetId),
            ]);
    }
}
