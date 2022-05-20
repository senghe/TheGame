<?php

declare(strict_types=1);

namespace App\Domain\Resource\Entity;

use App\Domain\Resource\Exception\OperatingOnClosedSnapshotException;
use App\SharedKernel\DoctrineEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Snapshot implements SnapshotInterface
{
    use DoctrineEntityTrait;

    public const MAX_OPERATIONS_COUNT = 10;

    /**
     * @var Collection<StorageInterface>
     */
    private Collection $storages;

    /**
     * @var Collection<OperationInterface>
     */
    private Collection $operations;

    private ?SnapshotInterface $previous;

    private ?SnapshotInterface $next;

    public function __construct()
    {
        $this->storages = new ArrayCollection();
        $this->operations = new ArrayCollection();
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

        $operation->linkToSnapshot($this);
        $this->operations->add($operation);
    }

    public function isClosed(): bool
    {
        return $this->operations->count() === self::MAX_OPERATIONS_COUNT;
    }

    public function linkPrevious(SnapshotInterface $previous): void
    {
        $this->previous = $previous;

        foreach ($previous->storages as $storage) {
            $newStorage = $storage->cloneFor($this);
            $this->storages->add($newStorage);
        }
    }

    public function getResourcesViewModel(): Collection
    {
        $viewModels = new ArrayCollection();

        foreach ($this->storages as $storage) {
            $viewModels->add($storage->toViewModel());
        }

        return $viewModels;
    }
}
