<?php

declare(strict_types=1);

namespace spec\TheGame\Application\SharedKernel\Domain;

use PhpSpec\ObjectBehavior;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceId;
use TheGame\Application\SharedKernel\Domain\Exception\InvalidResourceAmountException;
use TheGame\Application\SharedKernel\Domain\ResourceAmountInterface;

final class ResourceAmountSpec extends ObjectBehavior
{
    public function let(): void
    {
        $resourceId = "67c11028-efcd-4e2d-ad4b-c7dd4be398d3";
        $amount = 500;

        $this->beConstructedWith(new ResourceId($resourceId), $amount);
    }

    public function it_implements_interface(): void
    {
        $this->shouldImplement(ResourceAmountInterface::class);
    }

    public function it_has_resource_id(): void
    {
        $this->getResourceId()->shouldHaveType(ResourceId::class);
        $this->getResourceId()->getUuid()->shouldReturn("67c11028-efcd-4e2d-ad4b-c7dd4be398d3");
    }

    public function it_has_amount_value(): void
    {
        $this->getAmount()->shouldReturn(500);
    }

    public function it_throws_exception_when_amount_is_less_than_zero(): void
    {
        $resourceId = "67c11028-efcd-4e2d-ad4b-c7dd4be398d3";
        $amount = -500;

        $this->beConstructedWith(new ResourceId($resourceId), $amount);

        $this->shouldThrow(InvalidResourceAmountException::class)
            ->during('__construct', [new ResourceId($resourceId), $amount]);
    }

    public function it_throws_exception_when_amount_is_zero(): void
    {
        $resourceId = "67c11028-efcd-4e2d-ad4b-c7dd4be398d3";
        $amount = 0;

        $this->beConstructedWith(new ResourceId($resourceId), $amount);

        $this->shouldThrow(InvalidResourceAmountException::class)
            ->during('__construct', [new ResourceId($resourceId), $amount]);
    }
}
