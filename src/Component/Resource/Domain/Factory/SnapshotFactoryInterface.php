<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Factory;

use App\Component\Resource\Domain\Entity\ResourceInterface;
use App\Component\Resource\Domain\Entity\SnapshotInterface;
use App\SharedKernel\Domain\Entity\PlanetInterface;
use App\SharedKernel\Port\CollectionInterface;

interface SnapshotFactoryInterface
{
    /**
     * @var CollectionInterface<ResourceInterface>
     */
    public function createInitial(PlanetInterface $planet): SnapshotInterface;

    public function create(SnapshotInterface $previous): SnapshotInterface;
}
