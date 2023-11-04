<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceStorage\Domain\Entity;

use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\ResourceStorage\Domain\Entity\Storage;
use TheGame\Application\Component\ResourceStorage\Domain\Exception\CannotUseUnsupportedResourceException;
use TheGame\Application\Component\ResourceStorage\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\ResourceStorage\Domain\StorageCollectionId;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceAmountInterface;
use TheGame\Application\SharedKernel\Domain\ResourceId;

final class StoragesCollectionSpec extends ObjectBehavior
{
    public function let(): void
    {
        $storageCollectionId = "704ae84c-9114-4838-95d5-c2f2a734a02e";
        $planetId = "6100ab0e-285b-40ea-a22a-0cbcb7d35421";

        $this->beConstructedWith(
            new StorageCollectionId($storageCollectionId),
            new PlanetId($planetId),
            new DateTimeImmutable("1 second ago"),
        );
    }

    public function it_has_identifier(): void
    {
        $this->getId()->shouldHaveType(StorageCollectionId::class);
        $this->getId()->getUuid()->shouldReturn("704ae84c-9114-4838-95d5-c2f2a734a02e");
    }

    public function it_adds_storage(
        Storage $storage
    ): void {
        $this->add($storage);
    }

    public function it_supports_resource_amount(
        Storage $storage,
        ResourceAmountInterface $resourceAmount,
    ): void {
        $this->add($storage);

        $storage->supports($resourceAmount)
            ->willReturn(true);

        $this->supports($resourceAmount)
            ->shouldReturn(true);
    }

    public function it_doesnt_support_resource_amount(
        Storage $storage,
        ResourceAmountInterface $resourceAmount,
    ): void {
        $this->add($storage);

        $storage->supports($resourceAmount)
            ->willReturn(false);

        $this->supports($resourceAmount)
            ->shouldReturn(false);
    }

    public function it_has_enough_resources_for_resource_amount(
        Storage $storage,
        ResourceAmountInterface $resourceAmount,
    ): void {
        $this->add($storage);

        $storage->supports($resourceAmount)
            ->willReturn(true);

        $storage->hasEnough($resourceAmount)
            ->willReturn(true);

        $this->hasEnough($resourceAmount)
            ->shouldReturn(true);
    }

    public function it_hasnt_enough_resources_for_resource_amount_while_supporting_resource(
        Storage $storage,
        ResourceAmountInterface $resourceAmount,
    ): void {
        $this->add($storage);

        $storage->supports($resourceAmount)
            ->willReturn(true);

        $storage->hasEnough($resourceAmount)
            ->willReturn(false);

        $this->hasEnough($resourceAmount)
            ->shouldReturn(false);
    }

    public function it_hasnt_enough_resources_for_resource_amount_becouse_of_not_supporting_resource(
        Storage $storage,
        ResourceAmountInterface $resourceAmount,
    ): void {
        $this->add($storage);

        $storage->supports($resourceAmount)
            ->willReturn(false);

        $this->hasEnough($resourceAmount)
            ->shouldReturn(false);
    }

    public function it_uses_supported_resource(
        Storage $storage,
        ResourceAmountInterface $resourceAmount,
    ): void {
        $this->add($storage);

        $storage->supports($resourceAmount)
            ->willReturn(true);

        $storage->hasEnough($resourceAmount)
            ->willReturn(true);

        $storage->use($resourceAmount)
            ->shouldBeCalledOnce();

        $this->use($resourceAmount);
    }

    public function it_throws_exception_when_using_not_supported_resource(
        Storage $storage,
        ResourceAmountInterface $resourceAmount,
    ): void {
        $this->add($storage);

        $resourceId = "a8d6fc51-9f91-4a46-8785-4c6a58464802";
        $resourceAmount->getResourceId()
            ->willReturn(new ResourceId($resourceId));
        $resourceAmount->getAmount()
            ->willReturn(10);

        $storage->supports($resourceAmount)
            ->willReturn(false);

        $this->shouldThrow(CannotUseUnsupportedResourceException::class)
            ->during('use', [$resourceAmount]);
    }

    public function it_throws_exception_when_using_supported_but_insufficient_resource(
        Storage $storage,
        ResourceAmountInterface $resourceAmount,
    ): void {
        $this->add($storage);

        $resourceId = "a8d6fc51-9f91-4a46-8785-4c6a58464802";
        $resourceAmount->getResourceId()
            ->willReturn(new ResourceId($resourceId));
        $resourceAmount->getAmount()
            ->willReturn(10);

        $storage->supports($resourceAmount)
            ->willReturn(true);

        $storage->hasEnough($resourceAmount)
            ->willReturn(false);

        $this->shouldThrow(InsufficientResourcesException::class)
            ->during('use', [$resourceAmount]);
    }

    public function it_dispatches_supported_resource(
        Storage $storage,
        ResourceAmountInterface $resourceAmount,
    ): void {
        $this->add($storage);

        $resourceAmount->getAmount()->willReturn(10);

        $storage->supports($resourceAmount)->willReturn(true);
        $storage->dispatch(10)->shouldBeCalledOnce();

        $this->dispatch($resourceAmount);
    }

    public function it_does_nothing_when_dispatching_unsupported_resource(
        Storage $storage,
        ResourceAmountInterface $resourceAmount,
    ): void {
        $this->add($storage);

        $storage->supports($resourceAmount)->willReturn(false);

        $this->dispatch($resourceAmount);
    }
}
