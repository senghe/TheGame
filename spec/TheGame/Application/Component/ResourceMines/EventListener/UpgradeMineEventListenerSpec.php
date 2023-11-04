<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceMines\EventListener;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\Balance\Bridge\ResourceMinesContextInterface;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ResourceMineConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\ResourceMines\Domain\Entity\MinesCollection;
use TheGame\Application\Component\ResourceMines\ResourceMinesRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceId;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class UpgradeMineEventListenerSpec extends ObjectBehavior
{
    public function let(
        ResourceMinesRepositoryInterface $minesRepository,
        ResourceMinesContextInterface $resourceMinesContext,
    ): void {
        $this->beConstructedWith(
            $minesRepository,
            $resourceMinesContext,
        );
    }
    public function it_throws_exception_when_aggregate_is_not_found(
        ResourceMinesRepositoryInterface $minesRepository,
    ): void {
        $planetId = "7D5C44D6-0617-4F11-A7A3-76F66B4024E2";
        $resourceId = "1065CCFF-8111-43CF-9D66-9880FADE72A7";

        $minesRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn(null);

        $event = new ResourceMineConstructionHasBeenFinishedEvent(
            $planetId, $resourceId, 2
        );
        $this->shouldThrow(InconsistentModelException::class)
            ->during('__invoke', [
                $event
            ]);
    }

    public function it_upgrades_mining_speed(
        ResourceMinesRepositoryInterface $minesRepository,
        ResourceMinesContextInterface $resourceMinesContext,
        MinesCollection $minesCollection,
    ): void {
        $planetId = "7D5C44D6-0617-4F11-A7A3-76F66B4024E2";
        $resourceId = "1065CCFF-8111-43CF-9D66-9880FADE72A7";

        $minesRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn($minesCollection);

        $resourceMinesContext->getMiningSpeed(2, new ResourceId($resourceId))
            ->willReturn(700);

        $minesCollection->upgradeMiningSpeed(new ResourceId($resourceId), 700)
            ->shouldBeCalledOnce();

        $event = new ResourceMineConstructionHasBeenFinishedEvent(
            $planetId, $resourceId, 2
        );
        $this->__invoke($event);
    }
}
