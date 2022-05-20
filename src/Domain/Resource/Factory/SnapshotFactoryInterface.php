<?php

declare(strict_types=1);

namespace App\Domain\Resource\Factory;

use App\Domain\Resource\Entity\ResourceInterface;
use App\Domain\Resource\Entity\SnapshotInterface;
use Doctrine\Common\Collections\Collection;

interface SnapshotFactoryInterface
{
    /**
     * @var Collection<ResourceInterface>
     */
    public function createFirstInLine(Collection $resources): SnapshotInterface;

    public function create(SnapshotInterface $previous): SnapshotInterface;
}
