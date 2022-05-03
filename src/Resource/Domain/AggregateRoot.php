<?php

declare(strict_types=1);

namespace App\Resource\Domain;

use App\Resource\Domain\Entity\OperationInterface;
use App\Resource\Domain\Entity\ResourceInterface;
use App\Resource\Domain\Entity\SnapshotInterface;
use App\Resource\Domain\Exception\OperatingOnClosedSnapshotException;
use App\Resource\Domain\Factory\SnapshotFactoryInterface;
use App\Resource\Domain\Port\SnapshotRepositoryInterface;
use App\SharedKernel\Exception\AggregateRootNotBuiltException;
use Doctrine\Common\Collections\Collection;

final class AggregateRoot implements AggregateRootInterface
{
    private SnapshotRepositoryInterface $snapshotRepository;

    private SnapshotFactoryInterface $snapshotFactory;

    private bool $isBuilt = false;

    private ?SnapshotInterface $currentSnapshot;

    public function __construct(
        SnapshotRepositoryInterface $snapshotRepository,
        SnapshotFactoryInterface $snapshotFactory
    ) {
        $this->snapshotRepository = $snapshotRepository;
        $this->snapshotFactory = $snapshotFactory;
    }

    /**
     * @var Collection<ResourceInterface>
     */
    public function build(Collection $resources): void
    {
        $this->currentSnapshot = $this->snapshotRepository->findLatest();
        if ($this->currentSnapshot === null) {
            $this->currentSnapshot = $this->snapshotFactory->createFirstInLine($resources);
            $this->snapshotRepository->add($this->currentSnapshot);
        }

        $this->isBuilt = true;
    }

    public function performOperation(OperationInterface $operation): void
    {
        if (! $this->isBuilt) {
            throw new AggregateRootNotBuiltException($this);
        }

        try {
            $this->currentSnapshot->performOperation($operation);
        } catch (OperatingOnClosedSnapshotException $e) {
            $this->currentSnapshot = $this->snapshotFactory->create($this->currentSnapshot);
            $this->currentSnapshot->performOperation($operation);

            $this->snapshotRepository->add($this->currentSnapshot);
        }
    }

    public function getResources(): Collection
    {
        if (! $this->isBuilt) {
            throw new AggregateRootNotBuiltException($this);
        }

        return $this->currentSnapshot->getResourcesViewModel();
    }
}
