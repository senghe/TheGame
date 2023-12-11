<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceStorage\CommandHandler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\ResourceStorage\Command\DispatchResourcesCommand;
use TheGame\Application\Component\ResourceStorage\Domain\Entity\Storage;
use TheGame\Application\Component\ResourceStorage\Domain\Entity\StoragesCollection;
use TheGame\Application\Component\ResourceStorage\Domain\Event\ResourcesHaveBeenDispatchedEvent;
use TheGame\Application\Component\ResourceStorage\Domain\Event\StorageAmountHasChangedEvent;
use TheGame\Application\Component\ResourceStorage\Domain\Factory\StorageFactoryInterface;
use TheGame\Application\Component\ResourceStorage\ResourceStoragesRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetId;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceId;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;
use TheGame\Application\SharedKernel\EventBusInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class DispatchResourcesCommandHandlerSpec extends ObjectBehavior
{
    public function let(
        ResourceStoragesRepositoryInterface $storagesRepository,
        StorageFactoryInterface $storageFactory,
        EventBusInterface $eventBus,
    ): void {
        $this->beConstructedWith($storagesRepository, $storageFactory, $eventBus);
    }

    public function it_dispatches_supported_resource(
        ResourceStoragesRepositoryInterface $storagesRepository,
        StorageFactoryInterface $storageFactory,
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

        $storagesCollection->dispatch($resourceAmount)
            ->shouldBeCalledOnce();

        $eventBus->dispatch(Argument::type(ResourcesHaveBeenDispatchedEvent::class))
            ->shouldBeCalledOnce();

        $eventBus->dispatch(Argument::type(StorageAmountHasChangedEvent::class))
            ->shouldBeCalledOnce();

        $command = new DispatchResourcesCommand(
            $planetId,
            $resourceId,
            $amount
        );
        $this->__invoke($command);
    }

    public function it_adds_storage_when_resource_is_not_supported(
        ResourceStoragesRepositoryInterface $storagesRepository,
        StorageFactoryInterface $storageFactory,
        Storage $storage,
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

        $storageFactory->createNew(new ResourceId($resourceId))
            ->willReturn($storage);

        $storagesCollection->add($storage)->shouldBeCalledOnce();

        $storagesCollection->dispatch($resourceAmount)
            ->shouldBeCalledOnce();

        $command = new DispatchResourcesCommand(
            $planetId,
            $resourceId,
            $amount
        );
        $this->__invoke($command);
    }

    public function it_throws_exception_when_planet_has_no_storages_attached(
        ResourceStoragesRepositoryInterface $storagesRepository,
    ): void {
        $planetId = "c584286e-08e6-4875-990b-3af569f74eee";
        $resourceId = "aac85d88-7f05-4ca9-85a4-c1d0dbc71f6a";
        $amount = 10;

        $storagesRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn(null);

        $command = new DispatchResourcesCommand(
            $planetId,
            $resourceId,
            $amount
        );
        $this->shouldThrow(InconsistentModelException::class)
            ->during('__invoke', [$command]);
    }
}
