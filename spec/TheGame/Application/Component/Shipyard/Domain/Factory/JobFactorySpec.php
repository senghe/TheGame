<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Domain\Factory;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\Shipyard\Domain\ConstructibleUnit;
use TheGame\Application\Component\Shipyard\Domain\JobIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceRequirementsInterface;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class JobFactorySpec extends ObjectBehavior
{
    public function let(
        UuidGeneratorInterface $uuidGenerator,
    ): void {
        $this->beConstructedWith($uuidGenerator);
    }

    public function it_creates_cannons_job(
        ResourceRequirementsInterface $resourceRequirements,
        UuidGeneratorInterface $uuidGenerator,
        JobIdInterface $jobId,
    ): void {
        $cannonType = 'laser';
        $quantity = 50;
        $shipyardLevel = 3;
        $cannonConstructionTime = 10;
        $cannonProductionLoad = 3;

        $jobId->getUuid()->willReturn("509B69AD-90FA-4D95-BBA5-43459096F7D");
        $uuidGenerator->generateNewShipyardJobId()->willReturn($jobId);

        $cannonsJob = $this->createNewCannonsJob(
            $cannonType,
            $quantity,
            $shipyardLevel,
            $cannonConstructionTime,
            $cannonProductionLoad,
            $resourceRequirements,
        );

        $cannonsJob->getId()->getUuid()->shouldReturn("509B69AD-90FA-4D95-BBA5-43459096F7D");
        $cannonsJob->getConstructionUnit()->shouldReturn(ConstructibleUnit::Cannon);
        $cannonsJob->getType()->shouldReturn($cannonType);
        $cannonsJob->getQuantity()->shouldReturn(50);
        $cannonsJob->getDuration()->shouldReturn(500);
        $cannonsJob->getProductionLoad()->shouldReturn(150);
    }

    public function it_creates_ships_job(
        ResourceRequirementsInterface $resourceRequirements,
        UuidGeneratorInterface $uuidGenerator,
        JobIdInterface $jobId,
    ): void {
        $shipType = 'light-fighter';
        $quantity = 50;
        $shipyardLevel = 3;
        $shipConstructionTime = 10;
        $shipProductionLoad = 3;

        $jobId->getUuid()->willReturn("509B69AD-90FA-4D95-BBA5-43459096F7D");
        $uuidGenerator->generateNewShipyardJobId()->willReturn($jobId);

        $shipsJob = $this->createNewShipsJob(
            $shipType,
            $quantity,
            $shipyardLevel,
            $shipConstructionTime,
            $shipProductionLoad,
            $resourceRequirements,
        );

        $shipsJob->getId()->getUuid()->shouldReturn("509B69AD-90FA-4D95-BBA5-43459096F7D");
        $shipsJob->getConstructionUnit()->shouldReturn(ConstructibleUnit::Ship);
        $shipsJob->getType()->shouldReturn($shipType);
        $shipsJob->getQuantity()->shouldReturn(50);
        $shipsJob->getDuration()->shouldReturn(500);
        $shipsJob->getProductionLoad()->shouldReturn(150);
    }
}
