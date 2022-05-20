<?php

declare(strict_types=1);

namespace App\Domain\Resource\Port;

use App\Domain\Resource\Entity\SnapshotInterface;

interface SnapshotRepositoryInterface
{
    public function findLatest(): ?SnapshotInterface;

    public function add(SnapshotInterface $snapshot): void;
}
