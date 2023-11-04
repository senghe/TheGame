<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMines\EventListener;

use TheGame\Application\Component\Balance\Bridge\ResourceMinesContextInterface;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ResourceMineConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\ResourceMines\ResourceMinesRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceId;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class UpgradeMineEventListener
{
    public function __construct(
        private readonly ResourceMinesRepositoryInterface $minesRepository,
        private readonly ResourceMinesContextInterface $resourceMinesContext,
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
        $newMiningSpeed = $this->resourceMinesContext->getMiningSpeed(
            $event->getLevel(), $resourceId
        );

        $minesCollection->upgradeMiningSpeed(
            $resourceId,
            $newMiningSpeed,
        );
    }
}
