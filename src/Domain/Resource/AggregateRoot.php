<?php

declare(strict_types=1);

namespace App\Domain\Resource;

use App\Domain\Resource\Entity\OperationInterface;
use App\Domain\Resource\Entity\ResourceInterface;
use App\Domain\Resource\Entity\SnapshotInterface;
use App\Domain\Resource\Exception\OperatingOnClosedSnapshotException;
use App\Domain\Resource\Factory\SnapshotFactoryInterface;
use App\Domain\Resource\Port\SnapshotRepositoryInterface;
use App\Domain\SharedKernel\Exception\AggregateRootNotBuiltException;
use Doctrine\Common\Collections\Collection;
use Webmozart\Assert\Assert;

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
        Assert::allIsInstanceOf($resources, ResourceInterface::class);

        $this->currentSnapshot = $this->snapshotRepository->findLatest();
        if ($this->currentSnapshot === null) {
            $this->currentSnapshot = $this->snapshotFactory->createFirstInLine($resources);
            $this->snapshotRepository->add($this->currentSnapshot);
        }

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

    public function getResources(): Collection
    {
        if ($this->isBuilt === false) {
            throw new AggregateRootNotBuiltException($this);
        }

        return $this->currentSnapshot->getResourcesViewModel();
    }
}
