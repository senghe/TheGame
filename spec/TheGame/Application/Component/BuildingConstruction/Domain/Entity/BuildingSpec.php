<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\Domain\Entity;

use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingId;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\BuildingIsAlreadyUpgradingException;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\BuildingIsNotUpgradingYetException;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\BuildingTimeHasNotPassedException;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceId;
use TheGame\Application\SharedKernel\Domain\ResourceIdInterface;

final class BuildingSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "0CAB5B81-969A-4C2A-88B6-91B7B4B45E68";
        $buildingId = "B51FAABD-AC4C-4806-A3E7-A35234E54ABB";
        $buildingType = BuildingType::ResourceStorage;
        $resourceContextId = "D4EF6F12-604D-4590-944F-0BD5F5270A53";

        $this->beConstructedWith(
            new PlanetId($planetId),
            new BuildingId($buildingId),
            $buildingType,
            new ResourceId($resourceContextId),
        );
    }

    public function it_has_identifier(): void
    {
        $this->getId()->shouldHaveType(BuildingId::class);
        $this->getId()->getUuid()->shouldReturn("B51FAABD-AC4C-4806-A3E7-A35234E54ABB");
    }

    public function it_has_planet_identifier(): void
    {
        $this->getPlanetId()->shouldHaveType(PlanetIdInterface::class);
        $this->getPlanetId()->getUuid()->shouldReturn("0CAB5B81-969A-4C2A-88B6-91B7B4B45E68");
    }

    public function it_has_current_level(): void
    {
        $this->getCurrentLevel()->shouldReturn(0);
    }

    public function it_has_a_type(): void
    {
        $this->getType()->shouldReturn(BuildingType::ResourceStorage);
    }

    public function it_starts_upgrading(): void
    {
        $this->startUpgrading(new DateTimeImmutable("now +10 seconds"));
    }

    public function it_throws_exception_when_starts_upgrading_building_which_is_already_during_upgrade(): void
    {
        $this->startUpgrading(new DateTimeImmutable("now +10 seconds"));

        $this->shouldThrow(BuildingIsAlreadyUpgradingException::class)
            ->during('startUpgrading', [new DateTimeImmutable("now +10 seconds")]);
    }

    public function it_cancels_upgrading(): void
    {
        $this->startUpgrading(new DateTimeImmutable("now +10 seconds"));

        $this->cancelUpgrading();
    }

    public function it_throws_exception_when_cancels_upgrading_a_building_which_is_not_during_upgrade(): void
    {
        $this->startUpgrading(new DateTimeImmutable("now +10 seconds"));

        $this->cancelUpgrading();
        $this->shouldThrow(BuildingIsNotUpgradingYetException::class)
            ->during('cancelUpgrading', []);
    }

    public function it_finishes_upgrading_building(): void
    {
        $this->startUpgrading(new DateTimeImmutable("now -10 seconds"));
        $this->finishUpgrading();
    }

    public function it_throws_exception_when_finishes_upgrading_a_building_which_is_not_during_upgrade(): void
    {
        $this->startUpgrading(new DateTimeImmutable("now -10 seconds"));
        $this->finishUpgrading();

        $this->shouldThrow(BuildingIsNotUpgradingYetException::class)
            ->during('finishUpgrading', []);
    }

    public function it_throws_exception_when_finishes_upgrading_a_building_when_upgrade_time_didnt_pass(): void
    {
        $this->startUpgrading(new DateTimeImmutable("now +1 second"));

        $this->shouldThrow(BuildingTimeHasNotPassedException::class)
            ->during('finishUpgrading', []);
    }

    public function it_has_resource_context_id(): void
    {
        $this->getResourceContextId()->shouldHaveType(ResourceIdInterface::class);
        $this->getResourceContextId()->getUuid()->shouldReturn("D4EF6F12-604D-4590-944F-0BD5F5270A53");
    }

    public function it_has_no_resource_context_id(): void
    {
        $planetId = "0CAB5B81-969A-4C2A-88B6-91B7B4B45E68";
        $buildingId = "B51FAABD-AC4C-4806-A3E7-A35234E54ABB";
        $buildingType = BuildingType::ResourceStorage;

        $this->beConstructedWith(
            new PlanetId($planetId),
            new BuildingId($buildingId),
            $buildingType,
            null,
        );

        $this->getResourceContextId()->shouldReturn(null);
    }
}
