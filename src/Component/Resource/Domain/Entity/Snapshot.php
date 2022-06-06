<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Entity;

use App\Component\Resource\Domain\Exception\OperatingOnClosedSnapshotException;
use App\Component\Resource\Domain\Exception\WorkingOnEmptySnapshotException;
use App\SharedKernel\DoctrineEntityTrait;
use App\SharedKernel\Domain\Entity\PlanetInterface;
use App\SharedKernel\Port\CollectionInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;

class Snapshot implements SnapshotInterface
{
    use DoctrineEntityTrait;

    private PlanetInterface $planet;

    /**
     * @var CollectionInterface<StorageInterface>
     */
    private CollectionInterface $storages;

    private ?SnapshotInterface $previous;

    public function __construct(?PlanetInterface $planet)
    {
        $this->planet = $planet;
        $this->storages = new ArrayCollection();
    }

    public function hasEnough(ResourceInterface $resource, int $amount): bool
    {
        foreach ($this->storages as $storage) {
            if ($storage->isFor($resource)) {
                return $storage->getAmount() >= $amount;
            }
        }

        return false;
    }

    public function addStorage(StorageInterface $storage): void
    {
        $this->storages->add($storage);
    }

    public function performOperation(OperationInterface $operation): void
    {
        if ($this->isClosed()) {
            throw new OperatingOnClosedSnapshotException($this, $operation);
        }

        foreach ($this->storages as $storage) {
            $storage->accept($operation);
        }
    }

    private function isClosed(): bool
    {
        if ($this->storages->isEmpty() === true) {
            throw new WorkingOnEmptySnapshotException($this);
        }

        return $this->storages->first()->isClosed();
    }

    public function linkPrevious(SnapshotInterface $previous): void
    {
        $this->previous = $previous;

        $this->planet = $previous->planet;
        foreach ($previous->storages as $previousStorage) {
            $previousStorage->lock();

            $newStorage = $previousStorage->cloneFor($this);
            $this->storages->add($newStorage);
        }
    }

    public function removeOperationsOverTime(
        $operationType,
        DateTimeInterface $time
    ): void {
        foreach ($this->storages as $storage) {
            $storage->removeOperationsOverTime($operationType, $time);
        }
    }
}
