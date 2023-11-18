<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\CommandHandler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\Balance\Bridge\ShipyardContextInterface;
use TheGame\Application\Component\ResourceStorage\Bridge\ResourceAvailabilityCheckerInterface;
use TheGame\Application\Component\Shipyard\Command\ConstructShipsCommand;
use TheGame\Application\Component\Shipyard\Domain\Entity\Job;
use TheGame\Application\Component\Shipyard\Domain\Entity\Shipyard;
use TheGame\Application\Component\Shipyard\Domain\Event\NewShipsHaveBeenQueuedEvent;
use TheGame\Application\Component\Shipyard\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\Shipyard\Domain\Factory\JobFactoryInterface;
use TheGame\Application\Component\Shipyard\Domain\ShipyardId;
use TheGame\Application\Component\Shipyard\Exception\ShipyardHasNotBeenFoundException;
use TheGame\Application\Component\Shipyard\ShipyardRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;
use TheGame\Application\SharedKernel\EventBusInterface;

final class ConstructShipsCommandHandlerSpec extends ObjectBehavior
{
    public function let(
        ShipyardRepositoryInterface $shipyardRepository,
        ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        JobFactoryInterface $jobFactory,
        ShipyardContextInterface $shipyardBalanceContext,
        EventBusInterface $eventBus,
    ): void {
        $this->beConstructedWith(
            $shipyardRepository,
            $resourceAvailabilityChecker,
            $jobFactory,
            $shipyardBalanceContext,
            $eventBus,
        );
    }

    public function it_throws_exception_when_shipyard_has_not_been_found(
        ShipyardRepositoryInterface $shipyardRepository,
    ): void {
        $shipyardId = "3E303BDF-976A-4509-8611-A30D33781085";
        $shipType = 'light-fighter';
        $quantity = 500;

        $shipyardRepository->findAggregate(new ShipyardId($shipyardId))
            ->willReturn(null);

        $command = new ConstructShipsCommand($shipyardId, $shipType, $quantity);
        $this->shouldThrow(ShipyardHasNotBeenFoundException::class)
            ->during('__invoke', [$command]);
    }

    public function it_throws_exception_when_planet_hasnt_sufficient_resources(
        ShipyardRepositoryInterface          $shipyardRepository,
        Shipyard                             $shipyard,
        ShipyardContextInterface             $shipyardBalanceContext,
        JobFactoryInterface                  $jobFactory,
        Job                                  $job,
        ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        ResourcesInterface                   $singleShipResourceRequirements,
        ResourcesInterface                   $jobResourceRequirements,
    ): void {
        $shipyardId = "3E303BDF-976A-4509-8611-A30D33781085";
        $shipType = 'light-fighter';
        $quantity = 500;

        $shipyardRepository->findAggregate(new ShipyardId($shipyardId))
            ->willReturn($shipyard);

        $shipyard->getCurrentLevel()->willReturn(15);

        $shipyardBalanceContext->getShipConstructionTime($shipType, 15)->willReturn(30);
        $shipyardBalanceContext->getShipProductionLoad($shipType)->willReturn(5);
        $shipyardBalanceContext->getShipResourceRequirements($shipType)->willReturn($singleShipResourceRequirements);

        $jobFactory->createNewShipsJob(
            $shipType,
            $quantity,
            15,
            30,
            5,
            $singleShipResourceRequirements,
        )->willReturn($job);

        $job->getRequirements()->willReturn($jobResourceRequirements);

        $planetId = "D1D4E4BE-9542-47E6-94A1-EDD1B67C737E";
        $shipyard->getPlanetId()->willReturn(new PlanetId($planetId));

        $resourceAvailabilityChecker->check(
            new PlanetId($planetId),
            $jobResourceRequirements
        )->willReturn(false);

        $command = new ConstructShipsCommand($shipyardId, $shipType, $quantity);
        $this->shouldThrow(InsufficientResourcesException::class)->during('__invoke', [$command]);
    }

    public function it_queues_ships_in_shipyard(
        ShipyardRepositoryInterface          $shipyardRepository,
        Shipyard                             $shipyard,
        ShipyardContextInterface             $shipyardBalanceContext,
        JobFactoryInterface                  $jobFactory,
        Job                                  $job,
        ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        ResourcesInterface                   $singleShipResourceRequirements,
        ResourcesInterface                   $jobResourceRequirements,
        EventBusInterface                    $eventBus,
    ): void {
        $shipyardId = "3E303BDF-976A-4509-8611-A30D33781085";
        $shipType = 'light-fighter';
        $quantity = 500;

        $shipyardRepository->findAggregate(new ShipyardId($shipyardId))
            ->willReturn($shipyard);

        $shipyard->getCurrentLevel()->willReturn(15);

        $shipyardBalanceContext->getShipConstructionTime($shipType, 15)->willReturn(30);
        $shipyardBalanceContext->getShipProductionLoad($shipType)->willReturn(5);
        $shipyardBalanceContext->getShipResourceRequirements($shipType)->willReturn($singleShipResourceRequirements);

        $jobFactory->createNewShipsJob(
            $shipType,
            $quantity,
            15,
            30,
            5,
            $singleShipResourceRequirements,
        )->willReturn($job);

        $job->getRequirements()->willReturn($jobResourceRequirements);

        $planetId = "D1D4E4BE-9542-47E6-94A1-EDD1B67C737E";
        $shipyard->getPlanetId()->willReturn(new PlanetId($planetId));

        $resourceAvailabilityChecker->check(
            new PlanetId($planetId),
            $jobResourceRequirements
        )->willReturn(true);

        $jobResourceRequirements->toScalarArray()
            ->willReturn([
                "6D3CF8F4-4363-4FA2-8A25-E928B06725B7" => 540,
                "9CF70370-5AA0-46AD-88B5-D177EEDDC947" => 120,
            ]);

        $shipyard->queueJob($job)->shouldBeCalledOnce();

        $eventBus->dispatch(Argument::type(NewShipsHaveBeenQueuedEvent::class))
            ->shouldBeCalledOnce();

        $command = new ConstructShipsCommand($shipyardId, $shipType, $quantity);
        $this->__invoke($command);
    }
}
