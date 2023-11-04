<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceMines\Domain\Entity;

use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\ResourceMines\Domain\MineId;
use TheGame\Application\Component\ResourceMines\Domain\MineIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;
use TheGame\Application\SharedKernel\Domain\ResourceId;

final class MineSpec extends ObjectBehavior
{
    public function it_has_identifier(): void
    {
        $mineId = new MineId("df58a284-9255-4467-8a09-4d9296d6af60");
        $resourceId = new ResourceId("78a71358-a525-48f1-88b4-8280eb3ea4c4");

        $this->beConstructedWith(
            $mineId,
            $resourceId,
            60,
            60,
            1.0,
            new DateTimeImmutable("1 second ago"),
        );

        $this->getId()->shouldHaveType(MineIdInterface::class);
        $this->getId()->getUuid()->shouldBe("df58a284-9255-4467-8a09-4d9296d6af60");
    }

    public function it_extracts_resource_amount_on_first_level_from_one_second_delay(): void
    {
        $mineId = new MineId("df58a284-9255-4467-8a09-4d9296d6af60");
        $resourceId = new ResourceId("78a71358-a525-48f1-88b4-8280eb3ea4c4");

        $this->beConstructedWith(
            $mineId,
            $resourceId,
            60,
            60,
            1.0,
            new DateTimeImmutable("1 second ago"),
        );

        $extractedData = $this->extract();
        $extractedData->shouldHaveType(ResourceAmount::class);
        $extractedData->getResourceId()->shouldReturn($resourceId);
        $extractedData->getAmount()->shouldReturn(1);
    }

    public function it_extracts_resource_amount_after_upgrade_from_one_second_delay(): void
    {
        $mineId = new MineId("df58a284-9255-4467-8a09-4d9296d6af60");
        $resourceId = new ResourceId("78a71358-a525-48f1-88b4-8280eb3ea4c4");

        $this->beConstructedWith(
            $mineId,
            $resourceId,
            60,
            60,
            1.0,
            new DateTimeImmutable("1 second ago"),
        );

        $this->upgradeMiningSpeed();

        $extractedData = $this->extract();
        $extractedData->shouldHaveType(ResourceAmount::class);
        $extractedData->getResourceId()->shouldReturn($resourceId);
        $extractedData->getAmount()->shouldReturn(2);
    }

    public function it_extracts_resource_amount_from_ten_seconds_delay(): void
    {
        $mineId = new MineId("df58a284-9255-4467-8a09-4d9296d6af60");
        $resourceId = new ResourceId("78a71358-a525-48f1-88b4-8280eb3ea4c4");

        $this->beConstructedWith(
            $mineId,
            $resourceId,
            60,
            60,
            1.0,
            new DateTimeImmutable("10 seconds ago"),
        );

        $extractedData = $this->extract();
        $extractedData->shouldHaveType(ResourceAmount::class);
        $extractedData->getResourceId()->shouldReturn($resourceId);
        $extractedData->getAmount()->shouldReturn(10);
    }
}
