<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Factory;

use App\Component\Resource\Domain\Entity\ResourceInterface;
use App\Component\Resource\Domain\Entity\Snapshot;
use App\Component\Resource\Domain\Entity\SnapshotInterface;
use App\Component\Resource\Domain\Service\ResourceMetadata\ResourceMetadataInterface;
use App\Component\SharedKernel\Domain\PlanetInterface;
use Doctrine\Common\Collections\Collection;

final class SnapshotFactory implements SnapshotFactoryInterface
{
    private StorageFactoryInterface $storageFactory;

    /**
     * @var Collection<ResourceMetadataInterface>
     */
    private Collection $resourcesMetadata;

    public function __construct(
        StorageFactoryInterface $storageFactory,
        Collection $resourcesMetadata
    ) {
        $this->storageFactory = $storageFactory;
        $this->resourcesMetadata = $resourcesMetadata;
    }

    /**
     * @var Collection<ResourceInterface>
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
