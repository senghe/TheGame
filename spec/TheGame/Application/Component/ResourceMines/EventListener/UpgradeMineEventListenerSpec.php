<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceMines\EventListener;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\Balance\Bridge\ResourceMinesContextInterface;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ResourceMineConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\ResourceMines\Domain\Entity\Mine;
use TheGame\Application\Component\ResourceMines\Domain\Entity\MinesCollection;
use TheGame\Application\Component\ResourceMines\Domain\Factory\MineFactoryInterface;
use TheGame\Application\Component\ResourceMines\ResourceMinesRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceId;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class UpgradeMineEventListenerSpec extends ObjectBehavior
{
    public function let(
        ResourceMinesRepositoryInterface $minesRepository,
        ResourceMinesContextInterface $resourceMinesContext,
        MineFactoryInterface $mineFactory,
    ): void {
        $this->beConstructedWith(
            $minesRepository,
            $resourceMinesContext,
            $mineFactory,
        );
    }

    public function it_throws_exception_when_aggregate_is_not_found(
        ResourceMinesRepositoryInterface $minesRepository,
    ): void {
        $planetId = "7D5C44D6-0617-4F11-A7A3-76F66B4024E2";
        $buildingId = "94D4C48A-AB93-4189-B71E-44F3571D389B";
        $resourceId = "1065CCFF-8111-43CF-9D66-9880FADE72A7";

        $minesRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn(null);

        $event = new ResourceMineConstructionHasBeenFinishedEvent(
            $planetId,
            $buildingId,
            $resourceId,
            2
        );
        $this->shouldThrow(InconsistentModelException::class)
            ->during('__invoke', [
                $event,
            ]);
    }

    public function it_upgrades_mining_speed(
        ResourceMinesRepositoryInterface $minesRepository,
        ResourceMinesContextInterface $resourceMinesContext,
        MinesCollection $minesCollection,
    ): void {
        $planetId = "7D5C44D6-0617-4F11-A7A3-76F66B4024E2";
        $buildingId = "94D4C48A-AB93-4189-B71E-44F3571D389B";
        $resourceId = "1065CCFF-8111-43CF-9D66-9880FADE72A7";

        $minesRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn($minesCollection);

        $minesCollection->hasMineForResource(new ResourceId($resourceId))
            ->willReturn(true);

        $resourceMinesContext->getMiningSpeed(2, new ResourceId($resourceId))
            ->willReturn(700);

        $minesCollection->upgradeMiningSpeed(new ResourceId($resourceId), 700)
            ->shouldBeCalledOnce();

        $event = new ResourceMineConstructionHasBeenFinishedEvent(
            $planetId,
            $buildingId,
            $resourceId,
            2
        );
        $this->__invoke($event);
    }

    public function it_creates_mine_when_built_a_new_mine_building(
        ResourceMinesRepositoryInterface $minesRepository,
        ResourceMinesContextInterface $resourceMinesContext,
        MinesCollection $minesCollection,
        MineFactoryInterface $mineFactory,
        Mine $mine,
    ): void {
        $planetId = "7D5C44D6-0617-4F11-A7A3-76F66B4024E2";
        $buildingId = "94D4C48A-AB93-4189-B71E-44F3571D389B";
        $resourceId = "1065CCFF-8111-43CF-9D66-9880FADE72A7";

        $minesRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn($minesCollection);

        $minesCollection->hasMineForResource(new ResourceId($resourceId))
            ->willReturn(false);

        $mineFactory->createNew(new ResourceId($resourceId))
            ->willReturn($mine);

        $minesCollection->addMine($mine)
            ->shouldBeCalledOnce();

        $resourceMinesContext->getMiningSpeed(2, new ResourceId($resourceId))
            ->willReturn(700);

        $minesCollection->upgradeMiningSpeed(new ResourceId($resourceId), 700)
            ->shouldBeCalledOnce();

        $event = new ResourceMineConstructionHasBeenFinishedEvent(
            $planetId,
            $buildingId,
            $resourceId,
            2
        );
        $this->__invoke($event);
    }
}
