<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain;

use App\Component\Resource\Domain\Entity\OperationInterface;
use App\Component\Resource\Domain\Entity\SnapshotInterface;
use App\Component\Resource\Domain\Enum\OperationType;
use App\Component\Resource\Domain\Exception\OperatingOnClosedSnapshotException;
use App\Component\Resource\Domain\Factory\SnapshotFactoryInterface;
use App\Component\Resource\Port\SnapshotRepositoryInterface;
use App\SharedKernel\EntityInterface;
use App\SharedKernel\Exception\AggregateRootNotBuiltException;
use DateTime;
use InvalidArgumentException;

final class ResourceAggregate implements AggregateInterface
{
    private SnapshotRepositoryInterface $snapshotRepository;

    private SnapshotFactoryInterface $snapshotFactory;

    private bool $isBuilt = false;

    private SnapshotInterface $currentSnapshot;

    public function __construct(
        SnapshotRepositoryInterface $snapshotRepository,
        SnapshotFactoryInterface $snapshotFactory
    ) {
        $this->snapshotRepository = $snapshotRepository;
        $this->snapshotFactory = $snapshotFactory;
    }

    public function setAggregateRoot(EntityInterface $currentSnapshot): void
    {
        if ($currentSnapshot instanceof SnapshotInterface::class === false) {
            throw new InvalidArgumentException(sprintf('%s class accepts only %s entities', self::class, SnapshotInterface::class));
        }

        $this->currentSnapshot = $currentSnapshot;
        $this->isBuilt = true;
    }

    public function performOperation(OperationInterface $operation): void
    {
        if ($this->isBuilt === false) {
            throw new AggregateRootNotBuiltException($this);
        }

        try {
            $this->currentSnapshot->performOperation($operation);
        } catch (OperatingOnClosedSnapshotException $e) {
            $this->currentSnapshot = $this->snapshotFactory->create($this->currentSnapshot);
            $this->snapshotRepository->add($this->currentSnapshot);

            $this->currentSnapshot->performOperation($operation);
        }
    }

    public function removeOperationsNotPerformedYet(
        OperationType $operationType
    ): void {
        $this->currentSnapshot->removeOperationsOverTime(
            $operationType,
            new DateTime()
        );
    }
}
