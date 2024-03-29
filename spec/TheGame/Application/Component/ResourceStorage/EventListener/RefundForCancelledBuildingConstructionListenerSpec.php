<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceStorage\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenCancelledEvent;
use TheGame\Application\Component\ResourceStorage\Command\DispatchResourcesCommand;
use TheGame\Application\SharedKernel\CommandBusInterface;
use TheGame\Application\SharedKernel\Domain\BuildingType;

final class RefundForCancelledBuildingConstructionListenerSpec extends ObjectBehavior
{
    public function let(CommandBusInterface $commandBus): void
    {
        $this->beConstructedWith($commandBus);
    }

    public function it_creates_command_for_each_resource_requirement(
        CommandBusInterface $commandBus,
    ): void {
        $planetId = "DF3A7B29-6D32-4EBC-AC9D-7FD3939A6E47";
        $buildingType = BuildingType::ResourceStorage->value;
        $buildingId = "E4A23622-6C77-402D-A442-47124E19D190";
        $cancelledLevel = 50;

        $commandBus->dispatch(Argument::type(DispatchResourcesCommand::class))
            ->shouldBeCalled(2);

        $resourceRequirements = [
            "A606A1AA-CA42-4771-A641-312028ED415D" => 500,
            "8F474C2C-3AD0-4E27-A6EE-AF09151852C5" => 250,
        ];
        $event = new BuildingConstructionHasBeenCancelledEvent(
            $planetId,
            $buildingType,
            $buildingId,
            $cancelledLevel,
            $resourceRequirements
        );
        $this->__invoke($event);
    }
}
