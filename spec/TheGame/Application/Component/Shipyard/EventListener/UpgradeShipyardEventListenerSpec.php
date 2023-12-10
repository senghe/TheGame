<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\EventListener;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\Balance\Bridge\ShipyardContextInterface;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingId;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ShipyardConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\Shipyard\Domain\Entity\Shipyard;
use TheGame\Application\Component\Shipyard\Domain\Factory\ShipyardFactoryInterface;
use TheGame\Application\Component\Shipyard\Exception\ShipyardHasNotBeenFoundException;
use TheGame\Application\Component\Shipyard\ShipyardRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetId;

final class UpgradeShipyardEventListenerSpec extends ObjectBehavior
{
    public function let(
        ShipyardRepositoryInterface $shipyardRepository,
        ShipyardFactoryInterface $shipyardFactory,
        ShipyardContextInterface $shipyardBalanceContext,
    ): void {
        $this->beConstructedWith(
            $shipyardRepository,
            $shipyardFactory,
            $shipyardBalanceContext,
        );
    }

    public function it_upgrades_shipyard_when_shipyard_building_has_been_upgraded(
        ShipyardRepositoryInterface $shipyardRepository,
        ShipyardContextInterface $shipyardBalanceContext,
        Shipyard $shipyard,
    ): void {
        $planetId = "26A52569-15BB-449C-92C0-6B1EB0C1CDD5";
        $buildingId = "576FAA85-E3BD-4095-8B76-C7C1769488A5";
        $upgradedLevel = 5;

        $shipyardRepository->findForBuilding(
            new PlanetId($planetId),
            new BuildingId($buildingId),
        )->willReturn($shipyard);

        $shipyardBalanceContext->getShipyardProductionLimit(5)
            ->willReturn(20);

        $shipyard->upgrade(20)->shouldBeCalledOnce();

        $event = new ShipyardConstructionHasBeenFinishedEvent(
            $planetId,
            $buildingId,
            $upgradedLevel
        );
        $this->__invoke($event);
    }

    public function it_creates_shipyard_when_shipyard_has_level_1(
        ShipyardFactoryInterface $shipyardFactory,
        ShipyardContextInterface $shipyardBalanceContext,
        Shipyard $shipyard,
    ): void {
        $planetId = "26A52569-15BB-449C-92C0-6B1EB0C1CDD5";
        $buildingId = "576FAA85-E3BD-4095-8B76-C7C1769488A5";
        $upgradedLevel = 1;

        $shipyardFactory->create(
            new PlanetId($planetId),
            new BuildingId($buildingId),
        )->willReturn($shipyard);

        $shipyardBalanceContext->getShipyardProductionLimit(1)
            ->willReturn(3);

        $shipyard->upgrade(3)->shouldBeCalledOnce();

        $event = new ShipyardConstructionHasBeenFinishedEvent(
            $planetId,
            $buildingId,
            $upgradedLevel
        );
        $this->__invoke($event);
    }

    public function it_throws_exception_when_shipyard_has_level_greater_than_1_but_not_exist(
        ShipyardRepositoryInterface $shipyardRepository,
    ): void {
        $planetId = "26A52569-15BB-449C-92C0-6B1EB0C1CDD5";
        $buildingId = "576FAA85-E3BD-4095-8B76-C7C1769488A5";
        $upgradedLevel = 5;

        $shipyardRepository->findForBuilding(
            new PlanetId($planetId),
            new BuildingId($buildingId),
        )->willReturn(null);

        $event = new ShipyardConstructionHasBeenFinishedEvent(
            $planetId,
            $buildingId,
            $upgradedLevel
        );
        $this->shouldThrow(ShipyardHasNotBeenFoundException::class)
            ->during('__invoke', [$event]);
    }
}
