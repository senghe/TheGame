<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceStorage\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenStartedEvent;
use TheGame\Application\Component\ResourceStorage\Command\UseResourceCommand;
use TheGame\Application\SharedKernel\CommandBusInterface;
use TheGame\Application\SharedKernel\Domain\BuildingType;

final class PayForConstructedBuildingListenerSpec extends ObjectBehavior
{
    public function let(
        CommandBusInterface $commandBus
    ): void {
        $this->beConstructedWith($commandBus);
    }

    public function it_creates_command_for_each_resource_requirement(
        CommandBusInterface $commandBus
    ): void {
        $planetId = "DF3A7B29-6D32-4EBC-AC9D-7FD3939A6E47";
        $buildingType = BuildingType::ResourceStorage->value;
        $buildingId = "452D73D7-DE6E-4275-B282-0E9A622A3E59";
        $newLevel = 10;

        $commandBus->dispatch(Argument::type(UseResourceCommand::class))
            ->shouldBeCalled(2);

        $resourceRequirements = [
            "A606A1AA-CA42-4771-A641-312028ED415D" => 500,
            "8F474C2C-3AD0-4E27-A6EE-AF09151852C5" => 250,
        ];
        $event = new BuildingConstructionHasBeenStartedEvent(
            $planetId,
            $buildingType,
            $buildingId,
            $newLevel,
            $resourceRequirements
        );
        $this->__invoke($event);
    }
}
