<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceStorage\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasReachedJourneyReturnPointEvent;
use TheGame\Application\Component\Galaxy\Bridge\NavigatorInterface;
use TheGame\Application\Component\ResourceStorage\Command\DispatchResourcesCommand;
use TheGame\Application\SharedKernel\CommandBusInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class UnloadResourcesAfterReachingJourneyReturnPointEventListenerSpec extends ObjectBehavior
{
    public function let(
        NavigatorInterface $navigator,
        CommandBusInterface $commandBus,
    ): void {
        $this->beConstructedWith(
            $navigator,
            $commandBus,
        );
    }

    public function it_dispatches_resources_from_returning_fleet_to_the_storages(
        NavigatorInterface $navigator,
        CommandBusInterface $commandBus,
        PlanetIdInterface $planetId,
    ): void {
        $startCoordinates = "[1:2:3]";
        $targetCoordinates = "[4:5:6]";
        $returnCoordinates = "[7:8:9]";
        $fleetId = "086a3343-22cb-4673-88c8-368e9e9709f3";
        $resourcesLoad = [
            "a85f9598-2a03-442b-9781-b55ed748628b" => 250,
            "d704acf0-b89a-44f8-a87d-ba9e3c7c89e3" => 300,
        ];

        $navigator->getPlanetId(Argument::type(GalaxyPointInterface::class))
            ->willReturn($planetId);

        $planetId->getUuid()->willReturn("84a12209-d808-447d-8f97-2559ce60eb3e");

        $commandBus->dispatch(Argument::type(DispatchResourcesCommand::class))
            ->shouldBeCalledTimes(2);

        $event = new FleetHasReachedJourneyReturnPointEvent(
            $fleetId,
            $startCoordinates,
            $targetCoordinates,
            $returnCoordinates,
            $resourcesLoad
        );
        $this->__invoke($event);
    }

    public function it_throws_exception_when_dispatching_resources_on_non_existing_planet(
        NavigatorInterface $navigator,
    ): void {
        $startCoordinates = "[1:2:3]";
        $targetCoordinates = "[4:5:6]";
        $returnCoordinates = "[7:8:9]";
        $fleetId = "086a3343-22cb-4673-88c8-368e9e9709f3";
        $resourcesLoad = [
            "a85f9598-2a03-442b-9781-b55ed748628b" => 250,
        ];

        $navigator->getPlanetId(Argument::type(GalaxyPointInterface::class))
            ->willReturn(null);

        $event = new FleetHasReachedJourneyReturnPointEvent(
            $fleetId,
            $startCoordinates,
            $targetCoordinates,
            $returnCoordinates,
            $resourcesLoad
        );
        $this->shouldThrow(InconsistentModelException::class)->during('__invoke', [$event]);
    }
}
