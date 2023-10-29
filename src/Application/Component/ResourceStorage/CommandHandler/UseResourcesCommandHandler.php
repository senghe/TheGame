<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\CommandHandler;

use TheGame\Application\Component\ResourceStorage\Command\UseResourcesCommand;
use TheGame\Application\Component\ResourceStorage\Domain\Event\StorageAmountHasChangedEvent;
use TheGame\Application\Component\ResourceStorage\Domain\Exception\CannotUseUnsupportedResourceException;
use TheGame\Application\Component\ResourceStorage\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\ResourceStorage\ResourceStoragesRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;
use TheGame\Application\SharedKernel\Domain\ResourceId;
use TheGame\Application\SharedKernel\EventBusInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class UseResourcesCommandHandler
{
    public function __construct(
        private readonly ResourceStoragesRepositoryInterface $storagesRepository,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(UseResourcesCommand $command): void
    {
        $planetId = new PlanetId($command->getPlanetId());
        $storages = $this->storagesRepository->findForPlanet($planetId);
        if ($storages === null) {
            throw new InconsistentModelException(sprintf("Planet %d has no storages collection attached", $command->getPlanetId()));
        }

        $resourceId = new ResourceId($command->getResourceId());
        $amount = new ResourceAmount($resourceId, $command->getAmount());

        if ($storages->supports($amount) === false) {
            throw new CannotUseUnsupportedResourceException(
                $planetId,
                $amount,
            );
        }
        if ($storages->hasEnough($amount) === false) {
            throw new InsufficientResourcesException(
                $planetId,
                $amount,
            );
        }

        $storages->use($amount);

        $event = new StorageAmountHasChangedEvent(
            $command->getPlanetId(),
            $command->getResourceId(),
        );
        $this->eventBus->dispatch($event);
    }
}
