<?php

declare(strict_types=1);

namespace App\Component\Resource\Application;

use App\Component\Resource\Domain\Entity\SnapshotInterface;
use App\Component\Resource\Domain\Factory\SnapshotFactoryInterface;
use App\Component\Resource\Port\SnapshotRepositoryInterface;
use App\SharedKernel\Domain\Entity\PlanetInterface;

class RootSnapshotProvider implements RootSnapshotProviderInterface
{
    private SnapshotRepositoryInterface $snapshotRepository;

    private SnapshotFactoryInterface $snapshotFactory;

    public function __construct(
        SnapshotRepositoryInterface $snapshotRepository,
        SnapshotFactoryInterface $snapshotFactory
    ) {
        $this->snapshotRepository = $snapshotRepository;
        $this->snapshotFactory = $snapshotFactory;
    }

    public function provide(PlanetInterface $planet): SnapshotInterface
    {
        $currentSnapshot = $this->snapshotRepository->findLatest($planet);
        if ($currentSnapshot === null) {
            $currentSnapshot = $this->snapshotFactory->createInitial($planet);
            $this->snapshotRepository->add($currentSnapshot);
        }

        return $currentSnapshot;
    }
}