<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Factory;

use App\Component\Resource\Domain\Entity\SnapshotInterface;
use App\Component\Resource\Domain\Entity\Storage;
use App\Component\Resource\Domain\Entity\StorageInterface;
use App\Component\Resource\Domain\Service\ResourceMetadata\ResourceMetadataInterface;
use App\Component\Resource\Port\ResourceRepositoryInterface;
use App\SharedKernel\Domain\Entity\PlanetInterface;

final class StorageFactory implements StorageFactoryInterface
{
    private OperationFactoryInterface $operationFactory;

    private ResourceRepositoryInterface $resourceRepository;

    public function __construct(
        OperationFactoryInterface $operationFactory,
        ResourceRepositoryInterface $resourceRepository
    ) {
        $this->operationFactory = $operationFactory;
        $this->resourceRepository = $resourceRepository;
    }

    public function create(
        PlanetInterface $planet,
        SnapshotInterface $snapshot,
        ResourceMetadataInterface $resourceMetadata
    ): StorageInterface {
        $resource = $this->resourceRepository->findOneByCode($resourceMetadata->getCode());

        $storage = new Storage(
            $resource,
            $resourceMetadata->getStartAmount($planet),
            $resourceMetadata->getMaxAmount($planet)
        );

        $speedChangeOperation = $this->operationFactory->createSpeedChange(
            $resource,
            $resourceMetadata->getStartSpeed($planet)
        );
        $storage->accept($speedChangeOperation);

        return $storage;
    }
}
