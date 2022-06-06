<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Factory;

use App\Component\Resource\Domain\Entity\ResourceInterface;
use App\Component\Resource\Domain\Entity\Snapshot;
use App\Component\Resource\Domain\Entity\SnapshotInterface;
use App\Component\Resource\Domain\Service\ResourceMetadata\ResourceMetadataInterface;
use App\SharedKernel\Domain\Entity\PlanetInterface;
use App\SharedKernel\Port\CollectionInterface;
use Doctrine\Common\Collections\ArrayCollection;

final class SnapshotFactory implements SnapshotFactoryInterface
{
    private StorageFactoryInterface $storageFactory;

    /**
     * @var CollectionInterface<ResourceMetadataInterface>
     */
    private CollectionInterface $resourcesMetadata;

    public function __construct(
        StorageFactoryInterface $storageFactory
    ) {
        $this->storageFactory = $storageFactory;
        $this->resourcesMetadata = new ArrayCollection();
    }

    public function addResourceMetadata(ResourceMetadataInterface $resourceMetadata): void
    {
        $this->resourcesMetadata->add($resourceMetadata);
    }

    /**
     * @var CollectionInterface<ResourceInterface>
     */
    public function createInitial(PlanetInterface $planet): SnapshotInterface
    {
        $snapshot = new Snapshot($planet);

        foreach ($this->resourcesMetadata as $resourceMetadata) {
            $storage = $this->storageFactory->create($planet, $snapshot, $resourceMetadata);

            $snapshot->addStorage($storage);
        }

        return $snapshot;
    }

    public function create(SnapshotInterface $previous): SnapshotInterface
    {
        $snapshot = new Snapshot(null);
        $snapshot->linkPrevious($previous);

        return $snapshot;
    }
}
