<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceStorage\Domain\Factory;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\ResourceStorage\Domain\Entity\Storage;
use TheGame\Application\Component\ResourceStorage\Domain\StorageIdInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceIdInterface;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class StorageFactorySpec extends ObjectBehavior
{
    public function let(UuidGeneratorInterface $uuidGenerator): void
    {
        $this->beConstructedWith($uuidGenerator);
    }

    public function it_creates_new_storage_for_resource(
        ResourceIdInterface $resourceId,
        UuidGeneratorInterface $uuidGenerator,
        StorageIdInterface $storageId,
    ): void {
        $uuidGenerator->generateNewStorageId()
            ->willReturn($storageId);

        $createdStorage = $this->createNew($resourceId);

        $createdStorage->shouldHaveType(Storage::class);
        $createdStorage->getId()->shouldReturn($storageId);
        $createdStorage->getCurrentAmount()->shouldReturn(1000);
    }
}
