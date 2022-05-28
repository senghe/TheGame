<?php

declare(strict_types=1);

namespace Tests\CodeBase\Repository;

use App\Component\Resource\Domain\Entity\Snapshot;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

final class SnapshotRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAll(): Collection
    {
        return new ArrayCollection(
            $this->entityManager
                ->createQueryBuilder()
                ->select('s')
                ->from(Snapshot::class, 's')
                ->orderBy('s.id', 'ASC')
                ->getQuery()
                ->getResult()
        );
    }
}
