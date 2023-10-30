<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceMines\Domain\Entity;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\ResourceMines\Domain\Entity\Mine;
use TheGame\Application\Component\ResourceMines\Domain\MinesCollectionId;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceAmountInterface;
use TheGame\Application\SharedKernel\Domain\ResourceId;

final class MinesCollectionSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith(
            new MinesCollectionId("8fbba8d3-33ab-4693-a478-b4a1a52fde5d"),
            new PlanetId("cf49bb9f-e47c-4fe1-b0e5-ca3f467fb8bd")
        );
    }

    public function it_has_identifier(): void
    {
        $this->getId()->shouldHaveType(MinesCollectionId::class);
        $this->getId()->getUuid()->shouldReturn("8fbba8d3-33ab-4693-a478-b4a1a52fde5d");
    }

    public function it_is_empty(): void
    {
        $this->isEmpty()->shouldReturn(true);
    }

    public function it_is_not_empty(
        Mine $mine
    ): void {
        $this->addMine($mine);

        $this->isEmpty()->shouldReturn(false);
    }

    public function it_extracts_resources_from_two_mines(
        Mine $mine1,
        Mine $mine2,
        ResourceAmountInterface $resourceAmount1,
        ResourceAmountInterface $resourceAmount2,
    ): void {
        $this->addMine($mine1);
        $mine1->extract()->willReturn($resourceAmount1);
        $resourceId1 = new ResourceId("91eca0f6-3597-4fc0-bff8-934b20bf51a7");
        $resourceAmount1->getResourceId()->willReturn($resourceId1);
        $resourceAmount1->getAmount()->willReturn(5);

        $this->addMine($mine2);
        $mine2->extract()->willReturn($resourceAmount2);
        $resourceId2 = new ResourceId("d63a2187-daf5-437f-8586-7fa4e0ef5d7a");
        $resourceAmount2->getResourceId()->willReturn($resourceId2);
        $resourceAmount2->getAmount()->willReturn(10);

        $extractedResult = $this->extract();

        $extractedResult->shouldBeArray();
        $extractedResult->shouldHaveCount(2);

        $extractedResult[0]->shouldImplement(ResourceAmountInterface::class);
        $extractedResult[0]->getResourceId()->shouldHaveType(ResourceId::class);
        $extractedResult[0]->getResourceId()->getUuid()->shouldReturn("91eca0f6-3597-4fc0-bff8-934b20bf51a7");
        $extractedResult[0]->getAmount()->shouldReturn(5);

        $extractedResult[1]->shouldImplement(ResourceAmountInterface::class);
        $extractedResult[1]->getResourceId()->shouldHaveType(ResourceId::class);
        $extractedResult[1]->getResourceId()->getUuid()->shouldReturn("d63a2187-daf5-437f-8586-7fa4e0ef5d7a");
        $extractedResult[1]->getAmount()->shouldReturn(10);
    }
}
