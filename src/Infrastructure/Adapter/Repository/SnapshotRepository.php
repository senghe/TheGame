<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter\Repository;

use App\Resource\Domain\Entity\Snapshot;
use App\Resource\Domain\Entity\SnapshotInterface;
use App\Resource\Domain\Port\SnapshotRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class SnapshotRepository implements SnapshotRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findLatest(): ?SnapshotInterface
    {
        $qb = $this->entityManager->createQueryBuilder();

        return $qb->select('s')
            ->from(Snapshot::class, 's')
            ->orderBy('s.id', 'DESC')
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    public function add(SnapshotInterface $snapshot): void
    {
        $this->entityManager->persist($snapshot);
    }
}
