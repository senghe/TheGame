<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMines\EventListener;

use TheGame\Application\Component\Balance\Bridge\ResourceMinesContextInterface;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ResourceMineConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\ResourceMines\Domain\Factory\MineFactoryInterface;
use TheGame\Application\Component\ResourceMines\ResourceMinesRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetId;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceId;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class UpgradeMineEventListener
{
    public function __construct(
        private readonly ResourceMinesRepositoryInterface $minesRepository,
        private readonly ResourceMinesContextInterface $resourceMinesContext,
        private readonly MineFactoryInterface $resourceMineFactory,
    ) {
    }

    public function __invoke(ResourceMineConstructionHasBeenFinishedEvent $event): void
    {
        $planetId = new PlanetId($event->getPlanetId());
        $minesCollection = $this->minesRepository->findForPlanet($planetId);
        if ($minesCollection === null) {
            throw new InconsistentModelException(sprintf("Planet %d has no mines collection attached", $event->getPlanetId()));
        }

        $resourceId = new ResourceId($event->getResourceContextId());
        if ($minesCollection->hasMineForResource($resourceId) === false) {
            $mine = $this->resourceMineFactory->createNew($resourceId);
            $minesCollection->addMine($mine);
        }

        $resourceId = new ResourceId($event->getResourceContextId());
        $newMiningSpeed = $this->resourceMinesContext->getMiningSpeed(
            $event->getUpgradedLevel(),
            $resourceId
        );

        $minesCollection->upgradeMiningSpeed(
            $resourceId,
            $newMiningSpeed,
        );
    }
}
