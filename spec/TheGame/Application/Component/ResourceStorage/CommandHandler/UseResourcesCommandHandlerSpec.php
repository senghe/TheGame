<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceStorage\CommandHandler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\ResourceStorage\Command\UseResourcesCommand;
use TheGame\Application\Component\ResourceStorage\Domain\Entity\StoragesCollection;
use TheGame\Application\Component\ResourceStorage\Domain\Event\StorageAmountHasChangedEvent;
use TheGame\Application\Component\ResourceStorage\Domain\Exception\CannotUseUnsupportedResourceException;
use TheGame\Application\Component\ResourceStorage\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\ResourceStorage\ResourceStoragesRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;
use TheGame\Application\SharedKernel\Domain\ResourceId;
use TheGame\Application\SharedKernel\EventBusInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class UseResourcesCommandHandlerSpec extends ObjectBehavior
{
    public function let(
        ResourceStoragesRepositoryInterface $storagesRepository,
        EventBusInterface $eventBus,
    ): void {
        $this->beConstructedWith($storagesRepository, $eventBus);
    }

    public function it_uses_supported_resources(
        ResourceStoragesRepositoryInterface $storagesRepository,
        EventBusInterface $eventBus,
        StoragesCollection $storagesCollection,
    ): void {
        $planetId = "c584286e-08e6-4875-990b-3af569f74eee";
        $resourceId = "aac85d88-7f05-4ca9-85a4-c1d0dbc71f6a";
        $amount = 10;

        $storagesRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn($storagesCollection);

        $resourceAmount = new ResourceAmount(new ResourceId($resourceId), $amount);

        $storagesCollection->supports($resourceAmount)
            ->willReturn(true);

        $storagesCollection->hasEnough($resourceAmount)
            ->willReturn(true);

        $storagesCollection->use($resourceAmount)->shouldBeCalledOnce();

        $eventBus->dispatch(Argument::type(StorageAmountHasChangedEvent::class))->shouldBeCalledOnce();

        $command = new UseResourcesCommand(
            $planetId,
            $resourceId,
            $amount
        );
        $this->__invoke($command);
    }

    public function it_throws_exception_when_using_unsupported_resources(
        ResourceStoragesRepositoryInterface $storagesRepository,
        EventBusInterface $eventBus,
        StoragesCollection $storagesCollection,
    ): void {
        $planetId = "c584286e-08e6-4875-990b-3af569f74eee";
        $resourceId = "aac85d88-7f05-4ca9-85a4-c1d0dbc71f6a";
        $amount = 10;

        $storagesRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn($storagesCollection);

        $resourceAmount = new ResourceAmount(new ResourceId($resourceId), $amount);

        $storagesCollection->supports($resourceAmount)
            ->willReturn(false);

        $command = new UseResourcesCommand(
            $planetId,
            $resourceId,
            $amount
        );
        $this->shouldThrow(CannotUseUnsupportedResourceException::class)
            ->during('__invoke', [$command]);
    }

    public function it_throws_exception_when_has_not_enough_resources(
        ResourceStoragesRepositoryInterface $storagesRepository,
        EventBusInterface $eventBus,
        StoragesCollection $storagesCollection,
    ): void {
        $planetId = "c584286e-08e6-4875-990b-3af569f74eee";
        $resourceId = "aac85d88-7f05-4ca9-85a4-c1d0dbc71f6a";
        $amount = 10;

        $storagesRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn($storagesCollection);

        $resourceAmount = new ResourceAmount(new ResourceId($resourceId), $amount);

        $storagesCollection->supports($resourceAmount)
            ->willReturn(true);

        $storagesCollection->hasEnough($resourceAmount)
            ->willReturn(false);

        $command = new UseResourcesCommand(
            $planetId,
            $resourceId,
            $amount
        );
        $this->shouldThrow(InsufficientResourcesException::class)
            ->during('__invoke', [$command]);
    }

    public function it_throws_exception_when_planet_has_no_storages_attached(
        ResourceStoragesRepositoryInterface $storagesRepository,
    ): void {
        $planetId = "c584286e-08e6-4875-990b-3af569f74eee";
        $resourceId = "aac85d88-7f05-4ca9-85a4-c1d0dbc71f6a";
        $amount = 10;

        $storagesRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn(null);

        $command = new UseResourcesCommand(
            $planetId,
            $resourceId,
            $amount
        );
        $this->shouldThrow(InconsistentModelException::class)
            ->during('__invoke', [$command]);
    }
}
