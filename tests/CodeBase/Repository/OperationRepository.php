<?php

declare(strict_types=1);

namespace Tests\CodeBase\Repository;

use App\Resource\Domain\Entity\Operation;
use App\Resource\Domain\Entity\OperationInterface;
use App\Resource\Domain\Entity\SnapshotInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

final class OperationRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findByCode(string $code): OperationInterface
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('o')
            ->from(Operation::class, 'o')
            ->where('o.code = :code')
            ->setMaxResults(1)
            ->getQuery()
            ->setParameter('code', $code)
            ->getSingleResult();
    }

    public function findBySnapshot(SnapshotInterface $snapshot): Collection
    {
        return new ArrayCollection($this->entityManager
            ->createQueryBuilder()
            ->select('o')
            ->from(Operation::class, 'o')
            ->join('o.snapshot', 's')
            ->where('s = :snapshot')
            ->getQuery()
            ->setParameter('snapshot', $snapshot)
            ->getResult());
    }
}
