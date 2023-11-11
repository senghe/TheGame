<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceStorage\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\ResourceStorage\Command\DispatchResourcesCommand;
use TheGame\Application\Component\Shipyard\Domain\Event\JobHasBeenCancelledEvent;
use TheGame\Application\SharedKernel\CommandBusInterface;

final class RefundForCancelledShipyardJobEventListenerSpec extends ObjectBehavior
{
    public function let(CommandBusInterface $commandBus): void
    {
        $this->beConstructedWith($commandBus);
    }

    public function it_creates_command_for_each_resource_requirement(
        CommandBusInterface $commandBus,
    ): void {
        $shipyardId = "7DAFA510-770E-49F1-92CA-F749B7677B60";
        $jobId = "AE45C455-83FF-4109-8C96-A3454DE0DDE6";
        $planetId = "DF3A7B29-6D32-4EBC-AC9D-7FD3939A6E47";

        $commandBus->dispatch(Argument::type(DispatchResourcesCommand::class))
            ->shouldBeCalled(2);

        $resourceRequirements = [
            "A606A1AA-CA42-4771-A641-312028ED415D" => 500,
            "8F474C2C-3AD0-4E27-A6EE-AF09151852C5" => 250,
        ];
        $event = new JobHasBeenCancelledEvent(
            $shipyardId,
            $jobId,
            $planetId,
            $resourceRequirements
        );
        $this->__invoke($event);
    }
}
