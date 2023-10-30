<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceStorage\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\ResourceMines\Domain\Event\ResourceHasBeenExtractedEvent;
use TheGame\Application\Component\ResourceStorage\Command\DispatchResourcesCommand;
use TheGame\Application\SharedKernel\CommandBusInterface;

final class DispatchResourcesExtractedByMinesEventListenerSpec extends ObjectBehavior
{
    public function let(CommandBusInterface $commandBus): void
    {
        $this->beConstructedWith($commandBus);
    }

    public function it_dispatches_command_to_command_bus(
        CommandBusInterface $commandBus
    ): void {
        $planetId = "d4c86005-b2ea-4f19-b623-3e5b8d059501";
        $resourceId = "98b3d995-ac66-4bbc-a200-a5ac765fd4d2";
        $amount = 10;

        $event = new ResourceHasBeenExtractedEvent($planetId, $resourceId, $amount);

        $commandBus->dispatch(Argument::type(DispatchResourcesCommand::class));

        $this->__invoke($event);
    }
}
