<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\CommandHandler;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\Shipyard\Command\FinishJobsCommand;
use TheGame\Application\Component\Shipyard\Domain\Entity\Shipyard;
use TheGame\Application\Component\Shipyard\Domain\Event\Factory\FinishedConstructionEventFactoryInterface;
use TheGame\Application\Component\Shipyard\Domain\Event\NewCannonsHaveBeenConstructedEvent;
use TheGame\Application\Component\Shipyard\Domain\Event\NewShipsHaveBeenConstructedEvent;
use TheGame\Application\Component\Shipyard\Domain\FinishedJobsSummaryEntryInterface;
use TheGame\Application\Component\Shipyard\Domain\FinishedJobsSummaryInterface;
use TheGame\Application\Component\Shipyard\Domain\ShipyardId;
use TheGame\Application\Component\Shipyard\Exception\ShipyardHasNotBeenFoundException;
use TheGame\Application\Component\Shipyard\ShipyardRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetId;
use TheGame\Application\SharedKernel\EventBusInterface;

final class FinishJobsCommandHandlerSpec extends ObjectBehavior
{
    public function let(
        ShipyardRepositoryInterface $shipyardRepository,
        FinishedConstructionEventFactoryInterface $finishedConstructionEventFactory,
        EventBusInterface $eventBus,
    ): void {
        $this->beConstructedWith(
            $shipyardRepository,
            $finishedConstructionEventFactory,
            $eventBus,
        );
    }

    public function it_throws_exception_when_shipyard_has_not_been_found(
        ShipyardRepositoryInterface $shipyardRepository,
    ): void {
        $shipyardId = "3E303BDF-976A-4509-8611-A30D33781085";

        $shipyardRepository->find(new ShipyardId($shipyardId))
            ->willReturn(null);

        $command = new FinishJobsCommand($shipyardId);
        $this->shouldThrow(ShipyardHasNotBeenFoundException::class)
            ->during('__invoke', [$command]);
    }

    public function it_finishes_currently_done_jobs(
        ShipyardRepositoryInterface $shipyardRepository,
        FinishedConstructionEventFactoryInterface $finishedConstructionEventFactory,
        EventBusInterface $eventBus,
        Shipyard $shipyard,
        FinishedJobsSummaryInterface $summary,
        FinishedJobsSummaryEntryInterface $summaryEntry1,
        FinishedJobsSummaryEntryInterface $summaryEntry2,
    ): void {
        $shipyardId = "3E303BDF-976A-4509-8611-A30D33781085";

        $shipyardRepository->find(new ShipyardId($shipyardId))
            ->willReturn($shipyard);

        $shipyardPlanetId = new PlanetId("fab9bf85-9eb7-49d6-9e97-0f1e1c0cef44");
        $shipyard->getPlanetId()->willReturn($shipyardPlanetId);
        $shipyard->finishJobs()->willReturn($summary);

        $summary->getSummary()->willReturn([
            $summaryEntry1->getWrappedObject(),
            $summaryEntry2->getWrappedObject(),
        ]);

        $event1 = new NewCannonsHaveBeenConstructedEvent($shipyardPlanetId->getUuid(), 'laser', 12);
        $finishedConstructionEventFactory->createEvent($summaryEntry1, $shipyardPlanetId)
            ->willReturn($event1);

        $event2 = new NewShipsHaveBeenConstructedEvent($shipyardPlanetId->getUuid(), 'light-fighter', 5);
        $finishedConstructionEventFactory->createEvent($summaryEntry2, $shipyardPlanetId)
            ->willReturn($event2);

        $eventBus->dispatch($event1)->shouldBeCalledOnce();
        $eventBus->dispatch($event2)->shouldBeCalledOnce();

        $command = new FinishJobsCommand($shipyardId);
        $this->__invoke($command);
    }
}
