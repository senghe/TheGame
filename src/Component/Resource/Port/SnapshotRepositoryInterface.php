<?php

declare(strict_types=1);

namespace App\Component\Resource\Port;

use App\Component\Resource\Domain\Entity\SnapshotInterface;
use App\SharedKernel\Domain\Entity\PlanetInterface;

interface SnapshotRepositoryInterface
{
    public function findLatest(PlanetInterface $planet): ?SnapshotInterface;

    public function add(SnapshotInterface $snapshot): void;
}
