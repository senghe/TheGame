<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain\Factory;

use TheGame\Application\Component\ResourceStorage\Domain\Entity\Storage;
use TheGame\Application\SharedKernel\Domain\ResourceIdInterface;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class StorageFactory implements StorageFactoryInterface
{
    private const DEFAULT_STORAGE_LIMIT = 10000;

    private const DEFAULT_STORAGE_AMOUNT = 1000;

    public function __construct(
        private readonly UuidGeneratorInterface $uuidGenerator,
    ) {
    }

    public function createNew(ResourceIdInterface $resourceId): Storage
    {
        $storageId = $this->uuidGenerator->generateNewStorageId();

        return new Storage(
            $storageId,
            $resourceId,
            self::DEFAULT_STORAGE_AMOUNT,
            self::DEFAULT_STORAGE_LIMIT,
        );
    }
}
