<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceStorage\Domain\Entity;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\ResourceStorage\Domain\Exception\CannotUseUnsupportedResourceException;
use TheGame\Application\Component\ResourceStorage\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\ResourceStorage\Domain\StorageId;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceAmountInterface;
use TheGame\Application\SharedKernel\Domain\ResourceId;

final class StorageSpec extends ObjectBehavior
{
    public function let(): void
    {
        $storageId = "704ae84c-9114-4838-95d5-c2f2a734a02e";
        $resourceId = "6100ab0e-285b-40ea-a22a-0cbcb7d35421";
        $amount = 10;
        $limit = 100000;

        $this->beConstructedWith(
            new StorageId($storageId),
            new ResourceId($resourceId),
            $amount,
            $limit,
        );
    }

    public function it_has_identifier(): void
    {
        $this->getId()->shouldHaveType(StorageId::class);
        $this->getId()->getUuid()->shouldReturn("704ae84c-9114-4838-95d5-c2f2a734a02e");
    }

    public function it_returns_current_amount(): void
    {
        $this->getCurrentAmount()->shouldReturn(10);
    }

    public function it_supports_resource_amount(
        ResourceAmountInterface $resourceAmount,
    ): void {
        $resourceAmount->getResourceId()
            ->willReturn(new ResourceId("6100ab0e-285b-40ea-a22a-0cbcb7d35421"));

        $this->supports($resourceAmount)->shouldReturn(true);
    }

    public function it_doesnt_support_resource_amount(
        ResourceAmountInterface $resourceAmount,
    ): void {
        $resourceAmount->getResourceId()
            ->willReturn(new ResourceId("3a58570a-bf32-4bd2-9a65-1fa26567b80b"));

        $this->supports($resourceAmount)->shouldReturn(false);
    }

    public function it_hasnt_enough_resources_for_unsupported_resource(
        ResourceAmountInterface $resourceAmount,
    ): void {
        $resourceAmount->getResourceId()
            ->willReturn(new ResourceId("3a58570a-bf32-4bd2-9a65-1fa26567b80b"));

        $this->hasEnough($resourceAmount)->shouldReturn(false);
    }

    public function it_hasnt_enough_resources(
        ResourceAmountInterface $resourceAmount,
    ): void {
        $resourceAmount->getResourceId()
            ->willReturn(new ResourceId("6100ab0e-285b-40ea-a22a-0cbcb7d35421"));

        $resourceAmount->getAmount()
            ->willReturn(500);

        $this->hasEnough($resourceAmount)->shouldReturn(false);
    }

    public function it_has_enough_resources(
        ResourceAmountInterface $resourceAmount,
    ): void {
        $resourceAmount->getResourceId()
            ->willReturn(new ResourceId("6100ab0e-285b-40ea-a22a-0cbcb7d35421"));

        $resourceAmount->getAmount()
            ->willReturn(5);

        $this->hasEnough($resourceAmount)->shouldReturn(true);
    }

    public function it_uses_resources(
        ResourceAmountInterface $resourceAmount,
    ): void {
        $resourceAmount->getResourceId()
            ->willReturn(new ResourceId("6100ab0e-285b-40ea-a22a-0cbcb7d35421"));

        $resourceAmount->getAmount()
            ->willReturn(5);

        $planetId = new PlanetId("1ca09e5f-1418-4a53-9df7-d8ecd190e3fd");
        $this->use($planetId, $resourceAmount);
    }

    public function it_throws_exception_when_using_unsupported_resources(
        ResourceAmountInterface $resourceAmount,
    ): void {
        $resourceAmount->getResourceId()
            ->willReturn(new ResourceId("3a58570a-bf32-4bd2-9a65-1fa26567b80b"));

        $planetId = new PlanetId("1ca09e5f-1418-4a53-9df7-d8ecd190e3fd");
        $this->shouldThrow(CannotUseUnsupportedResourceException::class)
            ->during('use', [$planetId, $resourceAmount]);
    }

    public function it_throws_exception_when_using_more_resources_than_already_have(
        ResourceAmountInterface $resourceAmount,
    ): void {
        $resourceAmount->getResourceId()
            ->willReturn(new ResourceId("6100ab0e-285b-40ea-a22a-0cbcb7d35421"));

        $resourceAmount->getAmount()
            ->willReturn(500);

        $planetId = new PlanetId("1ca09e5f-1418-4a53-9df7-d8ecd190e3fd");
        $this->shouldThrow(InsufficientResourcesException::class)
            ->during('use', [$planetId, $resourceAmount]);
    }

    public function it_dispatches_amount_without_limit_specified(): void
    {
        $storageId = "704ae84c-9114-4838-95d5-c2f2a734a02e";
        $resourceId = "6100ab0e-285b-40ea-a22a-0cbcb7d35421";
        $amount = 10;

        $this->beConstructedWith(
            new StorageId($storageId),
            new ResourceId($resourceId),
            $amount,
        );

        $this->dispatch(1000000000);

        $this->getCurrentAmount()->shouldReturn(1000000010);
    }

    public function it_dispatches_amount_with_bit_limit_specified(): void
    {
        $storageId = "704ae84c-9114-4838-95d5-c2f2a734a02e";
        $resourceId = "6100ab0e-285b-40ea-a22a-0cbcb7d35421";
        $amount = 10;
        $limit = 100000;

        $this->beConstructedWith(
            new StorageId($storageId),
            new ResourceId($resourceId),
            $amount,
            $limit,
        );

        $this->dispatch(10);

        $this->getCurrentAmount()->shouldReturn(20);
    }

    public function it_dispatches_amount_and_reaches_limit(): void
    {
        $storageId = "704ae84c-9114-4838-95d5-c2f2a734a02e";
        $resourceId = "6100ab0e-285b-40ea-a22a-0cbcb7d35421";
        $amount = 10;
        $limit = 100000;

        $this->beConstructedWith(
            new StorageId($storageId),
            new ResourceId($resourceId),
            $amount,
            $limit,
        );

        $this->dispatch(10000000);

        $this->getCurrentAmount()->shouldReturn($limit);
    }
}
