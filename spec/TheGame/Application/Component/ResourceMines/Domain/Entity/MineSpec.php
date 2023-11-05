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
    public function let(): void
    {
        $mineId = new MineId("df58a284-9255-4467-8a09-4d9296d6af60");
        $resourceId = new ResourceId("78a71358-a525-48f1-88b4-8280eb3ea4c4");

        $this->beConstructedWith(
            $mineId,
            $resourceId,
            60,
            new DateTimeImmutable("1 second ago"),
        );
    }

    public function it_has_identifier(): void
    {
        $this->getId()->shouldHaveType(MineIdInterface::class);
        $this->getId()->getUuid()->shouldBe("df58a284-9255-4467-8a09-4d9296d6af60");
    }

    public function it_extracts_resource_amount_on_first_level_from_one_second_delay(): void
    {
        $resourceId = new ResourceId("78a71358-a525-48f1-88b4-8280eb3ea4c4");

        $extractedData = $this->extract();
        $extractedData->shouldHaveType(ResourceAmount::class);
        $extractedData->getResourceId()->shouldBeLike($resourceId);
        $extractedData->getAmount()->shouldReturn(1);
    }

    public function it_extracts_resource_amount_after_upgrade_from_one_second_delay(): void
    {
        $resourceId = new ResourceId("78a71358-a525-48f1-88b4-8280eb3ea4c4");

        $this->upgradeMiningSpeed(120);

        $extractedData = $this->extract();
        $extractedData->shouldHaveType(ResourceAmount::class);
        $extractedData->getResourceId()->shouldBeLike($resourceId);
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
            new DateTimeImmutable("10 seconds ago"),
        );

        $extractedData = $this->extract();
        $extractedData->shouldHaveType(ResourceAmount::class);
        $extractedData->getResourceId()->shouldReturn($resourceId);
        $extractedData->getAmount()->shouldReturn(10);
    }

    public function it_is_for_resource_with_specified_id(): void
    {
        $resourceId = new ResourceId("78a71358-a525-48f1-88b4-8280eb3ea4c4");

        $this->isForResource($resourceId)->shouldReturn(true);
    }

    public function it_is_not_for_resource_with_specified_id(): void
    {
        $resourceId = new ResourceId("EDE1F927-FEC5-4F79-B982-B7DF05CF36C6");

        $this->isForResource($resourceId)->shouldReturn(false);
    }
}
