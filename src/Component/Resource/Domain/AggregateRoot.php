<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain;

use App\Component\Resource\Domain\Entity\OperationInterface;
use App\Component\Resource\Domain\Entity\ResourceInterface;
use App\Component\Resource\Domain\Entity\SnapshotInterface;
use App\Component\Resource\Domain\Exception\OperatingOnClosedSnapshotException;
use App\Component\Resource\Domain\Factory\SnapshotFactoryInterface;
use App\Component\Resource\Domain\Port\SnapshotRepositoryInterface;
use App\Component\SharedKernel\Domain\PlanetInterface;
use App\Component\SharedKernel\Exception\AggregateRootNotBuiltException;
use Doctrine\Common\Collections\Collection;

final class AggregateRoot implements AggregateRootInterface
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

    /**
     * @var Collection<ResourceInterface>
     */
    public function build(PlanetInterface $planet): void
    {
        $currentSnapshot = $this->snapshotRepository->findLatest($planet);
        if ($currentSnapshot === null) {
            $currentSnapshot = $this->snapshotFactory->createInitial($planet);
            $this->snapshotRepository->add($currentSnapshot);
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
}
