<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\CommandHandler;

use TheGame\Application\Component\ResourceStorage\Command\DispatchResourcesCommand;
use TheGame\Application\Component\ResourceStorage\Domain\Event\ResourcesHaveBeenDispatchedEvent;
use TheGame\Application\Component\ResourceStorage\Domain\Event\StorageAmountHasChangedEvent;
use TheGame\Application\Component\ResourceStorage\Domain\Factory\StorageFactoryInterface;
use TheGame\Application\Component\ResourceStorage\ResourceStoragesRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetId;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceId;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;
use TheGame\Application\SharedKernel\EventBusInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class DispatchResourcesCommandHandler
{
    public function __construct(
        private readonly ResourceStoragesRepositoryInterface $storagesRepository,
        private readonly StorageFactoryInterface $storageFactory,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(DispatchResourcesCommand $command): void
    {
        $planetId = new PlanetId($command->getPlanetId());
        $storages = $this->storagesRepository->findForPlanet($planetId);
        if ($storages === null) {
            throw new InconsistentModelException(sprintf("Planet %d has no storages collection attached", $command->getPlanetId()));
        }

        $resourceId = new ResourceId($command->getResourceId());
        $resourceAmount = new ResourceAmount($resourceId, $command->getAmount());

        if ($storages->supports($resourceAmount) === false) {
            $storage = $this->storageFactory->createNew($resourceId);
            $storages->add($storage);
        }
        $storages->dispatch($resourceAmount);

        $event = new ResourcesHaveBeenDispatchedEvent(
            $command->getPlanetId(),
            $command->getResourceId(),
            $command->getAmount(),
        );
        $this->eventBus->dispatch($event);

        $event = new StorageAmountHasChangedEvent(
            $command->getPlanetId(),
            $command->getResourceId(),
        );
        $this->eventBus->dispatch($event);
    }
}
