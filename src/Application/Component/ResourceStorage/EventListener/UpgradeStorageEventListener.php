<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\EventListener;

use TheGame\Application\Component\Balance\Bridge\ResourceStoragesContextInterface;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ResourceStorageConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\ResourceStorage\ResourceStoragesRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceId;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class UpgradeStorageEventListener
{
    public function __construct(
        private readonly ResourceStoragesRepositoryInterface $storagesRepository,
        private readonly ResourceStoragesContextInterface $resourceStoragesContext,
    ) {

    }

    public function __invoke(ResourceStorageConstructionHasBeenFinishedEvent $event): void
    {
        $planetId = new PlanetId($event->getPlanetId());
        $storages = $this->storagesRepository->findForPlanet($planetId);
        if ($storages === null) {
            throw new InconsistentModelException(sprintf("Planet %d has no storages collection attached", $event->getPlanetId()));
        }

        $resourceId = new ResourceId($event->getResourceContextId());
        $newLimit = $this->resourceStoragesContext->getLimit(
            $event->getLevel(), $resourceId
        );

        $storages->upgradeLimit(
            $resourceId, $newLimit
        );
    }
}
