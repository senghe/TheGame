<?php

declare(strict_types=1);

namespace App\Domain\Resource\Factory;

use App\Domain\Resource\Entity\ResourceInterface;
use App\Domain\Resource\Entity\Snapshot;
use App\Domain\Resource\Entity\SnapshotInterface;
use Doctrine\Common\Collections\Collection;

final class SnapshotFactory implements SnapshotFactoryInterface
{
    private StorageFactoryInterface $storageFactory;

    public function __construct(StorageFactoryInterface $storageFactory)
    {
        $this->storageFactory = $storageFactory;
    }

    /**
     * @var Collection<ResourceInterface>
     */
    public function createFirstInLine(Collection $resources): SnapshotInterface
    {
        $snapshot = new Snapshot();

        foreach ($resources as $resource) {
            $storage = $this->storageFactory->create($snapshot, $resource);

            $snapshot->addStorage($storage);
        }

        return $snapshot;
    }

    public function create(SnapshotInterface $previous): SnapshotInterface
    {
        $snapshot = new Snapshot();
        $snapshot->linkPrevious($previous);

        return $snapshot;
    }
}
