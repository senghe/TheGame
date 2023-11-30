<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceStorage\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasStartedJourneyEvent;
use TheGame\Application\Component\ResourceStorage\Command\UseResourceCommand;
use TheGame\Application\SharedKernel\CommandBusInterface;

final class TakeResourcesOnStartingJourneyEventListenerSpec extends ObjectBehavior
{
    public function it_takes_resources_load_and_fuel_from_the_storage(
        CommandBusInterface $commandBus,
    ): void {
        $this->beConstructedWith($commandBus);

        $planetId = "c64b2d71-b70c-428e-9395-ce7ffbf74945";
        $fleetId = "dc14be47-7c6a-4530-9327-1a6b3467ae23";
        $fromGalaxyPoint = "[1:2:3]";
        $toGalaxyPoint = "[2:3:4]";
        $fuelRequirements = [
            "00fb6403-ad85-4361-b4fe-785ba3075172" => 350,
        ];
        $resourcesLoad = [
            "2f374b4e-6502-4fd2-addd-42c162d2b826" => 500,
            "00fb6403-ad85-4361-b4fe-785ba3075172" => 250,
        ];

        $commandBus->dispatch(Argument::type(UseResourceCommand::class))
            ->shouldBeCalledTimes(2);

        $event = new FleetHasStartedJourneyEvent(
            $planetId, $fleetId, $fromGalaxyPoint, $toGalaxyPoint, $fuelRequirements, $resourcesLoad,
        );
        $this->__invoke($event);
    }
}
