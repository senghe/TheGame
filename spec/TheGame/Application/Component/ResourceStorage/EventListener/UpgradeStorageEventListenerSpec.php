<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceStorage\EventListener;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\Balance\Bridge\ResourceStoragesContextInterface;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ResourceStorageConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\ResourceStorage\Domain\Entity\StoragesCollection;
use TheGame\Application\Component\ResourceStorage\ResourceStoragesRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceId;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class UpgradeStorageEventListenerSpec extends ObjectBehavior
{
    public function let(
        ResourceStoragesRepositoryInterface $storagesRepository,
        ResourceStoragesContextInterface $resourceStoragesContext,
    ): void {
        $this->beConstructedWith(
            $storagesRepository,
            $resourceStoragesContext
        );
    }
    public function it_throws_exception_when_aggregate_is_not_found(
        ResourceStoragesRepositoryInterface $storagesRepository,
    ): void {
        $planetId = "4DF55530-FFF2-439C-B616-41C8244C596C";

        $storagesRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn(null);

        $resourceContextId = "0F8DC0DB-766C-4D04-998B-1D9A86FC7A7C";
        $currentLevel = 5;
        $event = new ResourceStorageConstructionHasBeenFinishedEvent(
            $planetId, $resourceContextId, $currentLevel
        );
        $this->shouldThrow(InconsistentModelException::class)
            ->during('__invoke', [
                $event
            ]);
    }

    public function it_upgrades_storage_limit(
        ResourceStoragesRepositoryInterface $storagesRepository,
        ResourceStoragesContextInterface $resourceStoragesContext,
        StoragesCollection $storagesCollection,
    ): void {
        $planetId = "4DF55530-FFF2-439C-B616-41C8244C596C";
        $resourceContextId = "0F8DC0DB-766C-4D04-998B-1D9A86FC7A7C";
        $currentLevel = 5;

        $storagesRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn($storagesCollection);

        $resourceStoragesContext->getLimit($currentLevel, new ResourceId($resourceContextId))
            ->willReturn(500);

        $storagesCollection->upgradeLimit(new ResourceId($resourceContextId), 500)
            ->shouldBeCalledOnce();

        $event = new ResourceStorageConstructionHasBeenFinishedEvent(
            $planetId, $resourceContextId, $currentLevel
        );

        $this->__invoke($event);
    }
}
