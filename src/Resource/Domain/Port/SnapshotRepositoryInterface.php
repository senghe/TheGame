<?php

declare(strict_types=1);

namespace App\Resource\Domain\Port;

use App\Resource\Domain\Entity\SnapshotInterface;

interface SnapshotRepositoryInterface
{
    public function findLatest(): ?SnapshotInterface;

    public function add(SnapshotInterface $snapshot): void;
}
