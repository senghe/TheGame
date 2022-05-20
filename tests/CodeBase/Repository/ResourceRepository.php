<?php

declare(strict_types=1);

namespace Tests\CodeBase\Repository;

use App\Domain\Resource\Entity\Resource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

final class ResourceRepository
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
                ->select('r')
                ->from(Resource::class, 'r')
                ->getQuery()
                ->getResult()
        );
    }
}
