<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Port;

use App\Component\Resource\Domain\Entity\SnapshotInterface;
use App\Component\SharedKernel\Domain\PlanetInterface;

interface SnapshotRepositoryInterface
{
    public function findLatest(PlanetInterface $planet): ?SnapshotInterface;

    public function add(SnapshotInterface $snapshot): void;
}
