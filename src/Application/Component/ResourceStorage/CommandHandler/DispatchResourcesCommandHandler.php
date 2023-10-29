<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\CommandHandler;

use TheGame\Application\Component\ResourceStorage\Command\DispatchResourcesCommand;
use TheGame\Application\Component\ResourceStorage\Domain\Event\ResourcesHaveBeenDispatchedEvent;
use TheGame\Application\Component\ResourceStorage\Domain\Event\StorageAmountHasChangedEvent;
use TheGame\Application\Component\ResourceStorage\Domain\Factory\StorageFactory;
use TheGame\Application\Component\ResourceStorage\ResourceStoragesRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;
use TheGame\Application\SharedKernel\Domain\ResourceId;
use TheGame\Application\SharedKernel\EventBusInterface;

final class DispatchResourcesCommandHandler
{
    public function __construct(
        private readonly ResourceStoragesRepositoryInterface $storagesRepository,
        private readonly StorageFactory $storageFactory,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(DispatchResourcesCommand $command): void
    {
        $planetId = new PlanetId($command->planetId);
        $storages = $this->storagesRepository->findForPlanet($planetId);

        $resourceId = new ResourceId($command->resourceId);
        $resourceAmount = new ResourceAmount($resourceId, $command->amount);

        if ($storages->supports($resourceAmount) === false) {
            $storage = $this->storageFactory->createNew($resourceId);
            $storages->add($storage);
        }
        $storages->dispatch($resourceAmount);

        $event = new ResourcesHaveBeenDispatchedEvent(
            $command->planetId,
            $command->resourceId,
            $command->amount,
        );
        $this->eventBus->dispatch($event);

        $event = new StorageAmountHasChangedEvent(
            $command->planetId,
            $command->resourceId,
        );
        $this->eventBus->dispatch($event);
    }
}
